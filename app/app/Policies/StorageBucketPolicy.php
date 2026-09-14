<?php

declare(strict_types=1);

namespace App\Policies;

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

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function manage(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
