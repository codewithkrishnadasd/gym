<?php

declare(strict_types=1);

namespace App\Livewire\Members;

use App\Actions\Subscriptions\ChangeSubscriptionStatus;
use App\Actions\Subscriptions\CreateSubscription;
use App\Enums\AttendanceAction;
use App\Enums\AttendanceSubjectType;
use App\Enums\ConfirmationStatus;
use App\Enums\MemberStatus;
use App\Enums\NotificationEntityType;
use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Exceptions\LifecycleViolation;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Plan;
use App\Models\WhatsappActionNotification;
use App\Support\Money;
use App\Support\PhoneNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * The member's full record: identity, plans, attendance, and payment ledger,
 * with the contextual actions the acting user is permitted to take
 * (MEP.md 6.6).
 */
class Show extends Component
{
    use ResolvesMembership;

    public Member $member;

    #[Url]
    public string $tab = 'overview';

    public ?int $planId = null;

    public string $planStartDate = '';

    /** Discount on the plan, prefilled from the club's standing discount. */
    public string $planDiscount = '';

    public ?string $lifecycleError = null;

    /** Set by the action that redirected here (create, edit, transfer). */
    public ?int $notificationId = null;

    public function mount(Member $member): void
    {
        $this->authorize('view', $member);

        $this->member = $member;
        $this->notificationId = session('notification_id');
        $this->planStartDate = Carbon::today($this->organisation()->timezone)->toDateString();
    }

    /**
     * Opens the plan dialog set up for the likely case: a renewal of the
     * plan currently running, starting the day after the latest active term
     * ends (or today, if every term has already lapsed). Everything stays
     * editable.
     */
    public function prepareRenewal(): void
    {
        $this->authorize('createFor', [MemberSubscription::class, $this->member]);

        $active = $this->member->subscriptions()
            ->where('status', SubscriptionStatus::Active)
            ->orderByDesc('end_date')
            ->get();

        $today = Carbon::today($this->organisation()->timezone);
        $latest = $active->first();

        $this->planId = $latest?->plan_id;
        $this->planStartDate = $latest && $latest->end_date->toDateString() >= $today->toDateString()
            ? $latest->end_date->copy()->addDay()->toDateString()
            : $today->toDateString();

        $this->updatedPlanId($this->planId);
        $this->resetErrorBag();

        $this->dispatch('open-modal', 'start-plan');
    }

    public function startPlanToday(): void
    {
        $this->planStartDate = Carbon::today($this->organisation()->timezone)->toDateString();
    }

    /**
     * Picking a plan prefills the club's standing discount on it; the
     * operator can still change or clear it before starting.
     */
    public function updatedPlanId(mixed $value): void
    {
        $plan = $value ? Plan::query()->find((int) $value) : null;
        $club = $this->member->primaryClub;

        $this->planDiscount = $plan && $club && $club->discountFor($plan) > 0
            ? (string) Money::ofMinor($club->discountFor($plan), $this->organisation()->currency_code)->major()
            : '';
    }

    public function startPlan(): void
    {
        $this->authorize('createFor', [MemberSubscription::class, $this->member]);

        $organisation = $this->organisation();

        $validated = $this->validate([
            'planId' => ['required', Rule::exists('plans', 'id')->where('organisation_id', $organisation->id)],
            'planStartDate' => ['nullable', 'date'],
            'planDiscount' => ['nullable', 'numeric', 'min:0'],
        ], [], ['planId' => 'plan', 'planDiscount' => 'discount']);

        if ($this->member->primary_club_id === null) {
            $this->addError('planId', 'Assign a '.strtolower($organisation->term('club_singular')).' before starting a plan.');

            return;
        }

        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($validated['planId']);

        $discount = $validated['planDiscount'] !== null && $validated['planDiscount'] !== ''
            ? Money::parseMajor((string) $validated['planDiscount'], $organisation->currency_code)?->minor
            : null;

        if ($discount !== null && $discount > $plan->price_minor) {
            $this->addError('planDiscount', 'The discount cannot be more than the plan price of '.$organisation->money($plan->price_minor).'.');

            return;
        }

        $subscription = app(CreateSubscription::class)->handle(
            member: $this->member,
            plan: $plan,
            actor: $this->currentMembership(),
            startDate: $validated['planStartDate'] ? Carbon::parse($validated['planStartDate']) : null,
            discountMinor: $discount,
        );

        $this->reset(['planId', 'planDiscount']);
        $this->dispatch('close-modal', 'start-plan');

        // Straight on to collecting the fee for the term just created, with
        // the member and the term already chosen. The plan-started WhatsApp
        // message waits in Messages.
        session()->flash('status', "\"{$plan->name}\" ".($subscription->start_date->isFuture() ? 'renewed' : 'started')." for {$this->member->name} — collect the fee below.");
        session()->flash('notification_id', $this->latestSubscriptionNotification($subscription->id));

        $this->redirect(route('tenant.finance.payments.create', ['member' => $this->member->id, 'subscription' => $subscription->id]), navigate: true);
    }

