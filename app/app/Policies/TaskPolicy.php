<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Models\Task;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Tasks are a team tool: every active member of the organisation can raise
 * them. Admins see everything; staff see the tasks they reported and the ones
 * assigned to them. Only the category set-up is admin-only
 * (TaskCategoryPolicy).
 */
class TaskPolicy
{
    use EvaluatesMembership;

    /**
     * Nothing here is permitted while the organisation has the Tasks module
     * switched off (App\Enums\Feature).
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Tasks) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->membership($user) !== null;
    }

    public function view(User $user, Task $task): bool
    {
        $membership = $this->membership($user);

        return $membership !== null && ($membership->isAdmin() || $task->involves($membership));
    }

    public function create(User $user): bool
    {
        return $this->viewAny($user);
    }

    public function update(User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }

    public function delete(User $user, Task $task): bool
    {
        return $this->isAdmin($user) || $task->created_by === $this->membership($user)?->id;
    }
}
