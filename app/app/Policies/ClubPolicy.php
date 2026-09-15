<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Models\Club;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Club creation, editing, archiving, and restoring are organisation-admin
 * operations (MEP.md Section 4.1). Staff may only view clubs they're
 * assigned to, via the `clubs.view_assigned` permission.
 */
class ClubPolicy
{
    use EvaluatesMembership;

    /**
     * Nothing here is permitted while the organisation has the Clubs module
     * switched off (App\Enums\Feature).
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Clubs) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'clubs.view_assigned');
    }

    public function view(User $user, Club $club): bool
    {
        return $this->viewAny($user) && $this->clubAllowed($user, $club->id);
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

    public function restore(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
