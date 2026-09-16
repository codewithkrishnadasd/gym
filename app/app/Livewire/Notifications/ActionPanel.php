<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Enums\NotificationRecipientType;
use App\Enums\NotificationStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\CustomMessageTemplate;
use App\Models\Member;
use App\Models\OrganisationUser;
use App\Models\WhatsappActionNotification;
use App\Support\PhoneNumber;
use App\Support\WhatsApp\RecipientContext;
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

    /**
     * 'event' — shown straight after an action, where dismissing must leave the
     * message in the queue to send later.
     * 'queue' — shown inside the message list, where skipping is a decision not
     * to send at all.
     */
    public string $context = 'event';

    public function mount(?int $notificationId = null, string $context = 'event'): void
    {
        $this->notificationId = $notificationId;
        $this->context = $context;
    }

    /** Opens the editor straight away — for a message being written from scratch. */
    public bool $startEditing = false;

    #[On('notification-created')]
    public function show(int $notificationId, bool $edit = false): void
    {
        $this->notificationId = $notificationId;
        $this->startEditing = $edit;
    }

    /**
     * Keeps the wording on screen as one of the organisation's own
     * templates. The person's details are swapped back to placeholders so
     * the template fits everyone; a template of the same name (for the same
     * audience) is updated rather than duplicated.
     */
    public function saveAsTemplate(string $name, string $message): void
    {
        $this->authorize('sendNotifications', $this->organisation());

        $notification = $this->notification();
        $name = trim($name);
        $message = trim($message);

        if (! $notification || $name === '' || $message === '') {
            return;
        }

        if (mb_strlen($name) > 80) {
            $name = mb_substr($name, 0, 80);
        }

        $organisation = $this->organisation();
        $isMember = $notification->recipient_type === NotificationRecipientType::Member;

        $values = ['memberName' => $notification->recipient_name];

        if ($isMember) {
            $member = Member::query()->with('primaryClub')->find($notification->recipient_id);
            $values = $member ? RecipientContext::forMember($member, $organisation) : $values;
        } else {
            $staff = OrganisationUser::query()->with('user')->find($notification->recipient_id);
            $values = $staff ? RecipientContext::forStaff($staff, $organisation) : $values;
        }

        $template = CustomMessageTemplate::query()->firstOrNew([
            'organisation_id' => $organisation->id,
            'audience' => $isMember ? NotificationRecipientType::Member->value : NotificationRecipientType::User->value,
            'name' => $name,
        ]);

        $template->fill([
            'body' => RecipientContext::templatize($message, $values),
            'created_by' => $template->created_by ?? $this->currentMembership()->id,
        ])->save();

        $this->dispatch('template-saved', name: $template->name);
    }

    /**
     * Persists an edit so the message list shows the same wording the operator
     * is looking at here.
     *
     * Without this the edit lived only in the browser and reached the database
     * on "Open WhatsApp" alone — so editing and then closing the panel silently
     * threw the change away, and the queue kept offering the original text.
     */
    public function saveMessage(string $message): void
    {
        $this->authorize('sendNotifications', $this->organisation());

        $notification = $this->notification();

        if (! $notification || trim($message) === '') {
            return;
        }

        // Once it has gone out, the snapshot is the record of what was sent.
        // Rewriting it afterwards would make the history a lie.
        if ($notification->status !== NotificationStatus::Ready) {
            return;
        }

        if ($notification->message_snapshot === $message) {
            return;
        }

        $notification->forceFill(['message_snapshot' => $message])->save();

        $this->dispatch('notification-updated');
    }

    /**
     * Records the launch and stores the wording that was actually opened.
     * The originating record's own copy (for example
     * `fee_payments.whatsapp_message_snapshot`) keeps the system-generated
     * text, so the original receipt data is never lost — MEP.md 5.11.1.
     */
    public function markOpened(string $message = ''): void
    {
        $this->authorize('sendNotifications', $this->organisation());

        $notification = $this->notification();

        if (! $notification) {
            return;
        }

        $notification->forceFill([
            'status' => NotificationStatus::Opened,
            'opened_by' => $this->currentMembership()->id,
            'opened_at' => now(),
            ...(trim($message) === '' ? [] : ['message_snapshot' => $message]),
        ])->save();

        $this->dispatch('notification-updated');
    }

    /**
     * Marks the message as deliberately not sent, which takes it off the
     * outstanding list.
     */
    public function skip(): void
    {
        $this->authorize('sendNotifications', $this->organisation());

        $notification = $this->notification();

        if (! $notification) {
            return;
        }

        // Skipping something already launched would rewrite what happened.
        if ($notification->status !== NotificationStatus::Ready) {
            return;
        }

        $notification->forceFill(['status' => NotificationStatus::Skipped])->save();

        $this->notificationId = null;

        $this->dispatch('notification-updated');
    }

    /**
     * Closes the panel without touching the notification. Used after an action,
     * where the operator is moving on rather than deciding never to send — the
     * message stays in the queue for them to pick up later.
     */
    public function dismiss(): void
    {
        $this->notificationId = null;
    }

    protected function notification(): ?WhatsappActionNotification
    {
        // Seeing a message is the same permission as sending it: the panel
        // draws nothing for anyone without it, wherever it is mounted.
        if (! (auth()->user()?->can('sendNotifications', $this->organisation()) ?? false)) {
            return null;
        }

        return $this->notificationId === null
            ? null
            : WhatsappActionNotification::query()
                ->with(['createdBy.user:id,name', 'openedBy.user:id,name'])
                ->find($this->notificationId);
    }

    public function render(): View
    {
        $notification = $this->notification();
        $organisation = $this->organisation();

        // The template a custom message came from, offered as the name to
        // save it back under.
        $sourceTemplate = $notification !== null && str_starts_with((string) $notification->message_template_version, 'custom:')
            ? CustomMessageTemplate::query()->find((int) substr((string) $notification->message_template_version, 7))
            : null;

        return view('livewire.notifications.action-panel', [
            'notification' => $notification,
            'sourceTemplateName' => $sourceTemplate !== null ? $sourceTemplate->name : '',
            'canSaveTemplate' => $notification !== null && $notification->recipient_id !== null,
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
