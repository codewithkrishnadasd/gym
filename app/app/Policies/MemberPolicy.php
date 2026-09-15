<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Models\Member;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Members are the one resource staff can be granted explicit permission to
 * manage (MEP.md Section 4.2) — but only within clubs they're assigned to.
 * Archiving/restoring stays admin-only: it isn't in the staff-grantable
 * permission key list.
 */
class MemberPolicy
{
    use EvaluatesMembership;

    /**
     * Nothing here is permitted while the organisation has the Members module
     * switched off (App\Enums\Feature).
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Members) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'members.view');
    }

    public function view(User $user, Member $member): bool
    {
        return $this->viewAny($user) && $this->clubAllowed($user, $member->primary_club_id);
    }

    public function create(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'members.create');
    }

    public function update(User $user, Member $member): bool
    {
        return $this->isAdmin($user)
            || ($this->hasPermission($user, 'members.edit') && $this->clubAllowed($user, $member->primary_club_id));
    }

    public function transfer(User $user, Member $member): bool
    {
        return $this->isAdmin($user)
            || ($this->hasPermission($user, 'members.transfer') && $this->clubAllowed($user, $member->primary_club_id));
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
