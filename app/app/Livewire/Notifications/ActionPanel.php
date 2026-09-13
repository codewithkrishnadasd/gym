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
 * The post-action WhatsApp panel shared by every action that can notify a
 * member or staff user (MEP.md 6.8, 9.2).
 *
 * The message the admin sees is edited entirely on the client, and the deep
 * link is built from that same live value — a server round trip between typing
 * and clicking would open WhatsApp with stale text. The server is told the
 * final wording when the link is actually opened, so the record reflects what
 * was sent rather than what was originally generated.
 *
 * Recording that the link was opened is explicitly *not* a delivery receipt:
 * the status means "the operator launched WhatsApp", nothing more.
 */
class ActionPanel extends Component
{
    use ResolvesMembership;

    public ?int $notificationId = null;

    public function mount(?int $notificationId = null): void
    {
        $this->notificationId = $notificationId;
    }

    #[On('notification-created')]
    public function show(int $notificationId): void
    {
        $this->notificationId = $notificationId;
    }

    /**
     * Records the launch and stores the wording that was actually opened.
     * The originating record's own copy (for example
     * `fee_payments.whatsapp_message_snapshot`) keeps the system-generated
     * text, so the original receipt data is never lost — MEP.md 5.11.1.
     */
    public function markOpened(string $message = ''): void
    {
        $notification = $this->notification();

        if (! $notification) {
            return;
        }

        $this->authorize('sendNotifications', $this->organisation());

        $notification->forceFill([
            'status' => NotificationStatus::Opened,
            'opened_by' => $this->currentMembership()->id,
            'opened_at' => now(),
            ...(trim($message) === '' ? [] : ['message_snapshot' => $message]),
        ])->save();
    }

    public function skip(): void
    {
        $notification = $this->notification();

        if (! $notification) {
            return;
        }

        $this->authorize('sendNotifications', $this->organisation());

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

        return view('livewire.notifications.action-panel', [
            'notification' => $notification,
            'message' => $notification->message_snapshot ?? '',
            // Bare international digits, or null when unusable. The client
            // builds the wa.me link from this plus the live message text.
            'recipientDigits' => $notification === null
                ? null
                : PhoneNumber::normalise($notification->recipient_phone, $organisation->defaultCountry()),
            'displayPhone' => PhoneNumber::forDisplay($notification?->recipient_phone, $organisation->defaultCountry()),
        ]);
    }
}
