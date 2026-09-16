<?php

declare(strict_types=1);

namespace App\Livewire\Clubs;

use App\Enums\ClubAssignmentStatus;
use App\Enums\MemberStatus;
use App\Livewire\Concerns\FiltersByPeriod;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Member;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Club overview with KPIs, assigned staff, and the member roster
 * (MEP.md 6.4). Figures come from the shared OrganisationMetrics service
 * scoped to this single club, so they reconcile with the dashboard.
 */
class Show extends Component
{
    use FiltersByPeriod, ResolvesMembership;

    public Club $club;

    #[Url]
    public string $tab = 'overview';

    #[Url]
    public string $range = 'month';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public function mount(Club $club): void
    {
        $this->authorize('view', $club);

        $this->club = $club;
        $this->applyPreset($this->range, false);
    }

    /**
     * @return Collection<int, ClubUserAssignment>
     */
    protected function assignedStaff(): Collection
    {
        return $this->club->userAssignments()
            ->with('organisationUser.user:id,name,phone')
            ->where('status', ClubAssignmentStatus::Active)
            ->get();
    }

    /**
     * @return Collection<int, Member>
     */
    protected function members(): Collection
    {
        return Member::query()
            ->where('primary_club_id', $this->club->id)
            ->orderBy('name')
            ->limit(100)
            ->get();
    }

    public function render(): View
    {
        $organisation = $this->organisation();
        $period = ReportPeriod::fromStrings($this->from ?: null, $this->to ?: null, $organisation->timezone);
        $metrics = new OrganisationMetrics($organisation, [$this->club->id], $period);

        return view('livewire.clubs.show', [
            'organisation' => $organisation,
            'period' => $period,
            'presets' => ReportPeriod::presets($organisation->timezone),
            'staff' => $this->assignedStaff(),
            'members' => $this->members(),
            'memberCount' => Member::query()->where('primary_club_id', $this->club->id)->where('status', MemberStatus::Active)->count(),
            'revenue' => $metrics->revenueCollected(),
            'expenses' => $metrics->expensesRecorded(),
            'outstanding' => $metrics->outstandingFees(),
            'attendanceRate' => $metrics->attendanceRate(),
            'cashTrend' => $metrics->cashTrend(),
            'attendanceTrend' => $metrics->attendanceTrend(),
            'recentPayments' => $metrics->recentPayments(8),
            'recentExpenses' => $metrics->recentExpenses(8),
        ])->layout('components.layouts.app', ['heading' => $this->club->name]);
    }
}
