<?php

declare(strict_types=1);

use App\Livewire\Attendance\Roster;
use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Livewire\Finance\Payments\Show;
use App\Livewire\Notifications\ActionPanel;
use App\Livewire\Staff\Form;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use Livewire\Livewire;
use Tests\TestCase;

/**
 * After an action that composes a WhatsApp message, the preview is the next
 * thing the operator sees — for everyone allowed to send it, and nobody else.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'preview.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Paying Priya', 'phone' => '919876500001']);
    $this->account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);

    $this->adminUser = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $this->adminUser->id]);
});

/**
 * Switches the acting user and drops the membership the previous request's
 * middleware bound into the container, which would otherwise be read back
 * as the new user's.
 */
function actingAsFresh(User $user): TestCase
{
    app()->forgetInstance('membership');

    return test()->actingAs($user);
}

function staffWithPermissions(array $permissions): User
{
    $user = User::factory()->create();
    $staff = OrganisationUser::factory()->create([
        'organisation_id' => test()->organisation->id,
        'user_id' => $user->id,
        'permissions' => $permissions,
    ]);
    ClubUserAssignment::factory()->create(['organisation_id' => test()->organisation->id, 'organisation_user_id' => $staff->id, 'club_id' => test()->club->id, 'status' => 'active']);

    return $user;
}

