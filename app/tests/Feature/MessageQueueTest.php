<?php

declare(strict_types=1);

use App\Enums\NotificationActionType;
use App\Enums\NotificationStatus;
use App\Livewire\Notifications\ActionPanel;
use App\Livewire\Notifications\Index;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use Livewire\Livewire;

/**
 * The platform composes WhatsApp messages but never sends them — an operator
 * opens each deep link. A message missed at the moment it was generated is
 * otherwise never sent and invisible, so the queue is what makes the backlog
 * recoverable (MEP.md 5.14).
 *
 * Rendering is asserted over HTTP rather than through Livewire::test(): the
 * list is a stack of nested components, and the component test harness does
 * not render children.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'queue.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);
});

function queueMessage(NotificationStatus $status, string $name = 'Alex Morgan'): WhatsappActionNotification
{
    return WhatsappActionNotification::factory()->create([
        'organisation_id' => test()->organisation->id,
        'recipient_name' => $name,
        'recipient_phone' => '919876543210',
        'status' => $status,
        'action_type' => NotificationActionType::FeePaymentConfirmed,
        'created_by' => test()->admin->id,
    ]);
}

it('defaults to the work still to do, hiding sent and skipped', function (): void {
    queueMessage(NotificationStatus::Ready, 'Still Waiting');
    queueMessage(NotificationStatus::Opened, 'Already Sent');
    queueMessage(NotificationStatus::Skipped, 'Deliberately Skipped');
    queueMessage(NotificationStatus::Unavailable, 'No Number Person');

    $this->get('http://queue.test/messages')
        ->assertOk()
        ->assertSee('Still Waiting')
        // No usable number is still outstanding work — the message has to be
        // copied out by hand, so it stays on the list.
        ->assertSee('No Number Person')
        ->assertDontSee('Already Sent')
        ->assertDontSee('Deliberately Skipped');
});

it('finds sent and skipped messages when the filter asks for them', function (): void {
    queueMessage(NotificationStatus::Opened, 'Already Sent');
    queueMessage(NotificationStatus::Skipped, 'Deliberately Skipped');

    $this->get('http://queue.test/messages?status=opened')->assertOk()->assertSee('Already Sent');
    $this->get('http://queue.test/messages?status=skipped')->assertOk()->assertSee('Deliberately Skipped');
});

it('counts what is still waiting to be sent', function (): void {
    queueMessage(NotificationStatus::Ready);
    queueMessage(NotificationStatus::Ready);
    queueMessage(NotificationStatus::Opened);
    queueMessage(NotificationStatus::Unavailable);

    Livewire::test(Index::class)
        ->assertViewHas('readyCount', 2)
        ->assertViewHas('openedCount', 1)
        ->assertViewHas('unavailableCount', 1);
});

it('filters by status', function (): void {
    queueMessage(NotificationStatus::Ready, 'Waiting Person');
    queueMessage(NotificationStatus::Opened, 'Sent Person');

    $this->get('http://queue.test/messages?status=ready')
        ->assertOk()
        ->assertSee('Waiting Person')
        ->assertDontSee('Sent Person');
});

it('gives every message the same block, not a row that expands', function (): void {
    queueMessage(NotificationStatus::Ready, 'Alex Morgan');

    // The controls an operator gets after an action are the controls they get
    // here, so there is no second design to learn.
    $this->get('http://queue.test/messages')
        ->assertOk()
        ->assertSee('Notify Alex Morgan')
        ->assertSee('Open WhatsApp')
        ->assertSee('Copy message')
        ->assertSee('Edit message')
        ->assertSee('Skip');
});

it('skips a message without sending it', function (): void {
    $message = queueMessage(NotificationStatus::Ready);

    Livewire::test(ActionPanel::class, ['notificationId' => $message->id, 'context' => 'queue'])
        ->call('skip');

    expect($message->fresh()?->status)->toBe(NotificationStatus::Skipped);
});

it('will not re-skip a message that was already sent', function (): void {
    $message = queueMessage(NotificationStatus::Opened);

    Livewire::test(ActionPanel::class, ['notificationId' => $message->id, 'context' => 'queue'])
        ->call('skip');

    // Skipping an opened message would rewrite history: it was launched.
    expect($message->fresh()?->status)->toBe(NotificationStatus::Opened);
});

it('is closed to staff without the messaging permission', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
        'permissions' => ['members.view' => true],
    ]);

    $this->actingAs($user);

    $this->get('http://queue.test/messages')->assertForbidden();
});
