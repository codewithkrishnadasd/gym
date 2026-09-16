<?php

declare(strict_types=1);

use App\Enums\NotificationActionType;
use App\Livewire\Settings\OrganisationSettings;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * The Notifications settings tab decides which actions prepare a WhatsApp
 * message and whether it is previewed first. Saving it must work — and
 * what it saves must be what the message pipeline reads back.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'notify.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);
});

it('saves notification preferences and reads them back', function (): void {
    expect($this->organisation->notificationsEnabled(NotificationActionType::MemberCreated))->toBeTrue()
        ->and($this->organisation->notificationsEnabled(NotificationActionType::MemberAttendanceMarked))->toBeFalse();

    Livewire::test(OrganisationSettings::class, ['tab' => 'notifications'])
        ->set('enabledActions', [NotificationActionType::MemberAttendanceMarked->value, NotificationActionType::FeePaymentConfirmed->value])
        ->call('saveNotifications')
        ->assertHasNoErrors();

    $organisation = $this->organisation->fresh();

    expect($organisation->notificationsEnabled(NotificationActionType::MemberAttendanceMarked))->toBeTrue()
        ->and($organisation->notificationsEnabled(NotificationActionType::FeePaymentConfirmed))->toBeTrue()
        ->and($organisation->notificationsEnabled(NotificationActionType::MemberCreated))->toBeFalse()
        // A password link is a handover, never optional.
        ->and($organisation->notificationsEnabled(NotificationActionType::PasswordResetLink))->toBeTrue();

    // The page reloads with the saved choices ticked.
    Livewire::test(OrganisationSettings::class, ['tab' => 'notifications'])
        ->assertSet('enabledActions', fn (array $actions): bool => in_array('member_attendance_marked', $actions, true) && ! in_array('member_created', $actions, true));
});

it('switches every message off at once', function (): void {
    Livewire::test(OrganisationSettings::class, ['tab' => 'notifications'])
        ->set('notificationsEnabled', false)
        ->call('saveNotifications')
        ->assertHasNoErrors();

    expect($this->organisation->fresh()->notificationsEnabled(NotificationActionType::MemberCreated))->toBeFalse();
});

it('lists switches grouped by area, without the password link, and only for enabled modules', function (): void {
    $this->get('http://notify.test/settings/organisation?tab=notifications')
        ->assertOk()
        ->assertSee('Members')
        ->assertSee('Plans')
        ->assertSee('Payments and invoices')
        ->assertSee('Attendance')
        ->assertSee('Staff')
        ->assertSee('Payment confirmed')
        ->assertSee('Member attendance')
        // The handover has no switch; it is explained instead.
        ->assertDontSee('value="password_reset_link"', false)
        ->assertSee('Password links');

    $this->organisation->update(['features' => ['members', 'messaging']]);

    $this->get('http://notify.test/settings/organisation?tab=notifications')
        ->assertOk()
        ->assertSee('Member added')
        ->assertDontSee('Payment confirmed')
        ->assertDontSee('Plan started')
        ->assertDontSee('Staff invited')
        ->assertDontSee('value="member_club_transferred"', false)
        ->assertDontSee('Password links');
});

it('keeps the switch of a hidden module as it was when saving the rest', function (): void {
    $this->organisation->update([
        'notification_settings' => ['enabled' => true, 'actions' => ['fee_payment_confirmed' => false]],
        'features' => ['members', 'messaging'],
    ]);
    app()->instance('tenant', $this->organisation->fresh());

    Livewire::test(OrganisationSettings::class, ['tab' => 'notifications'])
        ->set('enabledActions', ['member_created'])
        ->call('saveNotifications')
        ->assertHasNoErrors();

    $organisation = $this->organisation->fresh();

    expect($organisation->notificationsEnabled(NotificationActionType::MemberCreated))->toBeTrue()
        ->and($organisation->notificationsEnabled(NotificationActionType::MemberProfileUpdated))->toBeFalse()
        // Payments is off, so its switch was not on screen and is left as stored.
        ->and(($organisation->notification_settings['actions'] ?? [])['fee_payment_confirmed'])->toBeFalse();
});

it('opens the template editor on a message the organisation actually sends', function (): void {
    $this->organisation->update(['features' => ['members', 'messaging']]);
    app()->instance('tenant', $this->organisation->fresh());

    Livewire::test(OrganisationSettings::class, ['tab' => 'templates'])
        ->assertSet('templateAction', 'member_created')
        ->assertDontSee('Payment confirmed');
});
