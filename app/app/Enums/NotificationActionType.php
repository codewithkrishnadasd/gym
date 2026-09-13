<?php

declare(strict_types=1);

namespace App\Enums;

enum NotificationActionType: string
{
    case MemberCreated = 'member_created';
    case MemberProfileUpdated = 'member_profile_updated';
    case MemberClubTransferred = 'member_club_transferred';
    case MemberPlanCreated = 'member_plan_created';
    case MemberPlanRenewed = 'member_plan_renewed';
    case MemberPlanPaused = 'member_plan_paused';
    case MemberPlanCancelled = 'member_plan_cancelled';
    case MemberStatusChanged = 'member_status_changed';
    case MemberAttendanceMarked = 'member_attendance_marked';
    case FeePaymentConfirmed = 'fee_payment_confirmed';
    case UserInvited = 'user_invited';
    case UserProfileUpdated = 'user_profile_updated';
    case UserClubAssignmentChanged = 'user_club_assignment_changed';
    case UserPermissionsChanged = 'user_permissions_changed';
    case UserStatusChanged = 'user_status_changed';
    case UserAttendanceMarked = 'user_attendance_marked';
}
