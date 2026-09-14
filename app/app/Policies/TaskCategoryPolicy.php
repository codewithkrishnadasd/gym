<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

class TaskCategoryPolicy
{
    use EvaluatesMembership;

    public function manage(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
