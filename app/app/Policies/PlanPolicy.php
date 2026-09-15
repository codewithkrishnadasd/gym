<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Plans set prices, so the plan management surface is admin-only
 * (MEP.md 4.2). Staff who collect fees still need to *read* the plan list to
 * pick one, which is a separate, narrower ability — reading a price is not
 * permission to change it.
 */
class PlanPolicy
{
    use EvaluatesMembership;

    /**
     * Nothing here is permitted while the organisation has the Plans module
     * switched off (App\Enums\Feature).
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Plans) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function select(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->hasPermission($user, 'fees.collect')
            || $this->hasPermission($user, 'members.view');
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
