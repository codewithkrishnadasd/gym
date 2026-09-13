<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ExpenseStatus;
use App\Models\Expense;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Expenses are admin-only (MEP.md 4.2, 6.10). A completed expense is a
 * financial record: it can be reversed with a reason but never edited or
 * deleted.
 */
class ExpensePolicy
{
    use EvaluatesMembership;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, Expense $expense): bool
    {
        return $this->isAdmin($user) && $expense->status === ExpenseStatus::Completed;
    }

    public function reverse(User $user, Expense $expense): bool
    {
        return $this->isAdmin($user) && $expense->status === ExpenseStatus::Completed;
    }
}
