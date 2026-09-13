<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Enums\NotificationStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\WhatsappActionNotification;
use App\Support\PhoneNumber;
use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

/**
 * The post-action WhatsApp panel shared by every admin action that can notify
 * a member or staff user (MEP.md 6.8, 9.2).
 *
 * Recording that the deep link was opened is explicitly *not* a delivery
 * receipt — the status means "the admin launched WhatsApp", nothing more, and
 * the UI is worded that way. Skipping never rolls back the business action
 * that produced the notification.
 */
class ActionPanel extends Component
{
    use ResolvesMembership;

    public ?int $notificationId = null;

    public bool $editing = false;

    public string $draft = '';

    public function mount(?int $notificationId = null): void
    {
        $this->notificationId = $notificationId;
        $this->draft = $this->notification()->message_snapshot ?? '';
    }

    #[On('notification-created')]
    public function show(int $notificationId): void
    {
        $this->notificationId = $notificationId;
        $this->editing = false;
        $this->draft = $this->notification()->message_snapshot ?? '';
    }

    public function markOpened(): void
    {
        $notification = $this->notification();

        if (! $notification) {
            return;
        }

        $this->authorize('manageSettings', $this->organisation());

        $notification->forceFill([
            'status' => NotificationStatus::Opened,
            'opened_by' => $this->currentMembership()->id,
            'opened_at' => now(),
        ])->save();
    }

    public function skip(): void
    {
        $notification = $this->notification();

        if (! $notification) {
            return;
        }

        $this->authorize('manageSettings', $this->organisation());

        $notification->forceFill(['status' => NotificationStatus::Skipped])->save();

        $this->notificationId = null;
    }

    public function dismiss(): void
    {
        $this->notificationId = null;
    }

    protected function notification(): ?WhatsappActionNotification
    {
        return $this->notificationId === null
            ? null
            : WhatsappActionNotification::query()->find($this->notificationId);
    }

    public function render(): View
    {
        $notification = $this->notification();
        $organisation = $this->organisation();

        // The admin may tweak the wording before sending, but the stored
        // snapshot of what the system generated is never overwritten.
        $message = $this->editing && $this->draft !== '' ? $this->draft : ($notification->message_snapshot ?? '');

        return view('livewire.notifications.action-panel', [
            'notification' => $notification,
            'message' => $message,
            'whatsappUrl' => $notification === null
                ? null
                : PhoneNumber::whatsappUrl($notification->recipient_phone, $message, $organisation->defaultCountry()),
            'displayPhone' => PhoneNumber::forDisplay($notification?->recipient_phone, $organisation->defaultCountry()),
        ]);
    }
}
