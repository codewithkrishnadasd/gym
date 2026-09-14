<?php

declare(strict_types=1);

namespace App\Policies;

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
