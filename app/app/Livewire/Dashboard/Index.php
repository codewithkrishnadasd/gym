<?php

declare(strict_types=1);

namespace App\Livewire\Dashboard;

use App\Enums\ConfirmationStatus;
use App\Enums\MemberStatus;
use App\Livewire\Concerns\FiltersByPeriod;
use App\Livewire\Concerns\RemembersFilters;
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
    use FiltersByPeriod, RemembersFilters, ResolvesMembership;

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
            'links' => $this->links($period),
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
     * Where each KPI card leads: the list it was counted from, opened with
     * the same period and club so the rows add up to the figure shown.
     *
     * @return array<string, string>
     */
    private function links(ReportPeriod $period): array
    {
        $organisation = $this->organisation();
        $today = Carbon::today($organisation->timezone);
        $club = $this->club !== '' ? ['club' => $this->club] : [];
        $range = ['from' => $period->from->toDateString(), 'to' => $period->to->toDateString(), ...$club];
        $reportRange = ['range' => $this->range, ...$range];

        return [
            'revenue' => route('tenant.finance.payments.index', ['status' => ConfirmationStatus::Confirmed->value, ...$range]),
            'outstanding' => route('tenant.members.index', ['balance' => 'due', ...$club]),
            'expenses' => route('tenant.finance.expenses.index', ['status' => 'completed', ...$range]),
            'net' => route('tenant.reports.index', ['tab' => 'finance', ...$reportRange]),
            'activeMembers' => route('tenant.members.index', ['status' => MemberStatus::Active->value, ...$club]),
            'newMembers' => route('tenant.members.index', ['joinedFrom' => $period->from->toDateString(), 'joinedTo' => $period->to->toDateString(), ...$club]),
            'expiring' => route('tenant.members.index', ['endingBy' => $today->copy()->addDays(30)->toDateString(), ...$club]),
            'pending' => route('tenant.finance.confirmations', $club),
            'attendanceRate' => route('tenant.reports.index', ['tab' => 'attendance', ...$reportRange]),
            'todaysAttendance' => route('tenant.attendance.members', ['date' => $today->toDateString(), ...($this->club !== '' ? ['clubId' => $this->club] : [])]),
            'staff' => route('tenant.staff.index'),
            'clubs' => route('tenant.clubs.index'),
            // Staff cards are "all time", so the payment list is opened from
            // the day the organisation started.
            'ownConfirmed' => route('tenant.finance.payments.index', ['status' => ConfirmationStatus::Confirmed->value, 'from' => $organisation->created_at?->toDateString(), 'to' => $today->toDateString()]),
            'ownPending' => route('tenant.finance.payments.index', ['status' => ConfirmationStatus::PendingAdminConfirmation->value, 'from' => $organisation->created_at?->toDateString(), 'to' => $today->toDateString()]),
            'ownRejected' => route('tenant.finance.payments.index', ['status' => ConfirmationStatus::Rejected->value, 'from' => $organisation->created_at?->toDateString(), 'to' => $today->toDateString()]),
            'roster' => route('tenant.members.index', $club),
        ];
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
            'outstanding' => $metrics->outstandingTotal(),
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

        // Permissions read through the policies so a module that is switched
        // off (App\Enums\Feature) takes its shortcuts and cards with it.
        $user = auth()->user();

        return [
            'canMarkAttendance' => $user?->can('markMembers', Attendance::class) ?? false,
            'canCollectFees' => $user?->can('create', FeePayment::class) ?? false,
            'canViewMembers' => $user?->can('viewAny', Member::class) ?? false,
            'todaysAttendance' => Attendance::query()
                ->when($clubIds !== null, fn ($query) => $query->whereIn('club_id', $clubIds))
                ->where('subject_type', 'member')
                ->whereDate('attendance_date', $today->toDateString())
                ->count(),
            'rosterSize' => Member::query()
                ->when($clubIds !== null, fn ($query) => $query->whereIn('primary_club_id', $clubIds))
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
