<?php

declare(strict_types=1);

namespace App\Actions\Expenses;

use App\Enums\ExpenseStatus;
use App\Exceptions\LifecycleViolation;
use App\Models\AuditEvent;
use App\Models\Expense;
use App\Models\OrganisationUser;
use Illuminate\Support\Facades\DB;

/**
 * Reverses a completed expense with a reason. Like payment reversal, the
 * original record is preserved and excluded from current totals rather than
 * deleted (MEP.md 6.10, 13.23).
 */
final class ReverseExpense
{
    public function handle(Expense $expense, OrganisationUser $actor, string $reason): Expense
    {
        return DB::transaction(function () use ($expense, $actor, $reason): Expense {
            /** @var Expense $locked */
            $locked = Expense::query()->whereKey($expense->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->status !== ExpenseStatus::Completed) {
                throw LifecycleViolation::expense($locked->status->value);
            }

            $locked->update(['status' => ExpenseStatus::Reversed]);

            AuditEvent::record(
                $locked,
                'expense.reversed',
                $actor,
                ['status' => ExpenseStatus::Completed->value],
                ['status' => ExpenseStatus::Reversed->value],
                ['reason' => $reason, 'amount_minor' => $locked->amount_minor],
            );

            return $locked;
        });
    }
}
