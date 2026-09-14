<?php

declare(strict_types=1);

namespace App\Livewire\Billing;

use App\Actions\Billing\VoidInvoice;
use App\Enums\NotificationEntityType;
use App\Exceptions\LifecycleViolation;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Invoice;
use App\Models\WhatsappActionNotification;
use Illuminate\View\View;
use Livewire\Component;

/**
 * One invoice: its lines, what has been paid against it, and what happens next.
 */
class Show extends Component
{
    use ResolvesMembership;

    public Invoice $invoice;

    public string $voidReason = '';

    public ?string $lifecycleError = null;

    public ?int $notificationId = null;

    public function mount(Invoice $invoice): void
    {
        $this->authorize('view', $invoice);

        $this->invoice = $invoice;

        // Straight after issuing, the form hands over the message it composed.
        $this->notificationId = session('notification_id') ?? $this->latestNotificationId();
    }

    public function startVoid(): void
    {
        $this->authorize('void', $this->invoice);

        $this->voidReason = '';
        $this->resetErrorBag();
        $this->dispatch('open-modal', 'void-invoice');
    }

    public function void(): void
    {
        $this->authorize('void', $this->invoice);

        $this->validate(
            ['voidReason' => ['required', 'string', 'min:3', 'max:255']],
            ['voidReason.required' => 'Say why, so the gap in the numbering is explained later.'],
        );

        try {
            app(VoidInvoice::class)->handle($this->invoice, $this->currentMembership(), $this->voidReason);
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();
            $this->dispatch('close-modal');

            return;
        }

        $this->invoice->refresh();
        $this->lifecycleError = null;
        $this->dispatch('close-modal');

        session()->flash('status', 'Invoice '.$this->invoice->number.' voided.');
    }

    private function latestNotificationId(): ?int
    {
        /** @var int|null $id */
        $id = WhatsappActionNotification::query()
            ->where('entity_type', NotificationEntityType::Invoice)
            ->where('entity_id', $this->invoice->id)
            ->latest('id')
            ->value('id');

        return $id;
    }

    public function render(): View
    {
        $this->invoice->load([
            'lines',
            'member:id,name,phone',
            'club:id,name',
            'createdBy.user:id,name',
            'voidedBy.user:id,name',
            'payments' => fn ($payments) => $payments
                ->with(['collectedBy.user:id,name', 'financialAccount:id,name'])
                ->orderByDesc('payment_date')
                ->orderByDesc('id'),
        ]);

        return view('livewire.billing.show', [
            'organisation' => $this->organisation(),
            'canNotify' => auth()->user()?->can('sendNotifications', $this->organisation()) ?? false,
        ])->layout('components.layouts.app', ['heading' => $this->invoice->number]);
    }
}
