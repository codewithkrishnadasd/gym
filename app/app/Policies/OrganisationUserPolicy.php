<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\OrganisationUser;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Staff/user management is an admin-only surface in the first release
 * (MEP.md Section 6.5) — there is no staff-grantable permission key for it.
 */
class OrganisationUserPolicy
{
    use EvaluatesMembership;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function view(User $user, OrganisationUser $organisationUser): bool
    {
        return $this->isAdmin($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, OrganisationUser $organisationUser): bool
    {
        return $this->isAdmin($user);
    }
}
