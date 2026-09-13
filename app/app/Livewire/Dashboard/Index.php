<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\ConfirmationStatus;
use App\Enums\MemberStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Attendance;
use App\Models\FeePayment;
use App\Models\Member;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * The tenant landing page. Admins get the organisation-wide operating picture
 * (MEP.md 6.2); staff get a work-focused view limited to their assigned clubs
 * and granted permissions (MEP.md 6.3).
 *
 * Both are driven by the same OrganisationMetrics service, differing only in
 * the club scope handed to it — so a staff member's numbers are a true subset
 * of the admin's, never a separately-computed approximation.
 */
class Index extends Component
{
    use ResolvesMembership;

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

    public function render(): View
    {
        $organisation = $this->organisation();
        $membership = $this->currentMembership();
        $isAdmin = $membership->isAdmin();

        $period = ReportPeriod::fromStrings($this->from ?: null, $this->to ?: null, $organisation->timezone);
        $metrics = new OrganisationMetrics($organisation, $this->scopedClubIds(), $period);
        $previous = $metrics->withPeriod($period->previous());

        $shared = [
            'organisation' => $organisation,
            'membership' => $membership,
            'period' => $period,
            'presets' => ReportPeriod::presets($organisation->timezone),
            'clubs' => $this->accessibleClubs(),
        ];

        return $isAdmin
            ? view('livewire.dashboard.admin', [...$shared, ...$this->adminData($metrics, $previous)])
                ->layout('components.layouts.app', ['heading' => 'Dashboard'])
            : view('livewire.dashboard.staff', [...$shared, ...$this->staffData($metrics)])
                ->layout('components.layouts.app', ['heading' => 'Dashboard']);
    }

    /**
     * @return array<string, mixed>
     */
    private function adminData(OrganisationMetrics $metrics, OrganisationMetrics $previous): array
    {
        $revenue = $metrics->revenueCollected();
        $expenses = $metrics->expensesRecorded();

        return [
            'activeMembers' => $metrics->activeMembers(),
            'newMembers' => $metrics->newMembers(),
            'newMembersPrevious' => $previous->newMembers(),
            'expiring' => $metrics->expiringSubscriptions(),
            'revenue' => $revenue,
            'revenuePrevious' => $previous->revenueCollected(),
            'outstanding' => $metrics->outstandingFees(),
            'expenses' => $expenses,
            'netMovement' => $revenue - $expenses,
            'attendanceRate' => $metrics->attendanceRate(),
            'activeStaff' => $metrics->activeStaff(),
            'todaysAttendance' => $metrics->todaysAttendance(),
            'pendingCount' => $metrics->pendingConfirmations(),
            'pendingValue' => $metrics->pendingConfirmationsValue(),
            'cashTrend' => $metrics->cashTrend(),
            'attendanceTrend' => $metrics->attendanceTrend(),
            'clubComparison' => $metrics->clubComparison(),
            'recentPayments' => $metrics->recentPayments(),
            'pendingPayments' => $metrics->pendingPayments(),
            'recentExpenses' => $metrics->recentExpenses(),
            'followUps' => $metrics->membersNeedingFollowUp(),
            'leaderboard' => $metrics->collectionLeaderboard(),
            'alerts' => $metrics->alerts(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function staffData(OrganisationMetrics $metrics): array
    {
        $membership = $this->currentMembership();
        $organisation = $this->organisation();
        $today = Carbon::today($organisation->timezone);
        $clubIds = $this->scopedClubIds();

        $ownCollections = FeePayment::query()
            ->with(['member:id,name', 'club:id,name'])
            ->where('collected_by', $membership->id)
            ->whereBetween('payment_date', [$this->from ?: $today->toDateString(), $this->to ?: $today->toDateString()])
            ->orderByDesc('payment_date')
            ->limit(8)
            ->get();

        $byStatus = FeePayment::query()
            ->where('collected_by', $membership->id)
            ->toBase()
            ->selectRaw('confirmation_status, COALESCE(SUM(amount_minor), 0) AS total, COUNT(*) AS entries')
            ->groupBy('confirmation_status')
            ->get()
            ->keyBy('confirmation_status');

        return [
            'canMarkAttendance' => $membership->hasPermission('attendance.member.mark'),
            'canCollectFees' => $membership->hasPermission('fees.collect'),
            'canViewMembers' => $membership->hasPermission('members.view'),
            'todaysAttendance' => Attendance::query()
                ->whereIn('club_id', $clubIds)
                ->where('subject_type', 'member')
                ->whereDate('attendance_date', $today->toDateString())
                ->count(),
            'rosterSize' => Member::query()
                ->whereIn('primary_club_id', $clubIds)
                ->whereIn('status', [MemberStatus::Active, MemberStatus::Paused])
                ->count(),
            'followUps' => $metrics->membersNeedingFollowUp(6),
            'ownCollections' => $ownCollections,
            'confirmedTotal' => (int) ($byStatus[ConfirmationStatus::Confirmed->value]->total ?? 0),
            'pendingTotal' => (int) ($byStatus[ConfirmationStatus::PendingAdminConfirmation->value]->total ?? 0),
            'pendingCount' => (int) ($byStatus[ConfirmationStatus::PendingAdminConfirmation->value]->entries ?? 0),
            'rejectedCount' => (int) ($byStatus[ConfirmationStatus::Rejected->value]->entries ?? 0),
        ];
    }
}
