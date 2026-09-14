<?php

declare(strict_types=1);

use App\Enums\ClubStatus;
use App\Enums\FinancialAccountStatus;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\PlanStatus;
use App\Models\Club;
use App\Models\Domain;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;

/**
 * Removing a record takes it out of day-to-day lists without deleting it. The
 * rule only holds if every listing applies it: one screen that still shows
 * removed rows alongside live ones is the whole reason operators stop trusting
 * the lists.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'removed.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();

    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);

    $this->actingAs($user);

    $this->club = Club::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Live Club',
    ]);

    // One live and one removed row of every kind a listing can show.
    Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'name' => 'Live Person',
        'status' => MemberStatus::Active,
    ]);
    Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'name' => 'Removed Person',
        'status' => MemberStatus::Archived,
    ]);

    Club::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Removed Club',
        'status' => ClubStatus::Archived,
    ]);

    Plan::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Live Plan',
        'status' => PlanStatus::Active,
    ]);
    Plan::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Removed Plan',
        'status' => PlanStatus::Archived,
    ]);

    FinancialAccount::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Live Account',
        'status' => FinancialAccountStatus::Active,
    ]);
    FinancialAccount::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Removed Account',
        'status' => FinancialAccountStatus::Archived,
    ]);
});

it('keeps removed records out of the default listing but shows them when filtered', function (
    string $path,
    string $liveName,
    string $removedName,
): void {
    $this->get('http://removed.test'.$path)
        ->assertOk()
        ->assertSee($liveName)
        ->assertDontSee($removedName);

    $this->get('http://removed.test'.$path.'?status=archived')
        ->assertOk()
        ->assertSee($removedName)
        ->assertDontSee($liveName);
})->with([
    'members' => fn () => ['/members', 'Live Person', 'Removed Person'],
    'clubs' => fn () => ['/clubs', 'Live Club', 'Removed Club'],
    'plans' => fn () => ['/plans', 'Live Plan', 'Removed Plan'],
    'accounts' => fn () => ['/finance/accounts', 'Live Account', 'Removed Account'],
]);

it('calls the removed state "Removed" rather than "Archived"', function (): void {
    expect(MemberStatus::Archived->label())->toBe('Removed')
        ->and(ClubStatus::Archived->label())->toBe('Removed')
        ->and(PlanStatus::Archived->label())->toBe('Removed')
        ->and(FinancialAccountStatus::Archived->label())->toBe('Removed')
        // The stored value is untouched, so existing rows and audit history
        // still resolve.
        ->and(MemberStatus::Archived->value)->toBe('archived');
});

it('hides removed staff from the list until the filter asks for them', function (): void {
    $active = User::factory()->create(['name' => 'Working Here']);
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $active->id,
        'status' => MembershipStatus::Active,
    ]);

    $gone = User::factory()->create(['name' => 'Left Already']);
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $gone->id,
        'status' => MembershipStatus::Deactivated,
    ]);

    $this->get('http://removed.test/staff')
        ->assertOk()
        ->assertSee('Working Here')
        ->assertDontSee('Left Already');

    $this->get('http://removed.test/staff?status=deactivated')
        ->assertOk()
        ->assertSee('Left Already')
        ->assertDontSee('Working Here');
});

it('calls a deactivated staff membership "Removed"', function (): void {
    expect(MembershipStatus::Deactivated->label())->toBe('Removed')
        // The stored value is untouched, so audit history still resolves.
        ->and(MembershipStatus::Deactivated->value)->toBe('deactivated');
});
