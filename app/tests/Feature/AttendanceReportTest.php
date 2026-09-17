<?php

declare(strict_types=1);

use App\Enums\AttendanceAction;
use App\Livewire\Attendance\Report;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

/**
 * A person's attendance as a month calendar with the figures that matter,
 * on the member's page and the staff member's page alike.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'attend.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Regular Ravi']);
});

function markOn(Member $member, Club $club, Carbon $date, AttendanceAction $action): void
{
    Attendance::factory()->create([
        'organisation_id' => $member->organisation_id,
        'club_id' => $club->id,
        'subject_type' => 'member',
        'subject_id' => $member->id,
        'attendance_date' => $date->toDateString(),
        'action' => $action,
        'marked_by' => test()->admin->id,
    ]);
}

it('shows the month, the streak, the rate and the usual days for a member', function (): void {
    $today = Carbon::today('Asia/Kolkata');

    // Three days in a row up to today, one late, and an absence last week.
    markOn($this->member, $this->club, $today->copy()->subDays(2), AttendanceAction::Present);
    markOn($this->member, $this->club, $today->copy()->subDay(), AttendanceAction::Late);
    markOn($this->member, $this->club, $today, AttendanceAction::Present);
    markOn($this->member, $this->club, $today->copy()->subDays(8), AttendanceAction::Absent);

    Livewire::test(Report::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->assertSee('Current streak')
        ->assertSee('3 days')
        ->assertSee('Attendance rate')
        ->assertSee('75%')
        ->assertSee('3 visits in 12 weeks')
        ->assertSee($today->format('F Y'))
        ->assertSee('Usual days')
        ->assertSee('Recent')
        ->assertSee('Late')
        // Stepping back shows the earlier month; forward past today is refused.
        ->call('shiftMonth', -1)
        ->assertSee($today->copy()->subMonthNoOverflow()->format('F Y'))
        ->call('shiftMonth', 2)
        ->assertSee($today->copy()->subMonthNoOverflow()->format('F Y'))
        ->call('thisMonth')
        ->assertSee($today->format('F Y'));

    $this->get('http://attend.test/members/'.$this->member->id.'?tab=attendance')->assertOk()->assertSee('Visits this month')->assertSee('Usual days');
});

it('appears on a staff member\'s page, and not without the Attendance module', function (): void {
    $staff = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Coach Kim'])->id]);

    Attendance::factory()->create([
        'organisation_id' => $this->organisation->id,
        'club_id' => null,
        'subject_type' => 'user',
        'subject_id' => $staff->id,
        'attendance_date' => Carbon::today('Asia/Kolkata')->toDateString(),
        'action' => AttendanceAction::Present,
        'marked_by' => $this->admin->id,
    ]);

    $this->get('http://attend.test/staff/'.$staff->id.'/edit')->assertOk()->assertSee('Visits this month')->assertSee('1 day');

    $this->organisation->update(['features' => ['members', 'staff', 'clubs']]);
    app()->instance('tenant', $this->organisation->fresh());

    $this->get('http://attend.test/staff/'.$staff->id.'/edit')->assertOk()->assertDontSee('Visits this month');
    $this->get('http://attend.test/members/'.$this->member->id)->assertOk()->assertDontSee('tab=attendance');
});
