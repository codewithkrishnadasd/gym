<?php

declare(strict_types=1);

namespace App\Livewire\Notifications;

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\Feature;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\CustomMessageTemplate;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\OrganisationUser;
use App\Support\WhatsApp\MessageComposer;
use App\Support\WhatsApp\RecipientContext;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

/**
 * The WhatsApp button on a person's page, as a menu of things to send: a
 * plain hello, the plan-expired nudge when it applies, every wording the
 * organisation wrote for itself, and a blank message to type. Choosing one
 * composes a notification for that person and hands it to the panel on the
 * page (ActionPanel), where it is previewed, edited, sent — and can be kept
 * as a template.
 */
class ComposeMenu extends Component
{
    use ResolvesMembership;

    public string $recipientType = 'member';

    public int $recipientId = 0;

    public function mount(string $recipientType, int $recipientId): void
    {
        $this->recipientType = $recipientType === 'user' ? 'user' : 'member';
        $this->recipientId = $recipientId;
    }

    public function compose(string $choice): void
    {
        $organisation = $this->organisation();
        $this->authorize('sendNotifications', $organisation);

        [$recipient, $context] = $this->recipient();

        if ($recipient === null) {
            return;
        }

        $type = NotificationActionType::CustomMessage;
        $version = 'custom:new';

        if ($choice === 'plan_expired' && $this->planLapsed()) {
            $type = NotificationActionType::MemberPlanExpired;
            $body = MessageComposer::render($type, $organisation, $context);
            $version = MessageComposer::versionFor($organisation, $type);
        } elseif (str_starts_with($choice, 'template:')) {
            $template = CustomMessageTemplate::query()
                ->where('audience', $this->recipientType)
                ->find((int) substr($choice, 9));

            if ($template === null) {
                return;
            }

            $body = MessageComposer::renderBody($template->body, $context, array_keys(MessageComposer::variablesFor($type)));
            $version = $template->versionLabel();
        } elseif ($choice === 'new') {
            $body = 'Hi '.$context['memberName'].',';
        } else {
            $body = 'Hi '.$context['memberName'].',';
            $version = 'custom:hi';
        }

        $isMember = $recipient instanceof Member;

        $notification = app(CreateActionNotification::class)->handle(
            organisation: $organisation,
            type: $type,
            recipientType: $isMember ? NotificationRecipientType::Member : NotificationRecipientType::User,
            recipientId: $recipient->id,
            recipientName: (string) $context['memberName'],
            recipientPhone: $isMember ? $recipient->phone : $recipient->user?->phone,
            entityType: $isMember ? NotificationEntityType::Member : NotificationEntityType::User,
            entityId: $recipient->id,
            actor: $this->currentMembership(),
            operationId: $type->value.'.'.$recipient->id.'.'.now()->getPreciseTimestamp(3),
            context: $context,
            body: $body,
            version: $version,
        );

        if ($notification === null) {
            return;
        }

        // "New message" opens straight into the editor: the whole point is
        // to type something.
        $this->dispatch('notification-created', notificationId: $notification->id, edit: $choice === 'new');
    }

    /**
     * @return array{0: Member|OrganisationUser|null, 1: array<string, string|null>}
     */
    private function recipient(): array
    {
        $organisation = $this->organisation();

        if ($this->recipientType === 'user') {
            $staff = OrganisationUser::query()->with('user')->find($this->recipientId);

            return [$staff, $staff ? RecipientContext::forStaff($staff, $organisation) : []];
        }

        $member = Member::query()->with('primaryClub')->find($this->recipientId);

        return [$member, $member ? RecipientContext::forMember($member, $organisation) : []];
    }

    /**
     * Whether the member's latest plan has ended — the one case the expired
     * nudge is for.
     */
    private function planLapsed(): bool
    {
        if ($this->recipientType !== 'member' || ! $this->organisation()->hasFeature(Feature::Plans)) {
            return false;
        }

        $latest = MemberSubscription::query()
            ->where('member_id', $this->recipientId)
            ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
            ->orderByDesc('end_date')
            ->first();

        return $latest !== null && $latest->end_date->lt(Carbon::today($this->organisation()->timezone));
    }

    /**
     * @return Collection<int, CustomMessageTemplate>
     */
    private function templates(): Collection
    {
        return CustomMessageTemplate::query()
            ->where('audience', $this->recipientType)
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.notifications.compose-menu', [
            'organisation' => $this->organisation(),
            'templates' => $this->templates(),
            'planLapsed' => $this->planLapsed(),
            'canManageTemplates' => auth()->user()?->can('manageSettings', $this->organisation()) ?? false,
        ]);
    }
}
