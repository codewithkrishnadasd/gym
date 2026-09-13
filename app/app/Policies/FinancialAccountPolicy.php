<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Bank/UPI account management is admin-only in the first release
 * (MEP.md 4.2, 6.9). Staff who collect fees may need to name the receiving
 * account on a submission, which `select` allows without exposing the
 * management surface.
 */
class FinancialAccountPolicy
{
    use EvaluatesMembership;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function select(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'fees.collect');
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function archive(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
