<?php

declare(strict_types=1);

use App\Enums\ClubAssignmentStatus;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;

/**
 * A count is never a dead end: clicking it opens the list it was counted
 * from, already filtered to exactly those records.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'counts.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->north = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    $this->south = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'South']);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->northMember = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->north->id, 'name' => 'Northern Nia']);
    $this->southMember = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->south->id, 'name' => 'Southern Sam']);
});

it('links club counts to the members and staff of that club', function (): void {
    $staff = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Trainer Tom'])->id]);
    ClubUserAssignment::factory()->create(['organisation_id' => $this->organisation->id, 'organisation_user_id' => $staff->id, 'club_id' => $this->north->id, 'status' => ClubAssignmentStatus::Active]);

    $this->get('http://counts.test/clubs')
        ->assertOk()
        ->assertSee('/members?club='.$this->north->id.'&amp;status=active', false)
        ->assertSee('/staff?club='.$this->north->id, false)
        ->assertSee('/attendance/members?clubId='.$this->north->id, false)
        ->assertSee('/finance/payments?club='.$this->north->id.'&amp;status=confirmed', false);

    // The destination is filtered to that club only.
    $this->get('http://counts.test/members?club='.$this->north->id.'&status=active')->assertOk()->assertSee('Northern Nia')->assertDontSee('Southern Sam');
    $this->get('http://counts.test/staff?club='.$this->north->id)->assertOk()->assertSee('Trainer Tom');
});

it('links a plan\'s active count to the members currently on it', function (): void {
    $quarterly = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Quarterly']);
    $monthly = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Monthly']);

    MemberSubscription::factory()->create(['organisation_id' => $this->organisation->id, 'member_id' => $this->northMember->id, 'club_id' => $this->north->id, 'plan_id' => $quarterly->id, 'status' => 'active', 'start_date' => now()->subDays(5), 'end_date' => now()->addDays(80)]);
    MemberSubscription::factory()->create(['organisation_id' => $this->organisation->id, 'member_id' => $this->southMember->id, 'club_id' => $this->south->id, 'plan_id' => $monthly->id, 'status' => 'active', 'start_date' => now()->subDays(5), 'end_date' => now()->addDays(20)]);

    $this->get('http://counts.test/plans')->assertOk()->assertSee('/members?planId='.$quarterly->id, false);

    $this->get('http://counts.test/members?planId='.$quarterly->id)
        ->assertOk()
        ->assertSee('Northern Nia')
        ->assertDontSee('Southern Sam')
        ->assertSee('On the Quarterly plan');
});

it('links the club page figures with the club and period applied', function (): void {
    $this->get('http://counts.test/clubs/'.$this->north->id.'?range=custom&from=2026-09-01&to=2026-09-15')
        ->assertOk()
        ->assertSee('/members?club='.$this->north->id.'&amp;status=active', false)
        ->assertSee('/finance/payments?status=confirmed&amp;club='.$this->north->id.'&amp;from=2026-09-01&amp;to=2026-09-15', false)
        ->assertSee('/finance/expenses?status=completed&amp;club='.$this->north->id, false)
        ->assertSee('/reports?tab=attendance&amp;range=custom&amp;club='.$this->north->id, false);
});

it('shows a zero as plain text rather than a link', function (): void {
    $empty = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Empty']);

    $html = $this->get('http://counts.test/clubs')->assertOk()->getContent();

    expect($html)->not->toContain('/staff?club='.$empty->id);
});
