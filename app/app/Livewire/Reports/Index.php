<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

use App\Enums\Feature;
use App\Livewire\Concerns\ResolvesMembership;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * Reporting across finance, membership, and attendance (MEP.md 6.11).
 *
 * Every figure here comes from OrganisationMetrics with the acting user's own
 * club scope, and the CSV/PDF export routes reuse the same service with the
 * same scope — so an export can never contain a row the user cannot see
 * on screen.
 */
class Index extends Component
{
    use ResolvesMembership;

    #[Url]
    public string $tab = 'finance';

    #[Url]
    public string $range = 'month';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    #[Url]
    public string $club = '';

    public function mount(): void
    {
        $this->authorize('viewReports', $this->organisation());

        $this->applyPreset($this->range, false);
    }

    public function applyPreset(string $key, bool $resetCustom = true): void
    {
        $presets = ReportPeriod::presets($this->organisation()->timezone);

        if (! isset($presets[$key])) {
            return;
        }

        $this->range = $key;

        if ($resetCustom || $this->from === '' || $this->to === '') {
            $this->from = $presets[$key]['from'];
            $this->to = $presets[$key]['to'];
        }
    }

    public function updatedFrom(): void
    {
        $this->range = 'custom';
    }

    public function updatedTo(): void
    {
        $this->range = 'custom';
    }

    /**
     * One club when the filter names one the user may see; otherwise the
     * user's whole reach (null for admins and for organisations without the
     * Clubs module — see ResolvesMembership::clubRestriction()).
     *
     * @return array<int, int>|null
     */
    private function scopedClubIds(): ?array
    {
        if ($this->club !== '' && $this->organisation()->usesClubs()) {
            $available = $this->accessibleClubIds();

            if (in_array((int) $this->club, $available, true)) {
                return [(int) $this->club];
            }
        }

        return $this->clubRestriction();
    }

    /**
     * @return array<string, string>
     */
    private function exportQuery(): array
    {
        return array_filter([
            'from' => $this->from,
            'to' => $this->to,
            'club' => $this->club,
        ], static fn (string $value): bool => $value !== '');
    }

    /**
     * The reports this organisation has anything to report on: money when it
     * collects or spends any, members and attendance when it tracks them.
     *
     * @return array<string, string>
     */
    private function availableTabs(): array
    {
        $organisation = $this->organisation();

        return array_filter([
            'finance' => $organisation->hasFeature(Feature::Payments) || $organisation->hasFeature(Feature::Expenses) ? 'Finance' : null,
            'members' => $organisation->hasFeature(Feature::Members) ? $organisation->term('member_plural') : null,
            'attendance' => $organisation->hasFeature(Feature::Attendance) && $organisation->hasFeature(Feature::Members) ? 'Attendance' : null,
        ]);
    }

    public function render(): View
    {
        $organisation = $this->organisation();
        $period = ReportPeriod::fromStrings($this->from ?: null, $this->to ?: null, $organisation->timezone);
        $metrics = new OrganisationMetrics($organisation, $this->scopedClubIds(), $period);

        $tabs = $this->availableTabs();

        if (! isset($tabs[$this->tab])) {
            $this->tab = (string) (array_key_first($tabs) ?? 'finance');
        }

        $data = match ($this->tab) {
            'members' => [
                'growth' => $metrics->memberGrowthTrend(),
                'statusSplit' => $metrics->memberStatusSplit(),
                'activeMembers' => $metrics->activeMembers(),
                'newMembers' => $metrics->newMembers(),
                'expiring' => $metrics->expiringSubscriptions(),
                'followUps' => $metrics->membersNeedingFollowUp(15),
            ],
            'attendance' => [
                'attendanceTrend' => $metrics->attendanceTrend(),
                'actionSplit' => $metrics->attendanceActionSplit(),
                'attendanceRate' => $metrics->attendanceRate(),
                'clubComparison' => $metrics->clubComparison(),
            ],
            default => [
                'statusTotals' => $metrics->paymentStatusTotals(),
                'revenue' => $metrics->revenueCollected(),
                'expenses' => $metrics->expensesRecorded(),
                'netMovement' => $metrics->netMovement(),
                'outstanding' => $metrics->outstandingFees(),
                'cashTrend' => $metrics->cashTrend(),
                'methodSplit' => $metrics->paymentMethodSplit(),
                'planSplit' => $metrics->revenueByPlan(),
                'accountSplit' => $metrics->revenueByAccount(),
                'categorySplit' => $metrics->expenseCategorySplit(),
                'clubComparison' => $metrics->clubComparison(),
                'leaderboard' => $metrics->collectionLeaderboard(10),
                'turnaround' => $metrics->confirmationTurnaroundHours(),
            ],
        };

        return view('livewire.reports.index', [
            ...$data,
            'reportTabs' => $tabs,
            'organisation' => $organisation,
            'period' => $period,
            'presets' => ReportPeriod::presets($organisation->timezone),
            'clubs' => $this->accessibleClubs(true),
            'exportQuery' => $this->exportQuery(),
        ])->layout('components.layouts.app', ['heading' => 'Reports']);
    }
}
