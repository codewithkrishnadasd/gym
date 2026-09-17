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
     * Nothing here is permitted while neither roster's module is on. Each
     * roster is its own module — Member attendance, Staff attendance — and
     * each also needs the people it is a roster of.
     */
    public function before(User $user): ?bool
    {
        return $this->featureEnabled(Feature::MemberAttendance) || $this->featureEnabled(Feature::StaffAttendance) ? null : false;
    }

    public function viewAny(User $user): bool
    {
        return $this->markMembers($user) || $this->markStaff($user);
    }

    public function markMembers(User $user, ?int $clubId = null): bool
    {
        return $this->featureEnabled(Feature::MemberAttendance) && $this->featureEnabled(Feature::Members)
            && ($this->isAdmin($user) || $this->hasPermission($user, 'attendance.member.mark'))
            && $this->clubAllowed($user, $clubId);
    }

    public function markStaff(User $user, ?int $clubId = null): bool
    {
        return $this->featureEnabled(Feature::StaffAttendance) && $this->featureEnabled(Feature::Staff)
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
