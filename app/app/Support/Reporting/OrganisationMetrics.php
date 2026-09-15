<?php

declare(strict_types=1);

namespace App\Support\Reporting;

use App\Enums\AttendanceAction;
use App\Enums\AttendanceSubjectType;
use App\Enums\ConfirmationStatus;
use App\Enums\ExpenseStatus;
use App\Enums\InvoiceStatus;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Support\Money;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Every dashboard card, report figure, and export total is computed here, so
 * "revenue" means exactly the same thing on the dashboard, in the reports
 * page, and in a downloaded CSV.
 *
 * The rules that matter (MEP.md 8.4, 6.11):
 *   - only `confirmed` payments count as revenue; pending, rejected, and
 *     reversed are reported separately and never folded in;
 *   - only `completed` expenses count as spend;
 *   - every query is constrained to the caller's permitted club scope, which
 *     is passed in rather than derived here, so on-screen lists and exports
 *     use identical scoping.
 */
final class OrganisationMetrics
{
    /**
     * @param  array<int, int>|null  $clubIds  The clubs the acting user may see, or null for the whole organisation.
     */
    public function __construct(
        private readonly Organisation $organisation,
        private readonly ?array $clubIds,
        private readonly ReportPeriod $period,
    ) {}

    public function withPeriod(ReportPeriod $period): self
    {
        return new self($this->organisation, $this->clubIds, $period);
    }

    // ---------------------------------------------------------------- cards

    public function activeMembers(): int
    {
        return $this->memberScope()->where('status', MemberStatus::Active)->count();
    }

    public function newMembers(): int
    {
        return $this->memberScope()
            ->whereBetween('joined_at', [$this->period->from->toDateString(), $this->period->to->toDateString()])
            ->count();
    }

    public function expiringSubscriptions(int $withinDays = 30): int
    {
        $today = Carbon::today($this->organisation->timezone);

        return $this->subscriptionScope()
            ->where('status', SubscriptionStatus::Active)
            ->whereBetween('end_date', [$today->toDateString(), $today->copy()->addDays($withinDays)->toDateString()])
            ->count();
    }

    /**
     * Plans whose term has already ended but which nothing has replaced.
     *
     * Counted from `end_date` rather than the status column, because that
     * column only changes when a renewal supersedes the term — a plan that ran
     * out and was never renewed is still stored as active. These are the
     * members who have quietly stopped paying.
     */
    public function lapsedSubscriptions(): int
    {
        $today = Carbon::today($this->organisation->timezone);

        return $this->subscriptionScope()
            ->where('status', SubscriptionStatus::Active)
            ->whereDate('end_date', '<', $today->toDateString())
            ->count();
    }

    public function revenueCollected(): int
    {
        return (int) $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->sum('amount_minor');
    }

    /**
     * What members still owe on subscriptions that are not fully paid. Not
     * period-scoped: an outstanding balance is a present-tense fact.
     */
    public function outstandingFees(): int
    {
        return (int) $this->subscriptionScope()
            ->whereIn('status', [SubscriptionStatus::Active, SubscriptionStatus::Expired])
            ->whereColumn('amount_paid_minor', '<', 'amount_due_minor')
            ->selectRaw('COALESCE(SUM(amount_due_minor - amount_paid_minor), 0) AS due')
            ->value('due');
    }

