<?php

declare(strict_types=1);

namespace App\Livewire\Finance;

use App\Actions\Payments\ConfirmFeePayment;
use App\Actions\Payments\RejectFeePayment;
use App\Enums\ConfirmationStatus;
use App\Exceptions\LifecycleViolation;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\FeePayment;
use App\Models\Member;
use App\Models\OrganisationUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * The admin queue for staff-submitted payments (MEP.md 6.8).
 *
 * Kept separate from the ledger deliberately: this is a work queue with
 * ageing, where the oldest submission is the most urgent, rather than a
 * financial record to browse.
 */
class Confirmations extends Component
{
    use ResolvesMembership, WithPagination;

    #[Url]
    public string $club = '';

    #[Url]
    public string $collector = '';

    public ?int $rejectingId = null;

    public string $rejectionReason = '';

    public ?string $lifecycleError = null;

    public ?int $lastConfirmedNotificationId = null;

    public function mount(): void
    {
        $this->authorize('viewAny', FeePayment::class);
        abort_unless($this->currentMembership()->isAdmin(), 403);
    }

    public function confirm(int $paymentId): void
    {
        $payment = $this->pendingPayment($paymentId);

        $this->authorize('confirm', $payment);

        try {
            $result = app(ConfirmFeePayment::class)->handle($payment, $this->currentMembership());
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();

            return;
        }

        $this->lifecycleError = null;
        $this->lastConfirmedNotificationId = $result->notification?->id;

        // Dispatched rather than left to the prop: a child Livewire component
        // keeps its own state across a parent re-render, so a freshly composed
        // message only reaches an already-mounted panel as an event.
        if ($result->notification !== null) {
            $this->dispatch('notification-created', notificationId: $result->notification->id);
        }

        /** @var Member $member */
        $member = $result->payment->member;

        // Naming the account in the confirmation makes the balance change
        // traceable from the notice alone, without opening the payment.
        $account = $result->payment->financialAccount?->name;

        session()->flash('status', $account === null
            ? 'Payment confirmed for '.$member->name.'.'
            : 'Payment confirmed for '.$member->name.' — credited to '.$account.'.');
    }

    public function startReject(int $paymentId): void
    {
        $this->rejectingId = $paymentId;
        $this->rejectionReason = '';
        $this->resetErrorBag();

        $this->dispatch('open-modal', 'reject-queued-payment');
    }

    public function reject(): void
    {
        $payment = $this->pendingPayment((int) $this->rejectingId);

        $this->authorize('reject', $payment);

        $this->validate(
            ['rejectionReason' => ['required', 'string', 'min:3', 'max:255']],
            ['rejectionReason.required' => 'A reason is required so the collector knows why this was rejected.'],
        );

        try {
            app(RejectFeePayment::class)->handle($payment, $this->currentMembership(), $this->rejectionReason);
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();

            return;
        }

        $this->reset(['rejectingId', 'rejectionReason']);
        $this->dispatch('close-modal');

        session()->flash('status', 'Payment rejected.');
    }

    private function pendingPayment(int $paymentId): FeePayment
    {
        /** @var FeePayment $payment */
        $payment = FeePayment::query()
            ->with(['member:id,name', 'financialAccount:id,name'])
            ->findOrFail($paymentId);

        return $payment;
    }

    /**
     * @return LengthAwarePaginator<int, FeePayment>
     */
    protected function queue(): LengthAwarePaginator
    {
        return FeePayment::query()
            // financialAccount is eager-loaded because the queue names the
            // receiving account on every row — an admin confirming a payment
            // is signing off that the money reached that account.
            ->with([
                'member:id,name,phone',
                'club:id,name',
                'collectedBy.user:id,name',
                'subscription.plan:id,name',
                'financialAccount:id,name,account_type',
            ])
            ->where('confirmation_status', ConfirmationStatus::PendingAdminConfirmation)
            ->when($this->club !== '', fn (Builder $query) => $query->where('club_id', $this->club))
            ->when($this->collector !== '', fn (Builder $query) => $query->where('collected_by', $this->collector))
            // Oldest first: ageing submissions are the ones that need action.
            ->orderBy('created_at')
            ->paginate(15);
    }

    public function render(): View
    {
        $pending = FeePayment::query()->where('confirmation_status', ConfirmationStatus::PendingAdminConfirmation);

        return view('livewire.finance.confirmations', [
            'organisation' => $this->organisation(),
            'payments' => $this->queue(),
            'clubs' => $this->accessibleClubs(true),
            'collectors' => OrganisationUser::query()->with('user:id,name')->get(),
            'pendingTotal' => (int) $pending->clone()->sum('amount_minor'),
            'pendingCount' => $pending->clone()->count(),
            'oldest' => $pending->clone()->orderBy('created_at')->value('created_at'),
        ])->layout('components.layouts.app', ['heading' => 'Confirmations']);
    }
}
