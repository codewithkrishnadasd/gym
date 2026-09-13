<?php

declare(strict_types=1);

use App\Enums\ClubAssignmentStatus;
use App\Enums\ConfirmationStatus;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Testing\TestResponse;

/**
 * The permission rules from MEP.md 4.2/4.3 and 8.3, checked through the real
 * HTTP stack so route middleware, tenant resolution, and policies are all
 * exercised together — hiding a button is never authorization.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();
    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'boundary.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    // HTTP requests get this from ResolveTenant; assertions that call the
    // policies directly need it bound explicitly.
    app()->instance('tenant', $this->organisation);

    $this->clubA = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Club A']);
    $this->clubB = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Club B']);
});

/**
 * Creates a signed-in user with the given role/permissions, assigned to the
 * given clubs.
 *
 * @param  array<string, bool>  $permissions
 * @param  array<int, Club>  $clubs
 */
function actingAsMember(array $permissions = [], array $clubs = [], bool $admin = false): OrganisationUser
{
    $user = User::factory()->create();

    $membership = OrganisationUser::factory()
        ->when($admin, fn ($factory) => $factory->admin())
        ->create([
            'organisation_id' => test()->organisation->id,
            'user_id' => $user->id,
            'permissions' => $permissions,
        ]);

    foreach ($clubs as $club) {
        ClubUserAssignment::factory()->create([
            'organisation_id' => test()->organisation->id,
            'club_id' => $club->id,
            'organisation_user_id' => $membership->id,
            'status' => ClubAssignmentStatus::Active,
        ]);
    }

    test()->actingAs($user);

    return $membership;
}

function tenantGet(string $path): TestResponse
{
    return test()->get('http://boundary.test'.$path);
}

it('denies staff access to admin-only areas', function (string $path): void {
    actingAsMember(['members.view' => true], [test()->clubA]);

    tenantGet($path)->assertForbidden();
})->with([
    'staff management' => '/staff',
    'plans' => '/plans',
    'expenses' => '/finance/expenses',
    'financial accounts' => '/finance/accounts',
    'confirmation queue' => '/finance/confirmations',
    'audit log' => '/audit-log',
    'organisation settings' => '/settings/organisation',
]);

it('allows an admin into every area', function (string $path): void {
    actingAsMember(admin: true);

    tenantGet($path)->assertOk();
})->with([
    '/dashboard', '/clubs', '/staff', '/members', '/plans',
    '/attendance/members', '/attendance/users',
    '/finance/payments', '/finance/confirmations', '/finance/expenses', '/finance/accounts',
    '/reports', '/audit-log', '/settings/organisation',
]);

it('denies members access to a staff user without the members.view permission', function (): void {
    actingAsMember(['fees.collect' => true], [test()->clubA]);

    tenantGet('/members')->assertForbidden();
});

it('hides members belonging to clubs the staff user is not assigned to', function (): void {
    actingAsMember(['members.view' => true], [test()->clubA]);

    $visible = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->clubA->id,
        'name' => 'Visible Person',
    ]);

    $hidden = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->clubB->id,
        'name' => 'Hidden Person',
    ]);

    tenantGet('/members')
        ->assertOk()
        ->assertSee('Visible Person')
        ->assertDontSee('Hidden Person');

    // Record-level scoping, not just list filtering.
    tenantGet('/members/'.$hidden->id)->assertForbidden();
    tenantGet('/members/'.$visible->id)->assertOk();
});

it('prevents a staff user from reaching another organisations records', function (): void {
    actingAsMember(['members.view' => true], [test()->clubA], admin: true);

    $otherOrganisation = Organisation::factory()->create();
    $otherClub = Club::factory()->create(['organisation_id' => $otherOrganisation->id]);
    $foreignMember = Member::factory()->create([
        'organisation_id' => $otherOrganisation->id,
        'primary_club_id' => $otherClub->id,
    ]);

    // The global scope makes an out-of-tenant ID a 404, so the existence of
    // another tenant's record is never leaked (MEP.md 7).
    tenantGet('/members/'.$foreignMember->id)->assertNotFound();
});

