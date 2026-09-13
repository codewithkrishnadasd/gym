<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Enums\ClubAssignmentStatus;
use App\Models\OrganisationUser;
use App\Models\User;

/**
 * Every tenant policy authorizes against the resolved organisation's
 * `organisation_users` row for the acting user, never the `users` row
 * alone (MEP.md Section 4.3). `EnsureActiveMembership` binds it into the
 * container as `membership` once per request.
 */
trait EvaluatesMembership
{
    protected function membership(User $user): ?OrganisationUser
    {
        if (app()->bound('membership')) {
            return app('membership');
        }

        return app()->bound('tenant') ? $user->membershipFor(app('tenant')) : null;
    }

    protected function isAdmin(User $user): bool
    {
        return $this->membership($user)?->isAdmin() ?? false;
    }

    protected function hasPermission(User $user, string $key): bool
    {
        return $this->membership($user)?->hasPermission($key) ?? false;
    }

    /**
     * Whether the acting user (if not an admin) has an active assignment to
     * the given club — the source of truth is always `club_user_assignments`
     * with status active, never the denormalised `club_ids` column.
     */
    protected function clubAllowed(User $user, ?int $clubId): bool
    {
        if ($clubId === null) {
            return true;
        }

        $membership = $this->membership($user);

        if (! $membership) {
            return false;
        }

        if ($membership->isAdmin()) {
            return true;
        }

        return $membership->clubAssignments()
            ->where('club_id', $clubId)
            ->where('status', ClubAssignmentStatus::Active)
            ->exists();
    }
}
