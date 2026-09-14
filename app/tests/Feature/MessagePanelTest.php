<?php

declare(strict_types=1);

use App\Enums\NotificationStatus;
use App\Enums\SubscriptionStatus;
use App\Livewire\Members\Show;
use App\Livewire\Notifications\ActionPanel;
use App\Livewire\Notifications\Index as MessageIndex;
use App\Livewire\Staff\Index as StaffIndex;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use Livewire\Livewire;

/**
 * The panel shown after an action and the panel shown inside the message list
 * are the same component, and every button on it has to do the same thing in
 * both places — except closing, which means "move on" after an action and
 * "never send this" in the list.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();
    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);

    $this->notification = WhatsappActionNotification::factory()->create([
        'organisation_id' => $this->organisation->id,
        'recipient_name' => 'Alex Morgan',
        'recipient_phone' => '919876543210',
        'message_snapshot' => 'The original wording.',
        'status' => NotificationStatus::Ready,
        'created_by' => $this->admin->id,
    ]);
});

it('persists an edit so the message list shows the same wording', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id])
        ->call('saveMessage', 'Wording the operator actually wants.');

    expect($this->notification->fresh()?->message_snapshot)
        ->toBe('Wording the operator actually wants.');

    // The list reads the same row, so it offers the edited wording too.
    Livewire::test(MessageIndex::class)->assertViewHas(
        'messages',
        fn ($messages): bool => $messages->first()?->message_snapshot === 'Wording the operator actually wants.',
    );
});

it('tells the surrounding list to re-render after an edit', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id])
        ->call('saveMessage', 'Edited.')
        ->assertDispatched('notification-updated');
});

it('refuses to rewrite the wording of a message already sent', function (): void {
    $this->notification->forceFill([
        'status' => NotificationStatus::Opened,
        'opened_at' => now(),
    ])->save();

    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id])
        ->call('saveMessage', 'Trying to change history.');

    // The snapshot is the record of what went out.
    expect($this->notification->fresh()?->message_snapshot)->toBe('The original wording.');
});

it('marks the message as sent when WhatsApp is opened, keeping the final wording', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id])
        ->call('markOpened', 'The wording as sent.');

    $fresh = $this->notification->fresh();

    expect($fresh?->status)->toBe(NotificationStatus::Opened)
        ->and($fresh?->status->label())->toBe('Sent')
        ->and($fresh?->message_snapshot)->toBe('The wording as sent.')
        ->and($fresh?->opened_by)->toBe($this->admin->id)
        ->and($fresh?->opened_at)->not->toBeNull();
});

it('closes the after-action panel without taking the message off the list', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id, 'context' => 'event'])
        ->assertSee('Close')
        ->call('dismiss')
        ->assertSet('notificationId', null);

    // Still waiting to be sent — closing was just moving on.
    expect($this->notification->fresh()?->status)->toBe(NotificationStatus::Ready);

    Livewire::test(MessageIndex::class)->assertViewHas(
        'messages',
        fn ($messages): bool => $messages->count() === 1,
    );
});

it('skipping from the list takes the message off it', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id, 'context' => 'queue'])
        ->assertSee('Skip')
        ->call('skip');

    expect($this->notification->fresh()?->status)->toBe(NotificationStatus::Skipped);

    Livewire::test(MessageIndex::class)->assertViewHas(
        'messages',
        fn ($messages): bool => $messages->isEmpty(),
    );
});

it('still finds a skipped message when the filter asks for it', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id, 'context' => 'queue'])
        ->call('skip');

    // Off the outstanding list, but never lost: the decision is on record.
    Livewire::test(MessageIndex::class)
        ->assertViewHas('messages', fn ($messages): bool => $messages->isEmpty())
        ->set('status', NotificationStatus::Skipped->value)
        ->assertViewHas('messages', fn ($messages): bool => $messages->count() === 1);
});

it('offers all four actions on the panel', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id])
        ->assertSee('Open WhatsApp')
        ->assertSee('Copy message')
        ->assertSee('Edit message')
        ->assertSee('Close');
});

it('is closed to staff without the messaging permission', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
        'permissions' => ['members.view' => true],
    ]);

    $this->actingAs($user);

    Livewire::test(ActionPanel::class, ['notificationId' => $this->notification->id])
        ->call('saveMessage', 'Not mine to edit.')
        ->assertForbidden();

    expect($this->notification->fresh()?->message_snapshot)->toBe('The original wording.');
});

it('shows the panel after a plan is paused, not just after it is created', function (): void {
    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $plan = Plan::factory()->create(['organisation_id' => $this->organisation->id]);
    $member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $club->id,
        'phone' => '919876543211',
    ]);
    $subscription = MemberSubscription::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $member->id,
        'club_id' => $club->id,
        'plan_id' => $plan->id,
        'status' => SubscriptionStatus::Active,
    ]);

    // The action composes a message; before this it was created and then never
    // put in front of anyone.
    Livewire::test(Show::class, ['member' => $member])
        ->call('changePlanStatus', $subscription->id, 'paused')
        ->assertSet('notificationId', fn (?int $id): bool => $id !== null);
});

it('shows the block instead of a flash notice when a reset link is issued', function (): void {
    $staff = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => User::factory()->create(['name' => 'KD'])->id,
    ]);

    // The panel is mounted on the page with no message; a Livewire action can
    // only reach it by dispatching, which is what was missing.
    Livewire::test(StaffIndex::class)
        ->call('sendResetLink', $staff->id)
        ->assertDispatched('notification-created')
        ->assertSessionHasNoErrors();

    expect(session('status'))->toBeNull();
});

it('mounts the panel on the staff page even with nothing to show', function (): void {
    Livewire::test(ActionPanel::class, ['notificationId' => null])
        ->assertOk()
        ->assertDontSee('Open WhatsApp');
});

it('ships a real Alpine scope, not an uncompiled Blade directive', function (): void {
    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'panel.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    $html = (string) $this->get('http://panel.test/messages')->assertOk()->getContent();

    // Blade leaves @js() untouched inside an x-component tag's attribute, so
    // putting the scope there shipped the literal directive to the browser.
    // Alpine then failed to build the scope and every button on the card died
    // with "copied is not defined".
    expect($html)->not->toContain('@js(')
        ->and($html)->toContain("digits: '919876543210'")
        ->and($html)->toContain("message: 'The original wording.'");
});

it('never calls the clipboard API directly, which is absent on plain HTTP', function (): void {
    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'clip.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    $html = (string) $this->get('http://clip.test/messages')->assertOk()->getContent();

    // navigator.clipboard is undefined outside a secure context, so reading
    // .writeText off it threw and the button did nothing. Everything goes
    // through the helper, which falls back to execCommand.
    expect($html)->not->toContain('navigator.clipboard')
        ->and($html)->toContain('window.copyToClipboard');
});
