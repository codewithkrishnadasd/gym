<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Storage credentials are admin-only in every direction. There is no staff key
 * for this and there should not be: a bucket key can read every document the
 * organisation has ever stored, so handing out the ability to add, edit, or
 * repoint one would hand out the documents with it.
 */
class StorageBucketPolicy
{
    use EvaluatesMembership;

    /**
     * Nothing here is permitted while the organisation has the Documents module
     * switched off (App\Enums\Feature).
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Documents) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function manage(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
