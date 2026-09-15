<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\AttendanceSubjectType;
use App\Enums\Feature;
use App\Models\User;
use App\Policies\Concerns\EvaluatesMembership;

/**
 * Member and staff attendance are separately permissioned (MEP.md 4.2, 5.9):
 * a staff user may be trusted to mark the member roster without being able to
 * mark their own colleagues' attendance. Admins may mark both.
 */
class AttendancePolicy
{
    use EvaluatesMembership;

    /**
     * Nothing here is permitted while the organisation has the Attendance
     * module switched off. Each roster additionally needs the module for the
     * people on it — Members or Staff — since a roster of nobody is no roster.
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::Attendance) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->markMembers($user) || $this->markStaff($user);
    }

    public function markMembers(User $user, ?int $clubId = null): bool
    {
        return $this->featureEnabled(Feature::Members)
            && ($this->isAdmin($user) || $this->hasPermission($user, 'attendance.member.mark'))
            && $this->clubAllowed($user, $clubId);
    }

    public function markStaff(User $user, ?int $clubId = null): bool
    {
        return $this->featureEnabled(Feature::Staff)
            && ($this->isAdmin($user) || $this->hasPermission($user, 'attendance.staff.mark'))
            && $this->clubAllowed($user, $clubId);
    }

    public function mark(User $user, AttendanceSubjectType $subjectType, ?int $clubId = null): bool
    {
        return $subjectType === AttendanceSubjectType::Member
            ? $this->markMembers($user, $clubId)
            : $this->markStaff($user, $clubId);
    }
}
