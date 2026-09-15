<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Selling and changing a member's plan is an admin action. Staff who manage
 * members can see the plan history on the member's page but cannot start,
 * pause, or cancel a term.
 */
class MemberSubscriptionPolicy
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
        return $this->isAdmin($user) || $this->hasPermission($user, 'members.view');
    }

    public function view(User $user, MemberSubscription $subscription): bool
    {
        return $this->viewAny($user) && $this->clubAllowed($user, $subscription->club_id);
    }

    public function createFor(User $user, Member $member): bool
    {
        return $this->isAdmin($user) && $this->clubAllowed($user, $member->primary_club_id);
    }

    public function changeStatus(User $user, MemberSubscription $subscription): bool
    {
        return $this->isAdmin($user) && $this->clubAllowed($user, $subscription->club_id);
    }
}
