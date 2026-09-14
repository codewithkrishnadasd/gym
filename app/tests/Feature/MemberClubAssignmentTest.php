<?php

declare(strict_types=1);

use App\Enums\ClubAssignmentStatus;
use App\Livewire\Members\Form as MemberForm;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Which club a new member lands in: prefilled from the club page, and never
 * one the acting staff member is not assigned to.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'clubs.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->north = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    $this->south = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'South']);
});

function staffAssignedTo(Organisation $organisation, Club ...$clubs): OrganisationUser
{
    $user = User::factory()->create();
    $staff = OrganisationUser::factory()->create([
        'organisation_id' => $organisation->id,
        'user_id' => $user->id,
        'permissions' => ['members.view' => true, 'members.create' => true],
    ]);

    foreach ($clubs as $club) {
        ClubUserAssignment::factory()->create([
            'organisation_id' => $organisation->id,
            'organisation_user_id' => $staff->id,
            'club_id' => $club->id,
            'status' => ClubAssignmentStatus::Active,
        ]);
    }

    test()->actingAs($user);

    return $staff;
}

it('prefills the club when the form is opened from a club page', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->get('http://clubs.test/members/create?club='.$this->south->id)
        ->assertOk()
        ->assertSeeHtml('value="'.$this->south->id.'"');

    Livewire::withQueryParams(['club' => $this->south->id])
        ->test(MemberForm::class)
        ->assertSet('primaryClubId', $this->south->id);
});

it('links "Add member" on the club page to that club', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->get('http://clubs.test/clubs/'.$this->north->id.'?tab=members')
        ->assertOk()
        ->assertSee('/members/create?club='.$this->north->id, false);
});

it('preselects the only club a staff member can add to', function (): void {
    staffAssignedTo($this->organisation, $this->south);

    Livewire::test(MemberForm::class)
        ->assertSet('primaryClubId', $this->south->id)
        ->assertSee('South')
        ->assertDontSee('North');
});

it('ignores a club the staff member is not assigned to', function (): void {
    staffAssignedTo($this->organisation, $this->north, $this->south);

    $other = Club::factory()->create(['organisation_id' => $this->organisation->id]);

    Livewire::withQueryParams(['club' => $other->id])
        ->test(MemberForm::class)
        ->assertSet('primaryClubId', null);
});

it('refuses to create a member in a club the staff member is not assigned to', function (): void {
    staffAssignedTo($this->organisation, $this->north);

    Livewire::test(MemberForm::class)
        ->set('name', 'Priya Nair')
        ->set('phone', '9876543210')
        ->set('primaryClubId', $this->south->id)
        ->call('save')
        ->assertHasErrors(['primaryClubId']);

    expect(Member::query()->where('name', 'Priya Nair')->exists())->toBeFalse();
});

it('lets the staff member create in an assigned club', function (): void {
    staffAssignedTo($this->organisation, $this->north);

    Livewire::test(MemberForm::class)
        ->set('name', 'Priya Nair')
        ->set('phone', '9876543210')
        ->call('save')
        ->assertHasNoErrors();

    expect(Member::query()->where('name', 'Priya Nair')->value('primary_club_id'))->toBe($this->north->id);
});
