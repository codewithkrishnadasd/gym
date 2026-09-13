<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Tenant-side organisation settings — branding, contact details, timezone,
 * currency, terminology, and notification preferences — are organisation-admin
 * operations (MEP.md 4.1). Creating or suspending the organisation itself
 * stays a platform operation on a separate guard entirely (MEP.md 3.3).
 */
class OrganisationPolicy
{
    use EvaluatesMembership;

    public function manageSettings(User $user): bool
    {
        return $this->isAdmin($user);
    }

    public function viewReports(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'reports.view_assigned');
    }

    public function exportReports(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'reports.view_assigned');
    }
}
