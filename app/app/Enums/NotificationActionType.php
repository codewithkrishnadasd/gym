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

    /**
     * Hands over a one-time link rather than a password. Exempt from the
     * organisation's notification switches — see
     * Organisation::notificationsEnabled() — because an admin who has just
     * issued a link has to be able to pass it on.
     */
    case PasswordResetLink = 'password_reset_link';

    case InvoiceIssued = 'invoice_issued';

    /**
     * Plain-language name for filters and message lists, where the raw enum
     * value ("member_plan_renewed") is readable but not what an operator calls
     * the thing.
     */
    public function label(): string
    {
        return match ($this) {
            self::MemberCreated => 'Member added',
            self::MemberProfileUpdated => 'Member details updated',
            self::MemberClubTransferred => 'Member transferred',
            self::MemberPlanCreated => 'Plan started',
            self::MemberPlanRenewed => 'Plan renewed',
            self::MemberPlanPaused => 'Plan paused',
            self::MemberPlanCancelled => 'Plan cancelled',
            self::MemberStatusChanged => 'Member status changed',
            self::MemberAttendanceMarked => 'Member attendance',
            self::FeePaymentConfirmed => 'Payment confirmed',
            self::UserInvited => 'Staff invited',
            self::UserProfileUpdated => 'Staff details updated',
            self::UserClubAssignmentChanged => 'Staff clubs changed',
            self::UserPermissionsChanged => 'Staff permissions changed',
            self::UserStatusChanged => 'Staff status changed',
            self::UserAttendanceMarked => 'Staff attendance',
            self::PasswordResetLink => 'Password link',
            self::InvoiceIssued => 'Invoice issued',
        };
    }

    /**
     * Whether the organisation may switch this action's messages off. An
     * operational handover is not a notification an operator opts out of.
     */
    public function isOptional(): bool
    {
        return $this !== self::PasswordResetLink;
    }
}
