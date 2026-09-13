<?php

declare(strict_types=1);

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\NotificationStatus;
use App\Models\Club;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\WhatsappActionNotification;

/**
 * The notification rules from MEP.md 5.14 and 6.5/6.8: idempotent creation,
 * organisation-level opt-outs, and messages that never leak secrets.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();
    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id]);
    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'phone' => '9876543210',
    ]);
});

function createNotification(NotificationActionType $type, string $operationId, array $context = []): ?WhatsappActionNotification
{
    return app(CreateActionNotification::class)->handle(
        organisation: test()->organisation,
        type: $type,
        recipientType: NotificationRecipientType::Member,
        recipientId: test()->member->id,
        recipientName: test()->member->name,
        recipientPhone: test()->member->phone,
        entityType: NotificationEntityType::Member,
        entityId: test()->member->id,
        actor: test()->admin,
        operationId: $operationId,
        context: $context,
    );
}

it('creates only one notification for a repeated operation', function (): void {
    $first = createNotification(NotificationActionType::MemberCreated, 'member.create.1');
    $second = createNotification(NotificationActionType::MemberCreated, 'member.create.1');

    expect($first->id)->toBe($second->id)
        ->and(WhatsappActionNotification::query()->count())->toBe(1);
});

it('treats a different operation as a separate notification', function (): void {
    createNotification(NotificationActionType::MemberCreated, 'member.create.1');
    createNotification(NotificationActionType::MemberProfileUpdated, 'member.update.1');

    expect(WhatsappActionNotification::query()->count())->toBe(2);
});

it('respects the organisation switching notifications off entirely', function (): void {
    $this->organisation->update(['notification_settings' => ['enabled' => false]]);

    expect(createNotification(NotificationActionType::MemberCreated, 'member.create.1'))->toBeNull()
        ->and(WhatsappActionNotification::query()->count())->toBe(0);
});

it('keeps attendance notifications off unless explicitly enabled', function (): void {
    // High message volume makes these opt-in (MEP.md 5.14).
    expect(createNotification(NotificationActionType::MemberAttendanceMarked, 'att.1'))->toBeNull();

    $this->organisation->update([
        'notification_settings' => ['actions' => [NotificationActionType::MemberAttendanceMarked->value => true]],
    ]);

    expect(createNotification(NotificationActionType::MemberAttendanceMarked, 'att.2'))->not->toBeNull();
});

it('records the template version alongside the rendered snapshot', function (): void {
    $notification = createNotification(NotificationActionType::MemberCreated, 'member.create.1', [
        'memberId' => 'MEM-7',
        'clubName' => $this->club->name,
    ]);

    expect($notification->message_template_version)->toBe('v1')
        ->and($notification->message_snapshot)->toContain($this->member->name)
        ->and($notification->message_snapshot)->toContain('MEM-7')
        ->and($notification->message_snapshot)->toContain($this->club->name)
        ->and($notification->status)->toBe(NotificationStatus::Ready);
});

it('omits template lines whose value was never supplied', function (): void {
    // No clubName in context, so the "Club:" line must not render half-empty.
    $notification = createNotification(NotificationActionType::MemberCreated, 'member.create.1', [
        'memberId' => 'MEM-7',
    ]);

    expect($notification->message_snapshot)->not->toContain('Club: —')
        ->and($notification->message_snapshot)->not->toContain(': —');
});

it('never includes a password or invitation token in an invitation', function (): void {
    $staff = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id]);

    $notification = app(CreateActionNotification::class)->handle(
        organisation: $this->organisation,
        type: NotificationActionType::UserInvited,
        recipientType: NotificationRecipientType::User,
        recipientId: $staff->id,
        recipientName: 'Casey Trainer',
        recipientPhone: '9876500000',
        entityType: NotificationEntityType::User,
        entityId: $staff->id,
        actor: $this->admin,
        operationId: 'user.invite.'.$staff->id,
        context: ['roleLabel' => 'Staff', 'clubName' => $this->club->name, 'signInUrl' => 'https://gym.test'],
    );

    $message = strtolower((string) $notification->message_snapshot);

    expect($message)->not->toContain('password')
        ->and($message)->not->toContain('token')
        ->and($message)->toContain('casey trainer')
        ->and($notification->message_snapshot)->toContain('https://gym.test');
});
