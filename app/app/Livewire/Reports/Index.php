<?php

declare(strict_types=1);

namespace App\Livewire\Reports;

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
     * @return array<int, int>
     */
    private function scopedClubIds(): array
    {
        $available = $this->accessibleClubIds();

        return $this->club !== '' && in_array((int) $this->club, $available, true)
            ? [(int) $this->club]
            : $available;
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

    public function render(): View
    {
        $organisation = $this->organisation();
        $period = ReportPeriod::fromStrings($this->from ?: null, $this->to ?: null, $organisation->timezone);
        $metrics = new OrganisationMetrics($organisation, $this->scopedClubIds(), $period);

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
            'organisation' => $organisation,
            'period' => $period,
            'presets' => ReportPeriod::presets($organisation->timezone),
            'clubs' => $this->accessibleClubs(true),
            'exportQuery' => $this->exportQuery(),
        ])->layout('components.layouts.app', ['heading' => 'Reports']);
    }
}