it('shows the receipt preview at the top of the payment page straight after an admin collects a fee', function (): void {
    $this->actingAs($this->adminUser);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->set('amount', '500')
        ->set('financialAccountId', $this->account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $payment = FeePayment::query()->latest('id')->firstOrFail();
    expect(WhatsappActionNotification::query()->where('entity_id', $payment->id)->exists())->toBeTrue();

    $html = $this->get('http://preview.test/finance/payments/'.$payment->id)->assertOk()->assertSee('Notify Paying Priya')->getContent();

    // The panel precedes the payment details rather than trailing them.
    expect(strpos($html, 'Notify Paying Priya'))->toBeLessThan(strpos($html, 'Received into'));
});

it('shows the preview only to staff with the messaging permission', function (): void {
    $silent = staffWithPermissions(['fees.collect' => true, 'fees.view_own' => true]);
    $sender = staffWithPermissions(['fees.collect' => true, 'fees.view_own' => true, 'notifications.send' => true]);

    // Each collects a fee; a staff member only ever sees their own payments.
    $payments = [];

    foreach ([$silent, $sender] as $collector) {
        actingAsFresh($collector);
        Livewire::test(PaymentForm::class)
            ->call('selectMember', $this->member->id)
            ->set('amount', '500')
            ->set('financialAccountId', $this->account->id)
            ->call('save')
            ->assertHasNoErrors();
        $payments[] = FeePayment::query()->latest('id')->firstOrFail();
    }

    actingAsFresh($this->adminUser);

    foreach ($payments as $payment) {
        Livewire::test(Show::class, ['payment' => $payment])->call('confirm')->assertHasNoErrors();
    }

    actingAsFresh($silent)->get('http://preview.test/finance/payments/'.$payments[0]->id)->assertOk()->assertDontSee('Notify Paying Priya');
    actingAsFresh($sender)->get('http://preview.test/finance/payments/'.$payments[1]->id)->assertOk()->assertSee('Notify Paying Priya');

    // The panel itself refuses without the permission, wherever it is mounted.
    $notification = WhatsappActionNotification::query()->where('entity_id', $payments[0]->id)->firstOrFail();
    actingAsFresh($silent);
    Livewire::test(ActionPanel::class, ['notificationId' => $notification->id])->assertDontSee('Notify Paying Priya');
    actingAsFresh($sender);
    Livewire::test(ActionPanel::class, ['notificationId' => $notification->id])->assertSee('Notify Paying Priya');
});

it('composes no receipt until an admin confirms a staff collection, then shows it', function (): void {
    $collector = staffWithPermissions(['fees.collect' => true, 'fees.view_own' => true, 'notifications.send' => true]);
    $this->actingAs($collector);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->set('amount', '500')
        ->set('financialAccountId', $this->account->id)
        ->call('save')
        ->assertHasNoErrors();

    $payment = FeePayment::query()->latest('id')->firstOrFail();
    expect($payment->isConfirmed())->toBeFalse()
        ->and(WhatsappActionNotification::query()->where('entity_id', $payment->id)->exists())->toBeFalse();

    $this->get('http://preview.test/finance/payments/'.$payment->id)->assertOk()->assertDontSee('Notify Paying Priya');

    actingAsFresh($this->adminUser);
    Livewire::test(Show::class, ['payment' => $payment])
        ->call('confirm')
        ->assertHasNoErrors()
        ->assertDispatched('notification-created');

    $this->get('http://preview.test/finance/payments/'.$payment->id)->assertOk()->assertSee('Notify Paying Priya');
});

it('composes an attendance message when that message is switched on, and offers it on the roster', function (): void {
    $this->actingAs($this->adminUser);

    // Off by default: marking composes nothing.
    Livewire::test(Roster::class, ['subject' => 'members'])
        ->set('clubId', $this->club->id)
        ->call('mark', $this->member->id, 'present')
        ->assertNotDispatched('notification-created');
    expect(WhatsappActionNotification::query()->count())->toBe(0);

    $this->organisation->update(['notification_settings' => ['enabled' => true, 'actions' => ['member_attendance_marked' => true]]]);
    app()->instance('tenant', $this->organisation->fresh());

    Livewire::test(Roster::class, ['subject' => 'members'])
        ->set('clubId', $this->club->id)
        ->call('mark', $this->member->id, 'late')
        ->assertDispatched('notification-created')
        ->assertSet('notificationId', WhatsappActionNotification::query()->value('id'));

    $notification = WhatsappActionNotification::query()->firstOrFail();
    expect($notification->action_type->value)->toBe('member_attendance_marked')
        ->and($notification->message_snapshot)->toContain('Late');
});

it('composes a status message when a member is removed or restored', function (): void {
    $this->actingAs($this->adminUser);

    Livewire::test(App\Livewire\Members\Show::class, ['member' => $this->member])
        ->call('archive')
        ->assertDispatched('notification-created');

    expect(WhatsappActionNotification::query()->where('action_type', 'member_status_changed')->count())->toBe(1)
        ->and(WhatsappActionNotification::query()->latest('id')->value('message_snapshot'))->toContain('Removed');

    Livewire::test(App\Livewire\Members\Show::class, ['member' => $this->member->fresh()])
        ->call('restore')
        ->assertDispatched('notification-created');

    expect(WhatsappActionNotification::query()->where('action_type', 'member_status_changed')->count())->toBe(2);
});

it('tells a staff member when their permissions change', function (): void {
    $this->actingAs($this->adminUser);
    $staffUser = staffWithPermissions(['members.view' => true]);
    $staff = OrganisationUser::query()->where('user_id', $staffUser->id)->firstOrFail();

    Livewire::test(Form::class, ['organisationUser' => $staff])
        ->set('permissions', ['members.view', 'fees.collect'])
        ->call('save')
        ->assertHasNoErrors();

    expect(WhatsappActionNotification::query()->latest('id')->firstOrFail()->action_type->value)->toBe('user_permissions_changed');
});

it('prefills the member arriving from their page however many members there are', function (): void {
    $this->actingAs($this->adminUser);

    // Nine members that sort ahead of the one we want, so a lookup through the
    // capped search list would miss it.
    foreach (range(1, 9) as $i) {
        Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Aaron '.$i]);
    }

    $late = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Zara Zed']);

    Livewire::withQueryParams(['member' => $late->id])->test(PaymentForm::class)->assertSet('memberId', $late->id);
    Livewire::withQueryParams(['member' => $late->id])->test(App\Livewire\Billing\Form::class)->assertSet('memberId', $late->id);
    Livewire::withQueryParams(['member' => $late->id])->test(App\Livewire\Tasks\Form::class)->assertSet('memberId', $late->id);
});
