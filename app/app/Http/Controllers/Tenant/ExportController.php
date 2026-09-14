<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Enums\ClubAssignmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Club;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Support\Export\StreamedCsv;
use App\Support\Money;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use Generator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * CSV exports (MEP.md 6.11).
 *
 * Every export re-applies exactly the same club scoping and lifecycle rules
 * as the on-screen list it mirrors — an export must never be a way to read
 * rows the user cannot see in the UI (MEP.md 8.3).
 */
class ExportController extends Controller
{
    public function payments(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', FeePayment::class);

        [$organisation, $membership, $period, $clubIds] = $this->context($request);

        $query = FeePayment::query()
            ->with(['member:id,name,phone', 'club:id,name', 'collectedBy.user:id,name', 'financialAccount:id,name', 'subscription.plan:id,name'])
            ->whereIn('club_id', $clubIds)
            ->whereBetween('payment_date', [$period->from->toDateString(), $period->to->toDateString()])
            // A staff user exports only their own collections, exactly as the
            // ledger shows them.
            ->when(! $membership->isAdmin(), fn ($builder) => $builder->where('collected_by', $membership->id))
            ->when($request->string('status')->isNotEmpty(), fn ($builder) => $builder->where('confirmation_status', $request->string('status')))
            ->orderBy('payment_date');

        return StreamedCsv::respond(
            $this->filename($organisation, 'payments', $period),
            ['Payment ID', 'Date', $organisation->term('member_singular'), 'Phone', $organisation->term('club_singular'),
                'For', 'Method', 'Account', 'Reference', 'Amount ('.$organisation->currency_code.')', 'Discount ('.$organisation->currency_code.')', 'Status', 'Collected by', 'Confirmed at'],
            fn (): Generator => $this->paymentRows($query, $organisation),
            $this->preamble($organisation, 'Payments', $period, $clubIds),
        );
    }

    public function expenses(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', Expense::class);

        [$organisation, , $period, $clubIds] = $this->context($request);

        $query = Expense::query()
            ->with(['club:id,name', 'fundingAccount:id,name', 'createdBy.user:id,name'])
            ->where(fn ($builder) => $builder->whereIn('club_id', $clubIds)->orWhereNull('club_id'))
            ->whereBetween('expense_date', [$period->from->toDateString(), $period->to->toDateString()])
            ->orderBy('expense_date');

        return StreamedCsv::respond(
            $this->filename($organisation, 'expenses', $period),
            ['Expense ID', 'Date', 'Category', 'Description', 'Payee', $organisation->term('club_singular'),
                'Paid from', 'Amount ('.$organisation->currency_code.')', 'Status', 'Recorded by'],
            fn (): Generator => $this->expenseRows($query, $organisation),
            $this->preamble($organisation, 'Expenses', $period, $clubIds),
        );
    }

    public function members(Request $request): StreamedResponse
    {
        $this->authorize('viewAny', Member::class);

        [$organisation, , $period, $clubIds] = $this->context($request);

        $query = Member::query()
            ->with(['primaryClub:id,name'])
            ->whereIn('primary_club_id', $clubIds)
            ->orderBy('name');

        return StreamedCsv::respond(
            $this->filename($organisation, 'members', $period),
            [$organisation->term('member_singular').' ID', 'Name', 'WhatsApp number', $organisation->term('club_singular'),
                'Status', 'Joined', 'Date of birth', 'Gender'],
            fn (): Generator => $this->memberRows($query, $organisation),
            $this->preamble($organisation, $organisation->term('member_plural'), $period, $clubIds),
        );
    }