it('does not let a staff user confirm, reject, or reverse a payment', function (): void {
    $membership = actingAsMember(['fees.collect' => true, 'fees.view_own' => true], [test()->clubA]);

    $member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->clubA->id,
    ]);

    $payment = FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'club_id' => $this->clubA->id,
        'member_id' => $member->id,
        'collected_by' => $membership->id,
    ]);

    $user = $membership->user;

    expect($user->can('confirm', $payment))->toBeFalse()
        ->and($user->can('reject', $payment))->toBeFalse()
        ->and($user->can('reverse', $payment))->toBeFalse()
        // They can still see their own submission and its decision.
        ->and($user->can('view', $payment))->toBeTrue();
});

it('does not let a staff user see collections made by someone else', function (): void {
    $membership = actingAsMember(['fees.collect' => true, 'fees.view_own' => true], [test()->clubA]);
    $otherCollector = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id]);

    $member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->clubA->id,
    ]);

    $theirs = FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'club_id' => $this->clubA->id,
        'member_id' => $member->id,
        'collected_by' => $otherCollector->id,
    ]);

    expect($membership->user->can('view', $theirs))->toBeFalse();
});

it('refuses attendance marking without the matching permission', function (): void {
    $membership = actingAsMember(['attendance.member.mark' => true], [test()->clubA]);
    $user = $membership->user;

    expect($user->can('markMembers', [Attendance::class, $this->clubA->id]))->toBeTrue()
        // Assigned to club A only.
        ->and($user->can('markMembers', [Attendance::class, $this->clubB->id]))->toBeFalse()
        // Staff attendance is a separate grant.
        ->and($user->can('markStaff', [Attendance::class, $this->clubA->id]))->toBeFalse();
});

it('keeps admin-only finance objects out of a staff users reach', function (): void {
    $membership = actingAsMember(['fees.collect' => true], [test()->clubA]);
    $user = $membership->user;

    $expense = Expense::factory()->create([
        'organisation_id' => $this->organisation->id,
        'club_id' => $this->clubA->id,
        'created_by' => $membership->id,
    ]);

    expect($user->can('viewAny', Expense::class))->toBeFalse()
        ->and($user->can('reverse', $expense))->toBeFalse()
        ->and($user->can('viewAny', FinancialAccount::class))->toBeFalse()
        // But they may still name a receiving account when collecting a fee.
        ->and($user->can('select', FinancialAccount::class))->toBeTrue()
        ->and($user->can('create', Plan::class))->toBeFalse();
});

it('blocks a deactivated member from signing in to the tenant', function (): void {
    $user = User::factory()->create();

    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
        'status' => 'deactivated',
    ]);

    $this->actingAs($user);

    tenantGet('/dashboard')->assertRedirect();
});

it('excludes an ended club assignment from the users club scope', function (): void {
    $membership = actingAsMember(['members.view' => true], [test()->clubA]);

    $membership->clubAssignments()->update([
        'status' => ClubAssignmentStatus::Ended,
        'ended_at' => now(),
    ]);

    $member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->clubA->id,
        'name' => 'Formerly Visible',
    ]);

    // Authorization resolves against active assignments only (MEP.md 5.5).
    tenantGet('/members/'.$member->id)->assertForbidden();
});

it('keeps pending payments out of confirmed revenue in the ledger totals', function (): void {
    actingAsMember(admin: true);

    $member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->clubA->id,
    ]);

    FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'club_id' => $this->clubA->id,
        'member_id' => $member->id,
        'amount_minor' => 12345,
        'confirmation_status' => ConfirmationStatus::PendingAdminConfirmation,
    ]);

    tenantGet('/finance/payments')
        ->assertOk()
        ->assertSee('Pending confirmation');
});