    /**
     * Money still owed on open invoices — a separate figure from plan fees,
     * because the two are collected and chased differently.
     */
    public function outstandingInvoices(): int
    {
        return (int) $this->invoiceScope()
            ->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid])
            ->selectRaw('COALESCE(SUM(total_minor - paid_minor), 0) AS due')
            ->value('due');
    }

    public function overdueInvoices(): int
    {
        $today = Carbon::today($this->organisation->timezone);

        return $this->invoiceScope()
            ->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid])
            ->whereDate('due_date', '<', $today->toDateString())
            ->count();
    }

    /**
     * @return Builder<Invoice>
     */
    private function invoiceScope(): Builder
    {
        return $this->withinClubs(Invoice::query());
    }

    public function expensesRecorded(): int
    {
        return (int) $this->expenseScope()->where('status', ExpenseStatus::Completed)->sum('amount_minor');
    }

    public function netMovement(): int
    {
        return $this->revenueCollected() - $this->expensesRecorded();
    }

    public function pendingConfirmations(): int
    {
        return $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::PendingAdminConfirmation)
            ->count();
    }

    public function pendingConfirmationsValue(): int
    {
        return (int) $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::PendingAdminConfirmation)
            ->sum('amount_minor');
    }

    /**
     * Present (and late) marks as a share of all marks made in the period.
     */
    public function attendanceRate(): float
    {
        $marks = $this->attendanceScope(AttendanceSubjectType::Member);
        $total = $marks->clone()->count();

        if ($total === 0) {
            return 0.0;
        }

        $present = $marks->clone()
            ->whereIn('action', [AttendanceAction::Present, AttendanceAction::Late])
            ->count();

        return round($present / $total * 100, 1);
    }

    public function todaysAttendance(): int
    {
        return $this->withinClubs(Attendance::query())
            ->where('subject_type', AttendanceSubjectType::Member->value)
            ->whereDate('attendance_date', Carbon::today($this->organisation->timezone)->toDateString())
            ->whereIn('action', [AttendanceAction::Present, AttendanceAction::Late])
            ->count();
    }

    public function activeStaff(): int
    {
        return OrganisationUser::query()->where('status', MembershipStatus::Active)->count();
    }

    // --------------------------------------------------------------- trends

    /**
     * Confirmed revenue and completed expenses per bucket, gap-filled so the
     * chart's x-axis is continuous even on days with no activity.
     *
     * @return array{labels: array<int, string>, revenue: array<int, float>, expenses: array<int, float>, net: array<int, float>}
     */
    public function cashTrend(): array
    {
        $buckets = $this->buckets();
        $expression = $this->bucketExpression('payment_date');

        $revenue = $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->selectRaw("{$expression} AS bucket, COALESCE(SUM(amount_minor), 0) AS total")
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        $expenses = $this->expenseScope()
            ->where('status', ExpenseStatus::Completed)
            ->selectRaw($this->bucketExpression('expense_date').' AS bucket, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        $labels = [];
        $revenueSeries = [];
        $expenseSeries = [];
        $netSeries = [];

        foreach ($buckets as $key => $label) {
            $in = $this->toMajor((int) ($revenue[$key] ?? 0));
            $out = $this->toMajor((int) ($expenses[$key] ?? 0));

            $labels[] = $label;
            $revenueSeries[] = $in;
            $expenseSeries[] = $out;
            $netSeries[] = round($in - $out, 2);
        }

        return ['labels' => $labels, 'revenue' => $revenueSeries, 'expenses' => $expenseSeries, 'net' => $netSeries];
    }

    /**
     * @return array{labels: array<int, string>, present: array<int, int>, absent: array<int, int>}
     */
    public function attendanceTrend(): array
    {
        $buckets = $this->buckets();
        $expression = $this->bucketExpression('attendance_date');

        // Base query: these are per-bucket counts, and hydrating Attendance
        // models would cast `action` to an enum and break the grouping below.
        $rows = $this->attendanceScope(AttendanceSubjectType::Member)
            ->toBase()
            ->selectRaw("{$expression} AS bucket, action, COUNT(*) AS total")
            ->groupBy('bucket', 'action')
            ->get();

        $labels = [];
        $present = [];
        $absent = [];

        foreach ($buckets as $key => $label) {
            $forBucket = $rows->where('bucket', $key);

            $labels[] = $label;
            $present[] = (int) $forBucket->whereIn('action', [AttendanceAction::Present->value, AttendanceAction::Late->value])->sum('total');
            $absent[] = (int) $forBucket->whereIn('action', [AttendanceAction::Absent->value, AttendanceAction::Excused->value])->sum('total');
        }

        return ['labels' => $labels, 'present' => $present, 'absent' => $absent];
    }

    /**
     * @return Collection<int, ClubPerformance>
     */
    public function clubComparison(): Collection
    {
        $clubs = $this->withinClubs(Club::query(), 'id')->orderBy('name')->get();

        $revenue = $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->selectRaw('club_id, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('club_id')
            ->pluck('total', 'club_id');

        $expenses = $this->expenseScope()
            ->where('status', ExpenseStatus::Completed)
            ->selectRaw('club_id, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('club_id')
            ->pluck('total', 'club_id');

        $members = $this->memberScope()
            ->where('status', MemberStatus::Active)
            ->selectRaw('primary_club_id, COUNT(*) AS total')
            ->groupBy('primary_club_id')
            ->pluck('total', 'primary_club_id');

        $attendance = $this->attendanceScope(AttendanceSubjectType::Member)
            ->whereIn('action', [AttendanceAction::Present, AttendanceAction::Late])
            ->selectRaw('club_id, COUNT(*) AS total')
            ->groupBy('club_id')
            ->pluck('total', 'club_id');

        return $clubs->map(fn (Club $club): ClubPerformance => new ClubPerformance(
            club: $club->name,
            revenue: (int) ($revenue[$club->id] ?? 0),
            expenses: (int) ($expenses[$club->id] ?? 0),
            members: (int) ($members[$club->id] ?? 0),
            attendance: (int) ($attendance[$club->id] ?? 0),
        ));
    }

    /**
     * @return Collection<int, CollectorTotal>
     */
    public function collectionLeaderboard(int $limit = 5): Collection
    {
        // Aggregated straight from the query builder: these rows are totals,
        // not FeePayment records, so hydrating models would be misleading.
        $rows = $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->toBase()
            ->selectRaw('collected_by, COALESCE(SUM(amount_minor), 0) AS collected, COUNT(*) AS entries')
            ->groupBy('collected_by')
            ->orderByDesc('collected')
            ->limit($limit)
            ->get();

        $names = OrganisationUser::query()
            ->with('user:id,name')
            ->whereIn('id', $rows->pluck('collected_by')->filter()->all())
            ->get()
            ->mapWithKeys(function (OrganisationUser $person): array {
                /** @var User|null $user */
                $user = $person->user;

                return [$person->id => $user->name ?? 'Unknown'];
            });

        return $rows->map(fn (object $row): CollectorTotal => new CollectorTotal(
            name: (string) ($names[$row->collected_by] ?? 'Unknown'),
            collected: (int) $row->collected,
            count: (int) $row->entries,
        ));
    }

    /**
     * @return array<string, int>
     */
    public function paymentMethodSplit(): array
    {
        return $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->selectRaw('payment_method, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('payment_method')
            ->pluck('total', 'payment_method')
            ->map(fn ($value): int => (int) $value)
            ->all();
    }

    /**
     * @return array<string, int>
     */
    public function expenseCategorySplit(): array
    {
        return $this->expenseScope()
            ->where('status', ExpenseStatus::Completed)
            ->selectRaw('category, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('category')
            ->orderByDesc('total')
            ->pluck('total', 'category')
            ->map(fn ($value): int => (int) $value)
            ->all();
    }

    /**
     * Confirmed revenue grouped by the plan the payment was applied to.
     * Payments not linked to a plan are reported under "Unallocated" rather
     * than silently dropped.
     *
     * @return array<string, int>
     */
    public function revenueByPlan(): array
    {
        $rows = $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->leftJoin('member_subscriptions', 'fee_payments.subscription_id', '=', 'member_subscriptions.id')
            ->leftJoin('plans', 'member_subscriptions.plan_id', '=', 'plans.id')
            ->toBase()
            ->selectRaw("COALESCE(plans.name, 'Unallocated') AS plan, COALESCE(SUM(fee_payments.amount_minor), 0) AS total")
            ->groupBy('plan')
            ->orderByDesc('total')
            ->get();

        return $rows->mapWithKeys(fn (object $row): array => [$row->plan => (int) $row->total])->all();
    }

    /**
     * @return array<string, int>
     */
    public function revenueByAccount(): array
    {
        $rows = $this->paymentScope()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->leftJoin('financial_accounts', 'fee_payments.financial_account_id', '=', 'financial_accounts.id')
            ->toBase()
            ->selectRaw("COALESCE(financial_accounts.name, 'Not specified') AS account, COALESCE(SUM(fee_payments.amount_minor), 0) AS total")
            ->groupBy('account')
            ->orderByDesc('total')
            ->get();

        return $rows->mapWithKeys(fn (object $row): array => [$row->account => (int) $row->total])->all();
    }

    /**
     * Value and count for every lifecycle state, so pending, rejected, and
     * reversed stay visible next to confirmed revenue instead of vanishing.
     *
     * @return array<string, array{total: int, count: int}>
     */
    public function paymentStatusTotals(): array
    {
        $rows = $this->paymentScope()
            ->toBase()
            ->selectRaw('confirmation_status, COALESCE(SUM(amount_minor), 0) AS total, COUNT(*) AS entries')
            ->groupBy('confirmation_status')
            ->get()
            ->keyBy('confirmation_status');

        $totals = [];

        foreach (ConfirmationStatus::cases() as $case) {
            $totals[$case->value] = [
                'total' => (int) ($rows[$case->value]->total ?? 0),
                'count' => (int) ($rows[$case->value]->entries ?? 0),
            ];
        }

        return $totals;
    }

    /**
     * Average hours between a staff submission and the admin's decision — the
     * confirmation turnaround figure required by MEP.md 6.11.
     */
    public function confirmationTurnaroundHours(): ?float
    {
        $average = $this->paymentScope()
            ->whereIn('confirmation_status', [ConfirmationStatus::Confirmed, ConfirmationStatus::Rejected])
            ->whereNotNull('confirmed_at')
            ->toBase()
            ->selectRaw('AVG(EXTRACT(EPOCH FROM (confirmed_at - fee_payments.created_at)) / 3600) AS hours')
            ->value('hours');

        return $average === null ? null : round((float) $average, 1);
    }

    /**
     * New joiners against members who left (archived or went inactive) per
     * bucket — the growth and churn report (MEP.md 6.11).
     *
     * @return array{labels: array<int, string>, joined: array<int, int>, left: array<int, int>}
     */
    public function memberGrowthTrend(): array
    {
        $buckets = $this->buckets();

        $joined = $this->memberScope()
            ->whereBetween('joined_at', [$this->period->from->toDateString(), $this->period->to->toDateString()])
            ->toBase()
            ->selectRaw($this->bucketExpression('joined_at').' AS bucket, COUNT(*) AS total')
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        // "Left" is approximated by when the record was last changed into a
        // non-active state, which is the only signal the schema carries.
        $left = $this->memberScope()
            ->whereIn('status', [MemberStatus::Archived, MemberStatus::Inactive])
            ->whereBetween('updated_at', [$this->period->from, $this->period->to])
            ->toBase()
            ->selectRaw($this->bucketExpression('updated_at').' AS bucket, COUNT(*) AS total')
            ->groupBy('bucket')
            ->pluck('total', 'bucket');

        $labels = [];
        $joinedSeries = [];
        $leftSeries = [];

        foreach ($buckets as $key => $label) {
            $labels[] = $label;
            $joinedSeries[] = (int) ($joined[$key] ?? 0);
            $leftSeries[] = (int) ($left[$key] ?? 0);
        }

        return ['labels' => $labels, 'joined' => $joinedSeries, 'left' => $leftSeries];
    }

    /**
     * @return array<string, int>
     */
    public function attendanceActionSplit(): array
    {
        return $this->attendanceScope(AttendanceSubjectType::Member)
            ->toBase()
            ->selectRaw('action, COUNT(*) AS total')
            ->groupBy('action')
            ->pluck('total', 'action')
            ->map(fn ($value): int => (int) $value)
            ->all();
    }

    /**
     * @return array<string, int>
     */
    public function memberStatusSplit(): array
    {
        return $this->memberScope()
            ->selectRaw('status, COUNT(*) AS total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->map(fn ($value): int => (int) $value)
            ->all();
    }

    // ---------------------------------------------------------------- lists

    /**
     * @return Collection<int, FeePayment>
     */
    public function recentPayments(int $limit = 6): Collection
    {
        return $this->paymentScope()
            ->with(['member:id,name', 'club:id,name'])
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, FeePayment>
     */
    public function pendingPayments(int $limit = 6): Collection
    {
        return $this->withinClubs(FeePayment::query())
            ->with(['member:id,name', 'club:id,name', 'collectedBy.user:id,name'])
            ->where('confirmation_status', ConfirmationStatus::PendingAdminConfirmation)
            ->orderBy('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Expense>
     */
    public function recentExpenses(int $limit = 6): Collection
    {
        return $this->expenseScope()
            ->with('club:id,name')
            ->where('status', ExpenseStatus::Completed)
            ->orderByDesc('expense_date')
            ->limit($limit)
            ->get();
    }

    /**
     * Members whose plan expires soon or who already owe money — the
     * follow-up list staff actually work from.
     *
     * @return Collection<int, MemberSubscription>
     */
    public function membersNeedingFollowUp(int $limit = 8): Collection
    {
        $today = Carbon::today($this->organisation->timezone);

        return $this->subscriptionScope()
            ->with(['member:id,name,phone', 'club:id,name', 'plan:id,name'])
            ->where('status', SubscriptionStatus::Active)
            ->where(fn (Builder $query) => $query
                ->whereBetween('end_date', [$today->toDateString(), $today->copy()->addDays(14)->toDateString()])
                ->orWhereColumn('amount_paid_minor', '<', 'amount_due_minor'))
            ->orderBy('end_date')
            ->limit($limit)
            ->get();
    }

    /**
     * @return array<int, array{tone: string, title: string, detail: string, route?: string}>
     */
    public function alerts(): array
    {
        $alerts = [];
        $today = Carbon::today($this->organisation->timezone);

        $pending = $this->pendingConfirmations();

        if ($pending > 0) {
            $alerts[] = [
                'tone' => 'caution',
                'title' => $pending.' '.str('payment')->plural($pending).' awaiting confirmation',
                'detail' => 'Worth '.$this->organisation->money($this->pendingConfirmationsValue()).' and not yet counted as revenue.',
                'route' => 'tenant.finance.confirmations',
            ];
        }

        $overdue = $this->subscriptionScope()
            ->where('status', SubscriptionStatus::Active)
            ->whereColumn('amount_paid_minor', '<', 'amount_due_minor')
            ->count();

        if ($overdue > 0) {
            $alerts[] = [
                'tone' => 'critical',
                'title' => $overdue.' '.str('plan')->plural($overdue).' with outstanding fees',
                'detail' => $this->organisation->money($this->outstandingFees()).' still owed across active plans.',
            ];
        }

        $expiring = $this->expiringSubscriptions(7);

        if ($expiring > 0) {
            $alerts[] = [
                'tone' => 'info',
                'title' => $expiring.' '.str('plan')->plural($expiring).' expiring within 7 days',
                'detail' => 'Renew these before they lapse.',
            ];
        }

        $overdueInvoices = $this->overdueInvoices();

        if ($overdueInvoices > 0) {
            $alerts[] = [
                'tone' => 'caution',
                'title' => $overdueInvoices.' '.str('invoice')->plural($overdueInvoices).' overdue',
                'detail' => $this->organisation->money($this->outstandingInvoices()).' still owed across open invoices.',
                'route' => 'tenant.billing.index',
            ];
        }

        $lapsed = $this->lapsedSubscriptions();

        if ($lapsed > 0) {
            $alerts[] = [
                'tone' => 'caution',
                'title' => $lapsed.' '.str('plan')->plural($lapsed).' already lapsed',
                'detail' => 'These terms ended and were never renewed. Filter the '
                    .strtolower($this->organisation->term('member_plural')).' list by "Expired" to see who.',
            ];
        }

        // Only meaningful where attendance is marked per club.
        $inactiveClubs = $this->organisation->usesClubs()
            ? $this->withinClubs(Club::query(), 'id')
                ->whereDoesntHave('attendances', fn (Builder $query) => $query
                    ->whereDate('attendance_date', '>=', $today->copy()->subDays(7)->toDateString()))
                ->count()
            : 0;

        if ($inactiveClubs > 0) {
            $alerts[] = [
                'tone' => 'caution',
                'title' => $inactiveClubs.' '.str('club')->plural($inactiveClubs).' with no attendance this week',
                'detail' => 'No attendance has been marked there in the last 7 days.',
            ];
        }

        return $alerts;
    }

    // --------------------------------------------------------------- scopes

    /**
     * @return Builder<FeePayment>
     */
    private function paymentScope(): Builder
    {
        // Columns are table-qualified because the revenue-by-plan and
        // revenue-by-account reports join tables that also carry `club_id`.
        return $this->withinClubs(FeePayment::query(), 'fee_payments.club_id')
            ->whereBetween('fee_payments.payment_date', [$this->period->from->toDateString(), $this->period->to->toDateString()]);
    }

    /**
     * @return Builder<Expense>
     */
    private function expenseScope(): Builder
    {
        return Expense::query()
            // Organisation-wide expenses have no club and belong to everyone
            // with finance access.
            ->when($this->clubIds !== null, fn (Builder $query) => $query
                ->where(fn (Builder $inner) => $inner->whereIn('club_id', $this->clubIds)->orWhereNull('club_id')))
            ->whereBetween('expense_date', [$this->period->from->toDateString(), $this->period->to->toDateString()]);
    }

    /**
     * @return Builder<Member>
     */
    private function memberScope(): Builder
    {
        return $this->withinClubs(Member::query(), 'primary_club_id');
    }

    /**
     * @return Builder<MemberSubscription>
     */
    private function subscriptionScope(): Builder
    {
        return $this->withinClubs(MemberSubscription::query());
    }

    /**
     * @return Builder<Attendance>
     */
    private function attendanceScope(AttendanceSubjectType $subjectType): Builder
    {
        return $this->withinClubs(Attendance::query())
            ->where('subject_type', $subjectType->value)
            ->whereBetween('attendance_date', [$this->period->from->toDateString(), $this->period->to->toDateString()]);
    }

    /**
     * Restricts a query to the permitted clubs; no restriction when the
     * scope is the whole organisation (admins, or the Clubs module off).
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     *
     * @param  Builder<TModel>  $query
     * @return Builder<TModel>
     */
    private function withinClubs(Builder $query, string $column = 'club_id'): Builder
    {
        return $this->clubIds === null ? $query : $query->whereIn($column, $this->clubIds);
    }

    // --------------------------------------------------------------- helpers

    /**
     * Bucket keys mapped to axis labels, pre-filled across the whole period.
     *
     * @return array<string, string>
     */
    private function buckets(): array
    {
        $buckets = [];
        $cursor = $this->period->from->copy();
        $byMonth = $this->period->grouping() === 'month';

        while ($cursor->lte($this->period->to)) {
            if ($byMonth) {
                $buckets[$cursor->format('Y-m')] = $cursor->format('M Y');
                $cursor->addMonthNoOverflow()->startOfMonth();

                continue;
            }

            $buckets[$cursor->toDateString()] = $cursor->format('d M');
            $cursor->addDay();
        }

        return $buckets;
    }

    /**
     * Returns a literal SQL fragment for the given date column. The column is
     * matched against a fixed allowlist rather than interpolated, so no
     * caller-supplied string can ever reach raw SQL.
     *
     * @return literal-string
     */
    private function bucketExpression(string $column): string
    {
        $byMonth = $this->period->grouping() === 'month';

        return match ($column) {
            'payment_date' => $byMonth ? "to_char(payment_date, 'YYYY-MM')" : "to_char(payment_date, 'YYYY-MM-DD')",
            'expense_date' => $byMonth ? "to_char(expense_date, 'YYYY-MM')" : "to_char(expense_date, 'YYYY-MM-DD')",
            'attendance_date' => $byMonth ? "to_char(attendance_date, 'YYYY-MM')" : "to_char(attendance_date, 'YYYY-MM-DD')",
            'joined_at' => $byMonth ? "to_char(joined_at, 'YYYY-MM')" : "to_char(joined_at, 'YYYY-MM-DD')",
            'updated_at' => $byMonth ? "to_char(updated_at, 'YYYY-MM')" : "to_char(updated_at, 'YYYY-MM-DD')",
            default => throw new InvalidArgumentException("Unsupported bucket column [{$column}]."),
        };
    }

    private function toMajor(int $minor): float
    {
        return round($minor / (10 ** Money::fractionDigits($this->organisation->currency_code)), 2);
    }
}
