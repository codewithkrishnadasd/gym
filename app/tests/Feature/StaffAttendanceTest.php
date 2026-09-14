<?php

declare(strict_types=1);

use App\Enums\AttendanceAction;
use App\Enums\ClubAssignmentStatus;
use App\Livewire\Attendance\Roster;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Staff attendance is per day for the organisation, not per club: one mark
 * a day for each person, no club to choose, and everyone active is listed
 * whether or not they are assigned anywhere.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'roster.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->clubA = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    $this->clubB = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'South']);

    $user = User::factory()->create(['name' => 'Admin Person']);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->unassigned = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => User::factory()->create(['name' => 'Floating Trainer'])->id,
    ]);

    $this->assigned = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => User::factory()->create(['name' => 'North Trainer'])->id,
    ]);
    ClubUserAssignment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'organisation_user_id' => $this->assigned->id,
        'club_id' => $this->clubA->id,
        'status' => ClubAssignmentStatus::Active,
    ]);
});

it('lists every active staff member with no club to pick', function (): void {
    $this->get('http://roster.test/attendance/users')
        ->assertOk()
        ->assertSee('Floating Trainer')
        ->assertSee('North Trainer')
        ->assertSee('Admin Person')
        ->assertDontSee('wire:model.live="clubId"', false);
});

it('records one mark per person per day with no club, whichever roster it came from', function (): void {
    Livewire::test(Roster::class, ['subject' => 'staff'])
        ->set('date', '2026-09-14')
        ->call('mark', $this->unassigned->id, 'present')
        ->call('mark', $this->assigned->id, 'present')
        // Marking again just changes the mark; it never duplicates the day.
        ->call('mark', $this->assigned->id, 'absent');

    $rows = Attendance::query()->where('subject_type', 'user')->get();

    expect($rows)->toHaveCount(2)
        ->and($rows->pluck('club_id')->unique()->all())->toBe([null])
        ->and($rows->firstWhere('subject_id', $this->assigned->id)?->action)->toBe(AttendanceAction::Absent);

    // Also visible from the other end of the day switch.
    Livewire::test(Roster::class, ['subject' => 'staff'])
        ->set('date', '2026-09-14')
        ->assertSee('Floating Trainer');
});

it('marks everyone present in one go', function (): void {
    Livewire::test(Roster::class, ['subject' => 'staff'])
        ->set('date', '2026-09-14')
        ->call('markAllPresent')
        ->assertSet('bulkResult', '3 marked present.');

    expect(Attendance::query()->where('subject_type', 'user')->whereNull('club_id')->count())->toBe(3);
});

it('still marks members per club', function (): void {
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->clubA->id]);

    Livewire::test(Roster::class, ['subject' => 'members'])
        ->set('date', '2026-09-14')
        ->set('clubId', $this->clubA->id)
        ->call('mark', $member->id, 'present');

    expect(Attendance::query()->where('subject_type', 'member')->value('club_id'))->toBe($this->clubA->id);
});
