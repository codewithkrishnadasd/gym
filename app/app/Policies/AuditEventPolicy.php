<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * The audit log is admin-only, and there is intentionally no create, update,
 * or delete ability for anyone — the table is append-only and written solely
 * by Action classes (MEP.md 5.13).
 */
class AuditEventPolicy
{
    use EvaluatesMembership;

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user);
    }
}