    /**
     * The aggregate report behind whichever tab the user is viewing.
     */
    public function report(Request $request): StreamedResponse
    {
        $this->authorize('viewReports', $this->tenant());

        [$organisation, , $period, $clubIds] = $this->context($request);

        $metrics = new OrganisationMetrics($organisation, $clubIds, $period);
        $report = $request->string('report')->toString() ?: 'finance';

        [$headings, $rows] = match ($report) {
            'members' => [
                ['Metric', 'Value'],
                $this->memberReportRows($metrics),
            ],
            'attendance' => [
                ['Metric', 'Value'],
                $this->attendanceReportRows($metrics),
            ],
            default => [
                ['Metric', 'Value'],
                $this->financeReportRows($metrics, $organisation),
            ],
        };

        return StreamedCsv::respond(
            $this->filename($organisation, $report.'-report', $period),
            $headings,
            fn (): Generator => yield from $rows,
            $this->preamble($organisation, ucfirst($report).' report', $period, $clubIds),
        );
    }

    /**
     * @param  Builder<FeePayment>  $query
     * @return Generator<int, array<int, string|int|float|null>>
     */
    private function paymentRows($query, Organisation $organisation): Generator
    {
        // lazy() keeps memory flat regardless of how many rows match.
        foreach ($query->lazy(500) as $payment) {
            /** @var Member $member */
            $member = $payment->member;
            /** @var Club $club */
            $club = $payment->club;

            yield [
                $organisation->reference('payment', $payment->id),
                $payment->payment_date->toDateString(),
                $member->name,
                $member->phone,
                $club->name,
                $payment->purposeLabel(),
                $payment->payment_method->label(),
                $payment->financialAccount->name ?? '',
                $payment->transaction_reference ?? '',
                Money::ofMinor($payment->amount_minor, $payment->currency_code)->formatPlain($organisation->locale),
                Money::ofMinor($payment->discount_minor, $payment->currency_code)->formatPlain($organisation->locale),
                $payment->confirmation_status->label(),
                $payment->collectedBy->user->name ?? '',
                $payment->confirmed_at?->toDateTimeString() ?? '',
            ];
        }
    }

    /**
     * @param  Builder<Expense>  $query
     * @return Generator<int, array<int, string|int|float|null>>
     */
    private function expenseRows($query, Organisation $organisation): Generator
    {
        foreach ($query->lazy(500) as $expense) {
            yield [
                $organisation->reference('expense', $expense->id),
                $expense->expense_date->toDateString(),
                $expense->category,
                $expense->description ?? '',
                $expense->payee ?? '',
                $expense->club->name ?? 'Organisation-wide',
                $expense->fundingAccount->name ?? '',
                Money::ofMinor($expense->amount_minor, $expense->currency_code)->formatPlain($organisation->locale),
                $expense->status->label(),
                $expense->createdBy->user->name ?? '',
            ];
        }
    }

