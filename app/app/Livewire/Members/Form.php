<?php

declare(strict_types=1);

namespace App\Livewire\Members;

use App\Actions\Notifications\CreateActionNotification;
use App\Actions\Subscriptions\CreateSubscription;
use App\Enums\ChangeLabel;
use App\Enums\MemberStatus;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\PlanStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Club;
use App\Models\Member;
use App\Models\MemberClubHistory;
use App\Models\Plan;
use App\Support\Money;
use App\Support\PhoneNumber;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

class Form extends Component
{
    use ResolvesMembership;

    public ?Member $member = null;

    public string $name = '';

    public string $phone = '';

    public string $dateOfBirth = '';

    public string $gender = '';

    public ?int $primaryClubId = null;

    public string $addressLine = '';

    public string $joinedAt = '';

    public string $status = 'active';

    public string $notes = '';

    public string $emergencyName = '';

    public string $emergencyPhone = '';

    /** Optional starting plan, offered only while creating (admins only). */
    public ?int $planId = null;

    public string $planStartDate = '';

    /** Discount on the plan, prefilled from the chosen club's standing discount. */
    public string $planDiscount = '';

    public ?int $transferClubId = null;

    public string $transferReason = '';

    public function mount(?Member $member = null): void
    {
        $this->member = $member;

        $this->authorize($member ? 'update' : 'create', $member ?? Member::class);

        if ($member) {
            $this->name = $member->name;
            $this->phone = (string) $member->phone;
            $this->dateOfBirth = $member->date_of_birth ? $member->date_of_birth->toDateString() : '';
            $this->gender = (string) $member->gender;
            $this->primaryClubId = $member->primary_club_id;
            $this->addressLine = (string) ($member->address['line1'] ?? '');
            $this->joinedAt = $member->joined_at->toDateString();
            $this->status = $member->status->value;
            $this->notes = (string) $member->notes;
            $this->emergencyName = (string) ($member->emergency_contact['name'] ?? '');
            $this->emergencyPhone = (string) ($member->emergency_contact['phone'] ?? '');
        } else {
            $this->joinedAt = now($this->organisation()->timezone)->toDateString();
            $this->planStartDate = $this->joinedAt;
            $this->primaryClubId = $this->presetClubId();
        }
    }

    /**
     * The club a new member starts in when the form is opened from a club
     * page (`?club=`), or the only club the acting user can add to. Anything
     * outside the user's reach is ignored rather than rejected, since the
     * picker below only offers reachable clubs anyway.
     */
    private function presetClubId(): ?int
    {
        $accessible = $this->accessibleClubs()->pluck('id')->all();
        $requested = (int) request()->query('club', 0);

        if ($requested > 0 && in_array($requested, $accessible, true)) {
            return $requested;
        }

        return count($accessible) === 1 ? $accessible[0] : null;
    }

