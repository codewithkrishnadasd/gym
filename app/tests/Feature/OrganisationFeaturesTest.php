<?php

declare(strict_types=1);

use App\Enums\Feature;
use App\Enums\PlanStatus;
use App\Livewire\Attendance\Roster;
use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Livewire\Members\Form as MemberForm;
use App\Livewire\Members\Show as MemberShow;
use App\Models\Club;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\PlatformAdmin;
use App\Models\User;
use App\Support\Navigation;
use Livewire\Livewire;

/**
 * A platform admin picks the modules an organisation gets. What is not
 * picked is absent — menu, routes, data on other screens — and everything
 * that is picked works without the rest.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'features.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create(['name' => 'Owner Olu']);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);
});

function enableOnly(array $features): void
{
    test()->organisation->update(['features' => array_map(fn (Feature $feature): string => $feature->value, $features)]);
    test()->organisation->refresh();
    app()->instance('tenant', test()->organisation);
}

it('treats an organisation with no stored list as having every module', function (): void {
    expect($this->organisation->features)->toBeNull()
        ->and($this->organisation->enabledFeatures())->toBe(Feature::keys())
        ->and($this->organisation->hasFeature(Feature::Payments))->toBeTrue();
});

it('closes the list over what each module needs', function (): void {
    expect(Feature::expand(['payments']))->toBe(['payments', 'accounts'])
        ->and(Feature::expand(['expenses']))->toBe(['expenses', 'accounts'])
        ->and(Feature::expand(['billing', 'bogus']))->toBe(['billing'])
        ->and(Feature::expand([]))->toBe([]);

    enableOnly([Feature::Plans]);

    expect($this->organisation->hasFeature(Feature::Members))->toBeTrue()
        ->and($this->organisation->hasFeature(Feature::Payments))->toBeFalse();
});

it('lets the platform admin choose the modules and stores the closed set', function (): void {
    $response = $this->actingAs(PlatformAdmin::factory()->create(), 'platform')
        ->put('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id, [
            'name' => $this->organisation->name,
            'slug' => $this->organisation->slug,
            'status' => 'active',
            'timezone' => 'UTC',
            'currency_code' => 'INR',
            'locale' => 'en',
            'terminology_member_singular' => 'Member',
            'terminology_member_plural' => 'Members',
            'terminology_user_singular' => 'Staff',
            'terminology_user_plural' => 'Staff',
            'terminology_club_singular' => 'Club',
            'terminology_club_plural' => 'Clubs',
            'features' => ['payments', 'tasks'],
        ]);

    $response->assertRedirect();

    expect($this->organisation->fresh()->features)->toBe(['payments', 'accounts', 'tasks']);

    // The edit page lists every module with a checkbox.
    $this->actingAs(PlatformAdmin::factory()->create(), 'platform')
        ->get('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id.'/edit')
        ->assertOk()
        ->assertSee('Features')
        ->assertSee('name="features[]" value="expenses"', false)
        ->assertDontSee('type="checkbox" name="features[]"', false)
        ->assertSee('Fee collection')
        ->assertSee('Needs Accounts')
        ->assertSee('Needs Members');
});

it('rejects an unknown module key', function (): void {
    $this->actingAs(PlatformAdmin::factory()->create(), 'platform')
        ->from('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id.'/edit')
        ->put('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id, [
            'name' => $this->organisation->name,
            'slug' => $this->organisation->slug,
            'status' => 'active',
            'timezone' => 'UTC',
            'currency_code' => 'INR',
            'locale' => 'en',
            'terminology_member_singular' => 'Member',
            'terminology_member_plural' => 'Members',
            'terminology_user_singular' => 'Staff',
            'terminology_user_plural' => 'Staff',
            'terminology_club_singular' => 'Club',
            'terminology_club_plural' => 'Clubs',
            'features' => ['payroll'],
        ])
        ->assertSessionHasErrors('features.0');
});

it('hides switched-off modules from the navigation', function (): void {
    enableOnly([Feature::Members, Feature::Tasks]);

    $labels = collect(Navigation::forTenant($this->organisation, $this->admin))
        ->flatMap(fn (array $section) => collect($section['items'])->pluck('label'))
        ->all();

    expect($labels)->toContain('Dashboard', 'Members', 'Tasks', 'Audit log', 'Settings')
        ->not->toContain('Payments', 'Invoices', 'Expenses', 'Accounts', 'Plans', 'Attendance', 'Clubs', 'Staff', 'Messages', 'Reports', 'Confirmations');
});

it('answers 404 on the routes of a switched-off module and denies through the policy', function (): void {
    enableOnly([Feature::Members]);

    $this->get('http://features.test/finance/payments')->assertNotFound();
    $this->get('http://features.test/finance/payments/create')->assertNotFound();
    $this->get('http://features.test/finance/expenses')->assertNotFound();
    $this->get('http://features.test/plans')->assertNotFound();
    $this->get('http://features.test/clubs')->assertNotFound();
    $this->get('http://features.test/staff')->assertNotFound();
    $this->get('http://features.test/attendance/members')->assertNotFound();
    $this->get('http://features.test/tasks')->assertNotFound();
    $this->get('http://features.test/messages')->assertNotFound();
    $this->get('http://features.test/reports')->assertNotFound();
    $this->get('http://features.test/billing')->assertNotFound();

    $this->get('http://features.test/members')->assertOk();
    $this->get('http://features.test/dashboard')->assertOk();

    expect($this->admin->user->can('create', FeePayment::class))->toBeFalse()
        ->and($this->admin->user->can('viewAny', Plan::class))->toBeFalse()
        ->and($this->admin->user->can('viewAny', Member::class))->toBeTrue()
        ->and($this->admin->user->can('viewReports', $this->organisation))->toBeFalse();
});

it('shows a member page with only the tabs of enabled modules', function (): void {
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Tab Tara', 'primary_club_id' => null]);

    enableOnly([Feature::Members]);

    $this->get('http://features.test/members/'.$member->id)
        ->assertOk()
        ->assertSee('Tab Tara')
        ->assertDontSee('tab=plans')
        ->assertDontSee('tab=payments')
        ->assertDontSee('tab=attendance')
        ->assertDontSee('tab=billing')
        ->assertDontSee('tab=documents')
        ->assertDontSee('Collect fee')
        ->assertDontSee('Current plan');

    // A stale link to a hidden tab falls back to the overview.
    $this->get('http://features.test/members/'.$member->id.'?tab=payments')->assertOk()->assertSee('Details');

    enableOnly([Feature::Members, Feature::Plans, Feature::Payments]);

    $this->get('http://features.test/members/'.$member->id)
        ->assertOk()
        ->assertSee('tab=plans')
        ->assertSee('tab=payments')
        ->assertSee('Collect fee')
        ->assertDontSee('tab=attendance');
});

it('runs members, plans, and fees without the Clubs module', function (): void {
    enableOnly([Feature::Members, Feature::Plans, Feature::Payments]);

    $plan = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Monthly', 'price_minor' => 150000, 'duration_days' => 30, 'status' => PlanStatus::Active]);
    $account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);

    // The member form neither shows nor requires a club.
    $this->get('http://features.test/members/create')->assertOk()->assertDontSee('name="primaryClubId"', false);

    Livewire::test(MemberForm::class)
        ->set('name', 'Clubless Cara')
        ->set('phone', '9876501234')
        ->set('planId', $plan->id)
        ->call('save')
        ->assertHasNoErrors();

    $member = Member::query()->where('name', 'Clubless Cara')->firstOrFail();
    expect($member->primary_club_id)->toBeNull()
        ->and($member->admission_fee_minor)->toBe(0);

    $subscription = MemberSubscription::query()->where('member_id', $member->id)->firstOrFail();
    expect($subscription->club_id)->toBeNull();

    // Admins see the club-less member everywhere a list used to filter by club.
    $this->get('http://features.test/members')->assertOk()->assertSee('Clubless Cara');
    $this->get('http://features.test/dashboard')->assertOk()->assertSee('Active members');

    // A fee is collected against the plan with no club on the payment.
    Livewire::test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->set('target', 'plan:'.$subscription->id)
        ->set('amount', '1500')
        ->set('financialAccountId', $account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    $payment = FeePayment::query()->latest('id')->firstOrFail();
    expect($payment->club_id)->toBeNull()
        ->and($payment->amount_minor)->toBe(150000);

    $this->get('http://features.test/finance/payments')->assertOk()->assertSee('Clubless Cara');
    $this->get('http://features.test/finance/payments/'.$payment->id)->assertOk()->assertSee('Clubless Cara');
    $this->get('http://features.test/finance/payments/'.$payment->id.'/receipt')->assertOk();
});

it('saves a new member when clubs exist but the Clubs module is off', function (): void {
    // The one club would otherwise be preselected and then fail validation on
    // a field the form no longer shows — the member silently never saved.
    Club::factory()->create(['organisation_id' => $this->organisation->id, 'admission_fee_minor' => 50000]);

    enableOnly([Feature::Members]);

    Livewire::test(MemberForm::class)
        ->assertSet('primaryClubId', null)
        ->set('name', 'Lone Lee')
        ->set('phone', '9876512345')
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $member = Member::query()->where('name', 'Lone Lee')->firstOrFail();
    expect($member->primary_club_id)->toBeNull()
        ->and($member->admission_fee_minor)->toBe(0);
});

it('marks member attendance organisation-wide without the Clubs module', function (): void {
    enableOnly([Feature::Members, Feature::Attendance]);

    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Present Pia', 'primary_club_id' => null]);

    $this->get('http://features.test/attendance/members')->assertOk()->assertSee('Present Pia')->assertDontSee('Staff');

    Livewire::test(Roster::class, ['subject' => 'members'])
        ->call('mark', $member->id, 'present')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('attendances', [
        'organisation_id' => $this->organisation->id,
        'subject_type' => 'member',
        'subject_id' => $member->id,
        'club_id' => null,
    ]);

    // Staff attendance needs the Staff module.
    $this->get('http://features.test/attendance/users')->assertForbidden();
});

it('offers a payment only what the enabled modules can pay for', function (): void {
    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $club->id]);
    $plan = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'price_minor' => 100000, 'duration_days' => 30, 'status' => PlanStatus::Active]);
    MemberSubscription::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $member->id,
        'club_id' => $club->id,
        'plan_id' => $plan->id,
        'amount_due_minor' => 100000,
    ]);

    enableOnly([Feature::Payments, Feature::Clubs]);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->assertDontSee('Plan:');

    enableOnly([Feature::Payments, Feature::Clubs, Feature::Plans]);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->assertSee('Plan:');
});

it('composes no WhatsApp message while messaging is off, and keeps the member action working', function (): void {
    enableOnly([Feature::Members]);

    Livewire::test(MemberForm::class)
        ->set('name', 'Quiet Quinn')
        ->set('phone', '9876509876')
        ->call('save')
        ->assertHasNoErrors();

    expect(Member::query()->where('name', 'Quiet Quinn')->exists())->toBeTrue();
    $this->assertDatabaseCount('whatsapp_action_notifications', 0);
});

it('offers staff only the permissions of enabled modules', function (): void {
    enableOnly([Feature::Members, Feature::Staff]);

    $this->get('http://features.test/staff/create')
        ->assertOk()
        ->assertSee('View members')
        ->assertDontSee('Collect fees')
        ->assertDontSee('View invoices')
        ->assertDontSee('Mark member attendance')
        ->assertDontSee('Assigned clubs');
});

it('shows only the settings tabs and prefixes of enabled modules', function (): void {
    enableOnly([Feature::Members, Feature::Tasks]);

    $this->get('http://features.test/settings/organisation?tab=terminology')
        ->assertOk()
        ->assertSee('tab=tasks')
        ->assertDontSee('tab=billing')
        ->assertDontSee('tab=expenses')
        ->assertDontSee('tab=templates')
        ->assertSee('idPrefixes.member', false)
        ->assertDontSee('idPrefixes.invoice', false);

    // A hidden tab falls back to the profile.
    $this->get('http://features.test/settings/organisation?tab=billing')->assertOk()->assertDontSee('Price list');
});

it('keeps the dashboard to the enabled modules', function (): void {
    enableOnly([Feature::Members, Feature::Tasks]);

    $this->get('http://features.test/dashboard')
        ->assertOk()
        ->assertSee('Active members')
        ->assertDontSee('Revenue collected')
        ->assertDontSee('Pending confirmations')
        ->assertDontSee('Attendance rate')
        ->assertDontSee('Expiring plans')
        ->assertDontSee('Recent expenses')
        ->assertDontSee('comparison');
});

it('renders the member show page for a staff member in a club-less organisation', function (): void {
    enableOnly([Feature::Members, Feature::Plans]);

    $staffUser = User::factory()->create(['name' => 'Desk Dev']);
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $staffUser->id,
        'permissions' => ['members.view' => true],
    ]);
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Seen Sana', 'primary_club_id' => null]);

    // No club assignments exist, and none are needed: staff reach every member.
    $this->actingAs($staffUser)->get('http://features.test/members')->assertOk()->assertSee('Seen Sana');
    $this->actingAs($staffUser)->get('http://features.test/members/'.$member->id)->assertOk()->assertSee('Seen Sana');

    Livewire::actingAs($staffUser)->test(MemberShow::class, ['member' => $member])->assertOk();
});