    /**
     * @param  Builder<Member>  $query
     * @return Generator<int, array<int, string|int|float|null>>
     */
    private function memberRows($query, Organisation $organisation): Generator
    {
        foreach ($query->lazy(500) as $member) {
            yield [
                $organisation->reference('member', $member->id),
                $member->name,
                $member->phone,
                $member->primaryClub->name ?? '',
                $member->status->label(),
                $member->joined_at->toDateString(),
                $member->date_of_birth?->toDateString() ?? '',
                $member->gender ?? '',
            ];
        }
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function financeReportRows(OrganisationMetrics $metrics, Organisation $organisation): array
    {
        $rows = [
            ['Confirmed revenue', $organisation->money($metrics->revenueCollected())],
            ['Expenses (completed)', $organisation->money($metrics->expensesRecorded())],
            ['Net movement', $organisation->money($metrics->netMovement())],
            ['Outstanding fees', $organisation->money($metrics->outstandingFees())],
            ['Average confirmation turnaround (hours)', (string) ($metrics->confirmationTurnaroundHours() ?? '—')],
            ['', ''],
            ['Payment lifecycle', 'Value'],
        ];

        foreach ($metrics->paymentStatusTotals() as $status => $totals) {
            $rows[] = [ucfirst(str_replace('_', ' ', $status)).' ('.$totals['count'].')', $organisation->money($totals['total'])];
        }

        foreach ([
            'Revenue by method' => $metrics->paymentMethodSplit(),
            'Revenue by plan' => $metrics->revenueByPlan(),
            'Revenue by account' => $metrics->revenueByAccount(),
            'Expenses by category' => $metrics->expenseCategorySplit(),
        ] as $title => $split) {
            $rows[] = ['', ''];
            $rows[] = [$title, 'Value'];

            foreach ($split as $label => $value) {
                $rows[] = [ucfirst(str_replace('_', ' ', (string) $label)), $organisation->money($value)];
            }
        }

        $rows[] = ['', ''];
        $rows[] = ['Club', 'Revenue / Expenses / Members / Attendance'];

        foreach ($metrics->clubComparison() as $club) {
            $rows[] = [
                $club->club,
                implode(' / ', [
                    $organisation->money($club->revenue),
                    $organisation->money($club->expenses),
                    (string) $club->members,
                    (string) $club->attendance,
                ]),
            ];
        }

        return $rows;
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function memberReportRows(OrganisationMetrics $metrics): array
    {
        $rows = [
            ['Active members', (string) $metrics->activeMembers()],
            ['Joined this period', (string) $metrics->newMembers()],
            ['Plans expiring within 30 days', (string) $metrics->expiringSubscriptions()],
            ['', ''],
            ['Status', 'Count'],
        ];

        foreach ($metrics->memberStatusSplit() as $status => $count) {
            $rows[] = [ucfirst((string) $status), (string) $count];
        }

        return $rows;
    }

    /**
     * @return array<int, array<int, string>>
     */
    private function attendanceReportRows(OrganisationMetrics $metrics): array
    {
        $rows = [
            ['Attendance rate', $metrics->attendanceRate().'%'],
            ['', ''],
            ['Action', 'Count'],
        ];

        foreach ($metrics->attendanceActionSplit() as $action => $count) {
            $rows[] = [ucfirst((string) $action), (string) $count];
        }

        $rows[] = ['', ''];
        $rows[] = ['Club', 'Present marks'];

        foreach ($metrics->clubComparison() as $club) {
            $rows[] = [$club->club, (string) $club->attendance];
        }

        return $rows;
    }

    /**
     * @return array{0: Organisation, 1: OrganisationUser, 2: ReportPeriod, 3: array<int, int>}
     */
    private function context(Request $request): array
    {
        $organisation = $this->tenant();

        /** @var User $user */
        $user = $request->user();

        /** @var OrganisationUser $membership */
        $membership = app()->bound('membership')
            ? app('membership')
            : $user->membershipFor($organisation);

        $period = ReportPeriod::fromStrings(
            $request->string('from')->toString() ?: null,
            $request->string('to')->toString() ?: null,
            $organisation->timezone,
        );

        $available = $membership->isAdmin()
            ? Club::query()->pluck('id')->all()
            : $membership->clubAssignments()->where('status', ClubAssignmentStatus::Active)->pluck('club_id')->all();

        $requested = $request->integer('club');
        $clubIds = $requested > 0 && in_array($requested, $available, true) ? [$requested] : $available;

        return [$organisation, $membership, $period, $clubIds];
    }

    private function tenant(): Organisation
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return $organisation;
    }

    private function filename(Organisation $organisation, string $subject, ReportPeriod $period): string
    {
        return str($organisation->slug.'-'.$subject.'-'.$period->from->toDateString().'-to-'.$period->to->toDateString())
            ->slug()
            ->append('.csv')
            ->toString();
    }

    /**
     * Context lines so a downloaded file is self-describing (MEP.md 6.11).
     *
     * @param  array<int, int>  $clubIds
     * @return array<int, string>
     */
    private function preamble(Organisation $organisation, string $title, ReportPeriod $period, array $clubIds): array
    {
        $clubNames = Club::query()->whereIn('id', $clubIds)->orderBy('name')->pluck('name')->implode(', ');

        return [
            $organisation->name.' — '.$title,
            'Period: '.$period->label(),
            'Scope: '.($clubNames !== '' ? $clubNames : 'No clubs in scope'),
            'Generated: '.now($organisation->timezone)->format('d M Y H:i').' ('.$organisation->timezone.')',
            'Note: only confirmed payments and completed expenses contribute to financial totals.',
        ];
    }
}