    public function save(): void
    {
        $this->authorize($this->member ? 'update' : 'create', $this->member ?? Member::class);

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'dateOfBirth' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'max:30'],
            // Limited to the acting user's clubs, not merely the organisation's:
            // staff may only add members to clubs they are assigned to.
            'primaryClubId' => ['required', Rule::in($this->accessibleClubs()->pluck('id')->all())],
            'addressLine' => ['nullable', 'string', 'max:255'],
            'joinedAt' => ['required', 'date'],
            'status' => ['required', Rule::enum(MemberStatus::class)],
            'notes' => ['nullable', 'string', 'max:2000'],
            'emergencyName' => ['nullable', 'string', 'max:255'],
            'emergencyPhone' => ['nullable', 'string', 'max:50'],
            // The plan is only offered on create, and only to admins — the
            // same rule as starting one from the member page.
            'planId' => [
                'nullable',
                Rule::prohibitedIf($this->member !== null || ! $this->canStartPlan()),
                Rule::exists('plans', 'id')->where('organisation_id', app('tenant')->id)->where('status', PlanStatus::Active->value),
            ],
            'planStartDate' => ['nullable', 'date'],
            'planDiscount' => ['nullable', 'numeric', 'min:0'],
        ], [
            'primaryClubId.in' => 'You can only add '.strtolower($this->organisation()->term('member_plural')).' to a '.strtolower($this->organisation()->term('club_singular')).' you are assigned to.',
        ]);

        $membership = $this->currentMembership();

        // Stored normalised so search, duplicate detection and the WhatsApp
        // deep link all agree on the same value (MEP.md 10).
        $normalisedPhone = PhoneNumber::normalise($validated['phone'], $this->organisation()->defaultCountry());

        if ($normalisedPhone === null) {
            $this->addError('phone', 'Enter a valid WhatsApp number.');

            return;
        }

        $attributes = [
            'name' => $validated['name'],
            'phone' => $normalisedPhone,
            'date_of_birth' => $validated['dateOfBirth'] ?: null,
            'gender' => $validated['gender'] ?: null,
            'address' => $validated['addressLine'] ? ['line1' => $validated['addressLine']] : null,
            'joined_at' => $validated['joinedAt'],
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?: null,
            'emergency_contact' => $validated['emergencyName'] || $validated['emergencyPhone']
                ? ['name' => $validated['emergencyName'] ?: null, 'phone' => $validated['emergencyPhone'] ?: null]
                : null,
        ];

        if ($this->member) {
            $this->member->fill($attributes);
            // Captured before saving so the notification can name what changed
            // using an allowlisted label (MEP.md 6.8).
            $changed = array_keys($this->member->getDirty());
            $this->member->save();

            $member = $this->member;
            $type = NotificationActionType::MemberProfileUpdated;
            $context = ['changedItem' => ChangeLabel::forColumns($changed)->label()];
        } else {
            $member = Member::create([
                ...$attributes,
                'primary_club_id' => $validated['primaryClubId'],
                'admission_fee_minor' => $this->admissionFeeFor((int) $validated['primaryClubId']),
                'created_by' => $membership->id,
            ]);

            $type = NotificationActionType::MemberCreated;
            $context = ['memberId' => $this->organisation()->reference('member', $member->id)];
        }

        $notification = app(CreateActionNotification::class)->handle(
            organisation: $this->organisation(),
            type: $type,
            recipientType: NotificationRecipientType::Member,
            recipientId: $member->id,
            recipientName: $member->name,
            recipientPhone: $member->phone,
            entityType: NotificationEntityType::Member,
            entityId: $member->id,
            actor: $membership,
            operationId: $type->value.'.'.$member->id.'.'.now()->timestamp,
            context: [...$context, 'clubName' => $member->primaryClub?->name],
        );

        $startedPlan = null;

        if ($this->member === null && $validated['planId'] !== null) {
            $startedPlan = $this->startPlanFor($member, (int) $validated['planId'], $validated['planStartDate'], $validated['planDiscount']);
        }

        session()->flash('status', $startedPlan === null
            ? "\"{$validated['name']}\" was saved."
            : "\"{$validated['name']}\" was saved and \"{$startedPlan->name}\" started.");
        session()->flash('notification_id', $notification?->id);

        $this->redirect(route('tenant.members.show', $member), navigate: true);
    }

    /**
     * The plan follows the joining date until the operator sets it apart:
     * a start date still equal to the old joining date moves with it.
     */
    public function updatingJoinedAt(string $value): void
    {
        if ($this->planStartDate === '' || $this->planStartDate === $this->joinedAt) {
            $this->planStartDate = $value;
        }
    }

    /**
     * Only admins may start plans (MemberSubscriptionPolicy::createFor), so
     * staff creating a member never see the option.
     */
    public function canStartPlan(): bool
    {
        return $this->member === null && $this->currentMembership()->isAdmin();
    }

    /**
     * Starts the chosen plan on the freshly created member. Runs after the
     * member row exists and outside its creation, so a problem here leaves a
     * member who can be given a plan from their page rather than no member.
     */
    private function startPlanFor(Member $member, int $planId, ?string $startDate, mixed $discount): Plan
    {
        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($planId);

        $organisation = $this->organisation();

        $discountMinor = $discount !== null && $discount !== ''
            ? Money::parseMajor((string) $discount, $organisation->currency_code)?->minor
            : null;

        app(CreateSubscription::class)->handle(
            member: $member,
            plan: $plan,
            actor: $this->currentMembership(),
            startDate: $startDate ? Carbon::parse($startDate) : null,
            discountMinor: $discountMinor,
        );

        return $plan;
    }

    /**
     * The club's standing discount on the chosen plan, prefilled whenever
     * either choice changes; the operator can still edit or clear it.
     */
    public function updatedPlanId(): void
    {
        $this->prefillPlanDiscount();
    }

    public function updatedPrimaryClubId(): void
    {
        $this->prefillPlanDiscount();
    }

    private function prefillPlanDiscount(): void
    {
        $plan = $this->planId ? Plan::query()->find($this->planId) : null;
        $club = $this->primaryClubId ? Club::query()->find($this->primaryClubId) : null;

        $this->planDiscount = $plan && $club && $club->discountFor($plan) > 0
            ? (string) Money::ofMinor($club->discountFor($plan), $this->organisation()->currency_code)->major()
            : '';
    }

    /**
     * The admission fee this member signs up under: the club's fee at the
     * moment of joining, frozen on the member so a later change to the club
     * does not alter what an existing member owes.
     */
    private function admissionFeeFor(int $clubId): int
    {
        return (int) Club::query()->whereKey($clubId)->value('admission_fee_minor');
    }

    /**
     * A member has one current club — transferring writes a history row
     * rather than silently overwriting the past (MEP.md Section 5.6/5.7).
     */
    public function transfer(): void
    {
        $member = $this->member;
        abort_unless($member !== null, 404);

        $this->authorize('transfer', $member);

        $validated = $this->validate([
            'transferClubId' => [
                'required', 'different:primaryClubId',
                Rule::exists('clubs', 'id')->where('organisation_id', app('tenant')->id),
            ],
            'transferReason' => ['nullable', 'string', 'max:500'],
        ]);

        $membership = $this->currentMembership();

        DB::transaction(function () use ($member, $validated, $membership): void {
            MemberClubHistory::create([
                'member_id' => $member->id,
                'from_club_id' => $member->primary_club_id,
                'to_club_id' => $validated['transferClubId'],
                'reason' => $validated['transferReason'] ?: null,
                'changed_at' => now(),
                'changed_by' => $membership->id,
            ]);

            $member->update(['primary_club_id' => $validated['transferClubId']]);
        });

        $member->refresh();

        // The notification is created only after the transfer has committed,
        // and a failure here must never undo it (MEP.md 8.2).
        $notification = app(CreateActionNotification::class)->handle(
            organisation: $this->organisation(),
            type: NotificationActionType::MemberClubTransferred,
            recipientType: NotificationRecipientType::Member,
            recipientId: $member->id,
            recipientName: $member->name,
            recipientPhone: $member->phone,
            entityType: NotificationEntityType::Member,
            entityId: $member->id,
            actor: $membership,
            operationId: 'member.transfer.'.$member->id.'.'.$validated['transferClubId'],
            context: [
                'clubName' => $member->primaryClub?->name,
                'effectiveDate' => now($this->organisation()->timezone)->format('d M Y'),
            ],
        );

        $this->primaryClubId = (int) $validated['transferClubId'];
        $this->transferClubId = null;
        $this->transferReason = '';

        session()->flash('status', "{$member->name} was transferred.");
        session()->flash('notification_id', $notification?->id);

        $this->redirect(route('tenant.members.show', $member), navigate: true);
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        $history = $this->member
            ? $this->member->clubHistory()->with(['fromClub:id,name', 'toClub:id,name'])->latest('changed_at')->get()
            : collect();

        return view('livewire.members.form', [
            'organisation' => $organisation,
            'availableClubs' => $this->accessibleClubs(),
            'canStartPlan' => $this->canStartPlan(),
            'availablePlans' => $this->canStartPlan()
                ? Plan::query()->where('status', PlanStatus::Active)->orderBy('name')->get()
                : collect(),
            'history' => $history,
            'statuses' => MemberStatus::cases(),
        ])->layout('components.layouts.app', [
            'heading' => $this->member
                ? "Edit {$this->member->name}"
                : 'New '.$organisation->term('member_singular'),
        ]);
    }
}
