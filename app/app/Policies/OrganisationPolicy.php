<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\Feature;
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

    /**
     * Opening the WhatsApp link and recording the result. Separate from
     * `manageSettings` so a staff user can send the messages their own actions
     * produce without gaining access to organisation configuration.
     */
    public function sendNotifications(User $user): bool
    {
        return $this->isAdmin($user) || $this->hasPermission($user, 'notifications.send');
    }

    public function viewReports(User $user): bool
    {
        return $this->featureEnabled(Feature::Reports)
            && ($this->isAdmin($user) || $this->hasPermission($user, 'reports.view_assigned'));
    }

    public function exportReports(User $user): bool
    {
        return $this->viewReports($user);
    }
}
