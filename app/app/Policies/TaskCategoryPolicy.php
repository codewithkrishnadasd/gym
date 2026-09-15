<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

class TaskCategoryPolicy
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

    public function manage(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
