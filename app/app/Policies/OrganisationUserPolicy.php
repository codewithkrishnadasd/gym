<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Permission;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Staff *management* — inviting, editing, suspending, issuing reset links —
 * stays admin-only (MEP.md Section 6.5). Seeing who else works here is
 * separately grantable via `staff.view`, because a staff member who can mark
 * staff attendance or message a colleague has to be able to find them.
 */
class OrganisationUserPolicy
{
    use EvaluatesMembership;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, Permission::StaffView->value);
    }

    public function view(User $user, OrganisationUser $organisationUser): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function update(User $user, OrganisationUser $organisationUser): bool
    {
        return $this->isAdmin($user);
    }

    /**
     * Issuing a password reset link. Separate from `update` because it hands
     * someone a way into an account rather than editing a record, and because
     * an admin must not be able to issue one for themselves — that would turn
     * a hijacked admin session into a permanent credential.
     */
    public function issuePasswordResetLink(User $user, OrganisationUser $organisationUser): bool
    {
        return $this->isAdmin($user) && $organisationUser->user_id !== $user->id;
    }
}
