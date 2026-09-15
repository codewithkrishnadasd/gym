<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\ExpenseStatus;
use App\Enums\Feature;
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

    /**
     * Nothing here is permitted while the organisation has the Expenses module
     * switched off (App\Enums\Feature).
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Expenses) ? null : false;
    }

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
