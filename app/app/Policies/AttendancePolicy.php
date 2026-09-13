<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\AttendanceSubjectType;
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

    public function viewAny(User $user): bool
    {
        return $this->isAdmin($user)
            || $this->hasPermission($user, 'attendance.member.mark')
            || $this->hasPermission($user, 'attendance.staff.mark');
    }

    public function markMembers(User $user, ?int $clubId = null): bool
    {
        return ($this->isAdmin($user) || $this->hasPermission($user, 'attendance.member.mark'))
            && $this->clubAllowed($user, $clubId);
    }

    public function markStaff(User $user, ?int $clubId = null): bool
    {
        return ($this->isAdmin($user) || $this->hasPermission($user, 'attendance.staff.mark'))
            && $this->clubAllowed($user, $clubId);
    }

    public function mark(User $user, AttendanceSubjectType $subjectType, ?int $clubId = null): bool
    {
        return $subjectType === AttendanceSubjectType::Member
            ? $this->markMembers($user, $clubId)
            : $this->markStaff($user, $clubId);
    }
}
