<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\AttendanceAction;
use App\Enums\AttendanceSubjectType;
use App\Enums\ConfirmationStatus;
use App\Enums\ExpenseStatus;
use App\Enums\MemberStatus;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationDailyMetric;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Pre-computes `organisation_daily_metrics` so dashboards for large tenants
 * do not aggregate the whole ledger on every page load (MEP.md 8.4).
 *
 * Rollups are a reporting accelerator, never the source of truth — every
 * figure here can be recomputed from the underlying tables, which is exactly
 * what re-running this command for a past date does.
 *
 * There is no resolved tenant in a console context, so every query
 * deliberately bypasses `OrganisationScope` and filters by organisation
 * explicitly (technology.md 2.2).
 */
#[AsCommand(name: 'metrics:roll-up', description: 'Roll up daily organisation metrics for dashboards and reports')]
class RollUpDailyMetrics extends Command
{
    protected $signature = 'metrics:roll-up
        {--date= : The date to roll up (defaults to yesterday in each organisation\'s timezone)}
        {--days=1 : Number of consecutive days to roll up, counting backwards}
        {--organisation= : Limit to a single organisation ID}';

    public function handle(): int
    {
        $organisations = Organisation::query()
            ->when($this->option('organisation'), fn ($query) => $query->whereKey($this->option('organisation')))
            ->get();

        if ($organisations->isEmpty()) {
            $this->components->warn('No organisations to roll up.');

            return self::SUCCESS;
        }

        $days = max(1, (int) $this->option('days'));
        $rows = 0;

        foreach ($organisations as $organisation) {
            $anchor = $this->option('date')
                ? Carbon::parse((string) $this->option('date'), $organisation->timezone)
                : Carbon::yesterday($organisation->timezone);

            for ($offset = 0; $offset < $days; $offset++) {
                $this->rollUp($organisation, $anchor->copy()->subDays($offset));
                $rows++;
            }

            $this->components->info("Rolled up {$days} ".str('day')->plural($days)." for {$organisation->name}.");
        }

        $this->components->info("{$rows} metric ".str('row')->plural($rows).' written.');

        return self::SUCCESS;
    }

    private function rollUp(Organisation $organisation, Carbon $date): void
    {
        $day = $date->toDateString();

        $revenue = (int) FeePayment::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->whereDate('payment_date', $day)
            ->sum('amount_minor');

        $expenses = (int) Expense::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('status', ExpenseStatus::Completed)
            ->whereDate('expense_date', $day)
            ->sum('amount_minor');

        $attendance = Attendance::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('subject_type', AttendanceSubjectType::Member->value)
            ->whereDate('attendance_date', $day)
            ->whereIn('action', [AttendanceAction::Present, AttendanceAction::Late])
            ->count();

        $members = Member::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id);

        OrganisationDailyMetric::query()->withoutGlobalScopes()->updateOrCreate(
            ['organisation_id' => $organisation->id, 'metric_date' => $day],
            [
                'total_members' => $members->clone()->whereDate('joined_at', '<=', $day)->count(),
                'new_members' => $members->clone()->whereDate('joined_at', $day)->count(),
                'active_members' => $members->clone()->where('status', MemberStatus::Active)->count(),
                'attendance_present' => $attendance,
                'revenue_collected_minor' => $revenue,
                'expenses_recorded_minor' => $expenses,
                'net_movement_minor' => $revenue - $expenses,
                'by_club' => $this->byClub($organisation, $day),
                'updated_at' => now(),
            ],
        );
    }

    /**
     * @return array<string, array{revenue: int, attendance: int}>
     */
    private function byClub(Organisation $organisation, string $day): array
    {
        $revenue = FeePayment::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->whereDate('payment_date', $day)
            ->toBase()
            ->selectRaw('club_id, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('club_id')
            ->pluck('total', 'club_id');

        $attendance = Attendance::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('subject_type', AttendanceSubjectType::Member->value)
            ->whereDate('attendance_date', $day)
            ->whereIn('action', [AttendanceAction::Present, AttendanceAction::Late])
            ->toBase()
            ->selectRaw('club_id, COUNT(*) AS total')
            ->groupBy('club_id')
            ->pluck('total', 'club_id');

        $byClub = [];

        foreach ($revenue->keys()->merge($attendance->keys())->unique() as $clubId) {
            $byClub[(string) $clubId] = [
                'revenue' => (int) ($revenue[$clubId] ?? 0),
                'attendance' => (int) ($attendance[$clubId] ?? 0),
            ];
        }

        return $byClub;
    }
}