    public function changePlanStatus(int $subscriptionId, string $status): void
    {
        /** @var MemberSubscription $subscription */
        $subscription = MemberSubscription::query()
            ->where('member_id', $this->member->id)
            ->findOrFail($subscriptionId);

        $this->authorize('changeStatus', $subscription);

        try {
            app(ChangeSubscriptionStatus::class)->handle(
                $subscription,
                SubscriptionStatus::from($status),
                $this->currentMembership(),
            );
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();

            return;
        }

        $this->lifecycleError = null;

        // Pausing, resuming and cancelling all compose a message for the
        // member. Without this the notification was created and then never
        // shown to anyone, so it sat unsent in the queue.
        $this->showNotification($this->latestSubscriptionNotification($subscription->id));

        session()->flash('status', 'Plan updated.');
    }

    /**
     * The subscription actions return the subscription rather than the
     * notification they composed, so the panel finds it by the entity it was
     * written against.
     */
    private function showNotification(?int $notificationId): void
    {
        $this->notificationId = $notificationId;

        // Dispatched rather than left to the prop: a child Livewire component
        // keeps its own state across a parent re-render, so a freshly composed
        // message only reaches an already-mounted panel as an event.
        if ($notificationId !== null) {
            $this->dispatch('notification-created', notificationId: $notificationId);
        }
    }

    private function latestSubscriptionNotification(int $subscriptionId): ?int
    {
        /** @var int|null $id */
        $id = WhatsappActionNotification::query()
            ->where('entity_type', NotificationEntityType::Subscription)
            ->where('entity_id', $subscriptionId)
            ->latest('id')
            ->value('id');

        return $id;
    }

    public function archive(): void
    {
        $this->authorize('archive', $this->member);

        $this->member->update(['status' => MemberStatus::Archived]);

        session()->flash('status', "{$this->member->name} was removed.");
    }

    public function restore(): void
    {
        $this->authorize('restore', $this->member);

        $this->member->update(['status' => MemberStatus::Active]);

        session()->flash('status', "{$this->member->name} was restored.");
    }

    /**
     * @return Collection<int, MemberSubscription>
     */
    protected function subscriptions(): Collection
    {
        return $this->member->subscriptions()
            ->with('plan:id,name,duration_days', 'club:id,name')
            ->orderByDesc('start_date')
            ->get();
    }

    /**
     * @return Collection<int, FeePayment>
     */
    protected function payments(): Collection
    {
        return $this->member->feePayments()
            ->with(['club:id,name', 'collectedBy.user:id,name', 'subscription.plan:id,name', 'invoice:id,number'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->get();
    }

    /**
     * The last 12 weeks of marks, keyed by date, for the attendance calendar.
     *
     * @return Collection<string, Attendance>
     */
    protected function attendanceByDate(): Collection
    {
        return Attendance::query()
            ->where('subject_type', AttendanceSubjectType::Member->value)
            ->where('subject_id', $this->member->id)
            ->whereDate('attendance_date', '>=', Carbon::today($this->organisation()->timezone)->subWeeks(12)->toDateString())
            ->get()
            ->keyBy(fn (Attendance $attendance): string => $attendance->attendance_date->toDateString());
    }

    public function render(): View
    {
        $organisation = $this->organisation();
        $subscriptions = $this->subscriptions();
        $payments = $this->payments();
        $attendance = $this->attendanceByDate();

        $current = $subscriptions->firstWhere('status', SubscriptionStatus::Active);

        $confirmed = $payments->where('confirmation_status', ConfirmationStatus::Confirmed);
        $presentMarks = $attendance->whereIn('action', [AttendanceAction::Present, AttendanceAction::Late])->count();

        return view('livewire.members.show', [
            'organisation' => $organisation,
            'canNotify' => auth()->user()?->can('sendNotifications', $organisation) ?? false,
            'subscriptions' => $subscriptions,
            'currentSubscription' => $current,
            'payments' => $payments,
            'attendance' => $attendance,
            'attendanceRate' => $attendance->isEmpty() ? 0 : (int) round($presentMarks / $attendance->count() * 100),
            'presentMarks' => $presentMarks,
            'totalPaid' => (int) $confirmed->sum('amount_minor'),
            'unlinkedPaid' => $this->member->unlinkedPaidMinor(),
            'creditApplied' => $this->member->creditAppliedMinor(),
            'unlinkedAvailable' => $this->member->unlinkedCreditMinor(),
            // Plan balances plus whatever is left of the admission fee: the
            // one figure the desk needs when the member walks in.
            'outstanding' => (int) $subscriptions
                ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
                ->sum(fn (MemberSubscription $s): int => $s->outstandingMinor())
                + $this->member->admissionOutstandingMinor(),
            'clubHistory' => $this->member->clubHistory()->with(['fromClub:id,name', 'toClub:id,name', 'changedBy.user:id,name'])
                ->orderByDesc('changed_at')->get(),
            'availablePlans' => Plan::query()->where('status', PlanStatus::Active)->orderBy('name')->get(),
            'whatsappUrl' => PhoneNumber::whatsappUrl(
                $this->member->phone,
                "Hi {$this->member->name},",
                $organisation->defaultCountry(),
            ),
            'displayPhone' => PhoneNumber::forDisplay($this->member->phone, $organisation->defaultCountry()),
        ])->layout('components.layouts.app', ['heading' => $this->member->name]);
    }
}
