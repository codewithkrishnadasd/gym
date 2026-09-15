<?php

declare(strict_types=1);

namespace App\Policies\Concerns;

use App\Enums\ClubAssignmentStatus;
use App\Enums\Feature;
use App\Models\Organisation;
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

    /**
     * Whether the resolved organisation has a module switched on. Policies
     * for a module's records answer false to everything when it is off, so
     * a stale link, a bookmarked Livewire page, or a button left in a view
     * all fail closed.
     */
    protected function featureEnabled(Feature $feature): bool
    {
        if (! app()->bound('tenant')) {
            return false;
        }

        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return $organisation->hasFeature($feature);
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
