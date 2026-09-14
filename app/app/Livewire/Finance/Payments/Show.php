<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Payments;

use App\Actions\Payments\ConfirmFeePayment;
use App\Actions\Payments\RejectFeePayment;
use App\Actions\Payments\ReverseFeePayment;
use App\Enums\NotificationActionType;
use App\Exceptions\LifecycleViolation;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\FeePayment;
use App\Models\WhatsappActionNotification;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Payment detail and the admin confirmation workflow (MEP.md 6.8).
 *
 * The lifecycle buttons are gated by the policy *and* by the payment's
 * current state, and the underlying Action classes re-check the state inside
 * a locked transaction — so a stale page cannot double-confirm or confirm a
 * payment somebody else already rejected.
 */
class Show extends Component
{
    use ResolvesMembership;

    public FeePayment $payment;

    public string $rejectionReason = '';

    public string $reversalReason = '';

    public ?int $notificationId = null;

    public ?string $lifecycleError = null;

    public function mount(FeePayment $payment): void
    {
        $this->authorize('view', $payment);

        $this->payment = $payment;
        $this->notificationId = $this->latestNotification()?->id;
    }

    public function confirm(): void
    {
        $this->authorize('confirm', $this->payment);

        try {
            $result = app(ConfirmFeePayment::class)->handle($this->payment, $this->currentMembership());
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();
            $this->payment->refresh();

            return;
        }

        $this->payment = $result->payment->fresh() ?? $this->payment;
        $this->notificationId = $result->notification?->id;

        // Dispatched rather than left to the prop: a child Livewire component
        // keeps its own state across a parent re-render, so a freshly composed
        // message only reaches an already-mounted panel as an event.
        if ($result->notification !== null) {
            $this->dispatch('notification-created', notificationId: $result->notification->id);
        }

        $this->lifecycleError = null;

        $this->dispatch('close-modal');
        session()->flash('status', 'Payment confirmed.');
    }

    public function reject(): void
    {
        $this->authorize('reject', $this->payment);

        $this->validate(
            ['rejectionReason' => ['required', 'string', 'min:3', 'max:255']],
            ['rejectionReason.required' => 'A reason is required so the collector knows why this was rejected.'],
        );

        try {
            $this->payment = app(RejectFeePayment::class)->handle($this->payment, $this->currentMembership(), $this->rejectionReason);
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();
            $this->payment->refresh();

            return;
        }

        $this->rejectionReason = '';
        $this->dispatch('close-modal');
        session()->flash('status', 'Payment rejected. The original submission is preserved for audit.');
    }

    public function reverse(): void
    {
        $this->authorize('reverse', $this->payment);

        $this->validate(
            ['reversalReason' => ['required', 'string', 'min:3', 'max:255']],
            ['reversalReason.required' => 'A reason is required to reverse a confirmed payment.'],
        );

        try {
            $this->payment = app(ReverseFeePayment::class)->handle($this->payment, $this->currentMembership(), $this->reversalReason);
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();
            $this->payment->refresh();

            return;
        }

        $this->reversalReason = '';
        $this->dispatch('close-modal');
        session()->flash('status', 'Payment reversed. It remains in history and is excluded from current totals.');
    }

    protected function latestNotification(): ?WhatsappActionNotification
    {
        return WhatsappActionNotification::query()
            ->where('entity_id', $this->payment->id)
            ->where('action_type', NotificationActionType::FeePaymentConfirmed)
            ->latest('id')
            ->first();
    }

    /**
     * @return Collection<int, AuditEvent>
     */
    protected function history(): Collection
    {
        return AuditEvent::query()
            ->with('actor.user:id,name')
            ->where('entity_type', $this->payment->getMorphClass())
            ->where('entity_id', $this->payment->id)
            ->orderBy('id')
            ->get();
    }

    public function render(): View
    {
        $this->payment->loadMissing([
            'member:id,name,phone,primary_club_id',
            'club:id,name',
            'subscription.plan:id,name',
            'financialAccount:id,name,account_type',
            'collectedBy.user:id,name',
            'confirmedBy.user:id,name',
        ]);

        return view('livewire.finance.payments.show', [
            'organisation' => $this->organisation(),
            'history' => $this->history(),
            'isAdmin' => $this->currentMembership()->isAdmin(),
            'canNotify' => auth()->user()?->can('sendNotifications', $this->organisation()) ?? false,
        ])->layout('components.layouts.app', ['heading' => 'Payment PMT-'.$this->payment->id]);
    }
}
