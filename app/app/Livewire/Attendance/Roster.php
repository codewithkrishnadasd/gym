<?php

declare(strict_types=1);

namespace App\Livewire\Attendance;

use App\Actions\Attendance\MarkAttendance;
use App\Enums\AttendanceAction;
use App\Enums\AttendanceSubjectType;
use App\Enums\ClubAssignmentStatus;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\Member;
use App\Models\OrganisationUser;
use App\Support\RosterEntry;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * The daily attendance workflow for one club and one date (MEP.md 6.7).
 * Serves both the member roster and the staff roster; which one is decided
 * by the route, and each is permissioned separately.
 *
 * Marking is optimistic on the client and idempotent on the server — the
 * upsert in MarkAttendance is keyed on the table's unique index, so two staff
 * marking the same roster simultaneously cannot create duplicate rows.
 */
class Roster extends Component
{
    use ResolvesMembership;

    public AttendanceSubjectType $subjectType = AttendanceSubjectType::Member;

    #[Url]
    public string $date = '';

    #[Url]
    public ?int $clubId = null;

    #[Url]
    public string $search = '';

    /** Set after a bulk action so the UI can confirm what happened. */
    public ?string $bulkResult = null;

    public function mount(string $subject = 'members'): void
    {
        $this->subjectType = $subject === 'staff' ? AttendanceSubjectType::User : AttendanceSubjectType::Member;

        $this->authorize('mark', [Attendance::class, $this->subjectType, null]);

        $this->date = $this->date !== '' ? $this->date : Carbon::today($this->organisation()->timezone)->toDateString();

        $clubs = $this->accessibleClubs();

        if ($this->clubId === null || ! $clubs->contains('id', $this->clubId)) {
            $this->clubId = $clubs->first()?->id;
        }
    }

    public function updatedClubId(): void
    {
        $this->bulkResult = null;
    }

    public function shiftDate(int $days): void
    {
        $this->date = Carbon::parse($this->date)->addDays($days)->toDateString();
        $this->bulkResult = null;
    }

    public function goToToday(): void
    {
        $this->date = Carbon::today($this->organisation()->timezone)->toDateString();
        $this->bulkResult = null;
    }

    public function mark(int $subjectId, string $action): void
    {
        $club = $this->currentClub();

        if (! $club) {
            return;
        }

        $this->authorize('mark', [Attendance::class, $this->subjectType, $club->id]);
        $this->assertSubjectBelongsToClub($subjectId, $club);

        app(MarkAttendance::class)->handle(
            club: $club,
            subjectType: $this->subjectType,
            subjectId: $subjectId,
            date: Carbon::parse($this->date),
            action: AttendanceAction::from($action),
            actor: $this->currentMembership(),
        );

        $this->bulkResult = null;
    }

    /**
     * Marks everyone who has no record yet, deliberately leaving existing
     * marks alone so a bulk action can never silently overwrite a
     * deliberate "absent" or "excused".
     */
    public function markAllPresent(): void
    {
        $club = $this->currentClub();

        if (! $club) {
            return;
        }

        $this->authorize('mark', [Attendance::class, $this->subjectType, $club->id]);

        $existing = $this->attendanceBySubject()->keys()->all();
        $unmarked = $this->roster()->pluck('id')->reject(fn (int $id): bool => in_array($id, $existing, true))->values()->all();

        $count = app(MarkAttendance::class)->handleMany(
            club: $club,
            subjectType: $this->subjectType,
            subjectIds: $unmarked,
            date: Carbon::parse($this->date),
            action: AttendanceAction::Present,
            actor: $this->currentMembership(),
        );

        $this->bulkResult = $count === 0
            ? 'Everyone on this roster is already marked.'
            : $count.' marked present.';
    }

    private function currentClub(): ?Club
    {
        return $this->clubId === null ? null : $this->accessibleClubs()->firstWhere('id', $this->clubId);
    }

    /**
     * Defence in depth: the roster query already scopes to the club, but an
     * ID arriving from the client is re-checked before it is written.
     */
    private function assertSubjectBelongsToClub(int $subjectId, Club $club): void
    {
        $belongs = $this->subjectType === AttendanceSubjectType::Member
            ? Member::query()->whereKey($subjectId)->where('primary_club_id', $club->id)->exists()
            : OrganisationUser::query()->whereKey($subjectId)->whereHas(
                'clubAssignments',
                fn ($query) => $query->where('club_id', $club->id)->where('status', ClubAssignmentStatus::Active)
            )->exists();

        abort_unless($belongs, 403);
    }

    /**
     * @return Collection<int, RosterEntry>
     */
    protected function roster(): Collection
    {
        if ($this->clubId === null) {
            return collect();
        }

        if ($this->subjectType === AttendanceSubjectType::Member) {
            return Member::query()
                ->where('primary_club_id', $this->clubId)
                ->whereIn('status', [MemberStatus::Active, MemberStatus::Paused])
                ->when($this->search !== '', fn ($query) => $query->where(
                    fn ($inner) => $inner->where('name', 'ilike', "%{$this->search}%")
                        ->orWhere('phone', 'ilike', "%{$this->search}%")
                ))
                ->orderBy('name')
                ->get()
                ->map(fn (Member $member): RosterEntry => new RosterEntry(
                    id: $member->id,
                    name: $member->name,
                    detail: $member->phone,
                ));
        }

        return OrganisationUser::query()
            ->with('user')
            ->where('status', MembershipStatus::Active)
            ->whereHas('clubAssignments', fn ($query) => $query
                ->where('club_id', $this->clubId)
                ->where('status', ClubAssignmentStatus::Active))
            ->get()
            ->filter(fn (OrganisationUser $staff): bool => $this->search === ''
                || str_contains(strtolower((string) $staff->user?->name), strtolower($this->search)))
            ->sortBy(fn (OrganisationUser $staff): string => (string) $staff->user?->name)
            ->values()
            ->map(fn (OrganisationUser $staff): RosterEntry => new RosterEntry(
                id: $staff->id,
                name: (string) $staff->user?->name,
                detail: $staff->isAdmin() ? 'Administrator' : $this->organisation()->term('user_singular'),
            ));
    }

    /**
     * @return Collection<int, Attendance>
     */
    protected function attendanceBySubject(): Collection
    {
        if ($this->clubId === null) {
            return collect();
        }

        return Attendance::query()
            ->with('markedBy.user')
            ->where('club_id', $this->clubId)
            ->where('subject_type', $this->subjectType->value)
            ->whereDate('attendance_date', $this->date)
            ->get()
            ->keyBy('subject_id');
    }

    public function render(): View
    {
        $roster = $this->roster();
        $attendance = $this->attendanceBySubject();

        $present = $attendance->where('action', AttendanceAction::Present)->count();
        $marked = $roster->filter(fn (RosterEntry $row): bool => $attendance->has($row->id))->count();

        return view('livewire.attendance.roster', [
            'organisation' => $this->organisation(),
            'clubs' => $this->accessibleClubs(),
            'roster' => $roster,
            'attendance' => $attendance,
            'actions' => AttendanceAction::cases(),
            'presentCount' => $present,
            'markedCount' => $marked,
            'unmarkedCount' => max(0, $roster->count() - $marked),
            'isToday' => Carbon::parse($this->date)->isSameDay(Carbon::today($this->organisation()->timezone)),
            'canMark' => $this->clubId !== null
                && auth()->user()?->can('mark', [Attendance::class, $this->subjectType, $this->clubId]),
        ])->layout('components.layouts.app', ['heading' => 'Attendance']);
    }
}
