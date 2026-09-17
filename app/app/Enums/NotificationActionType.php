<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Organisation;

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
     * Sent by hand from a member's page once their plan has run out — a
     * nudge to renew. Not composed by any action, so it has no switch.
     */
    case MemberPlanExpired = 'member_plan_expired';

    /**
     * A message written on the spot, or from one of the organisation's own
     * templates (CustomMessageTemplate). Its wording is whatever was chosen.
     */
    case CustomMessage = 'custom_message';

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
            self::MemberPlanExpired => 'Plan expired follow-up',
            self::CustomMessage => 'Custom message',
        };
    }

    /**
     * When the message is prepared — what the settings screen shows beside
     * the switch so the operator knows what they are turning on.
     */
    public function description(): string
    {
        return match ($this) {
            self::MemberCreated => 'When a member is added — a welcome with their member ID.',
            self::MemberProfileUpdated => 'When a member\'s details are edited.',
            self::MemberClubTransferred => 'When a member is moved to another club.',
            self::MemberPlanCreated => 'When a plan is started for a member.',
            self::MemberPlanRenewed => 'When a plan is renewed or resumed.',
            self::MemberPlanPaused => 'When a plan is paused.',
            self::MemberPlanCancelled => 'When a plan is cancelled.',
            self::MemberStatusChanged => 'When a member is removed or restored.',
            self::MemberAttendanceMarked => 'Every time a member is marked on the roster. Off by default — daily marking is a lot of messages.',
            self::FeePaymentConfirmed => 'When a payment is confirmed — the receipt, with its link.',
            self::UserInvited => 'When a staff member is invited.',
            self::UserProfileUpdated => 'When a staff member\'s details are edited.',
            self::UserClubAssignmentChanged => 'When a staff member\'s clubs change.',
            self::UserPermissionsChanged => 'When a staff member\'s role or permissions change.',
            self::UserStatusChanged => 'When a staff member is deactivated or reactivated.',
            self::UserAttendanceMarked => 'Every time a staff member is marked on the roster. Off by default.',
            self::PasswordResetLink => 'The one-time sign-in link an admin hands to a staff member. Always prepared.',
            self::InvoiceIssued => 'When an invoice is issued — with its link.',
            self::MemberPlanExpired => 'Sent by hand from a member\'s page when their plan has run out.',
            self::CustomMessage => 'A message written on the spot, or from one of your own templates.',
        };
    }

    /**
     * The settings heading each action sits under.
     */
    public function group(): string
    {
        return match ($this) {
            self::MemberCreated, self::MemberProfileUpdated, self::MemberClubTransferred, self::MemberStatusChanged => 'Members',
            self::MemberPlanCreated, self::MemberPlanRenewed, self::MemberPlanPaused, self::MemberPlanCancelled, self::MemberPlanExpired => 'Plans',
            self::CustomMessage => 'Custom',
            self::FeePaymentConfirmed, self::InvoiceIssued => 'Payments and invoices',
            self::MemberAttendanceMarked, self::UserAttendanceMarked => 'Attendance',
            self::UserInvited, self::UserProfileUpdated, self::UserClubAssignmentChanged,
            self::UserPermissionsChanged, self::UserStatusChanged, self::PasswordResetLink => 'Staff',
        };
    }

    /**
     * The module whose action prepares this message. With the module off
     * there is nothing to switch, so the action is not offered.
     */
    public function feature(): Feature
    {
        return match ($this) {
            self::MemberCreated, self::MemberProfileUpdated, self::MemberStatusChanged => Feature::Members,
            self::MemberClubTransferred => Feature::Clubs,
            self::MemberPlanCreated, self::MemberPlanRenewed, self::MemberPlanPaused, self::MemberPlanCancelled, self::MemberPlanExpired => Feature::Plans,
            self::CustomMessage => Feature::Messaging,
            self::FeePaymentConfirmed => Feature::Payments,
            self::InvoiceIssued => Feature::Billing,
            self::MemberAttendanceMarked => Feature::MemberAttendance,
            self::UserAttendanceMarked => Feature::StaffAttendance,
            self::UserInvited, self::UserProfileUpdated, self::UserClubAssignmentChanged,
            self::UserPermissionsChanged, self::UserStatusChanged, self::PasswordResetLink => Feature::Staff,
        };
    }

    /**
     * The actions an organisation can switch on or off, grouped, for the
     * modules it has — the non-optional handover is not among them.
     *
     * @return array<string, array<int, self>>
     */
    public static function configurableFor(Organisation $organisation): array
    {
        $groups = [];

        foreach (self::cases() as $case) {
            if ($case->isOptional() && $organisation->hasFeature($case->feature())) {
                $groups[$case->group()][] = $case;
            }
        }

        return $groups;
    }

    /**
     * Every action whose message the organisation can word, for the
     * template editor: the configurable ones plus the handover link.
     *
     * @return array<int, self>
     */
    public static function editableFor(Organisation $organisation): array
    {
        return array_values(array_filter(
            self::cases(),
            // A custom message has no fixed wording to edit.
            fn (self $case): bool => $case !== self::CustomMessage && $organisation->hasFeature($case->feature()),
        ));
    }

    /**
     * Whether the message is composed by hand rather than by an action.
     */
    public function isManual(): bool
    {
        return in_array($this, [self::MemberPlanExpired, self::CustomMessage], true);
    }

    /**
     * Whether the organisation may switch this action's messages off. An
     * operational handover is not a notification an operator opts out of.
     */
    public function isOptional(): bool
    {
        // A handover, and anything someone writes by hand, cannot be
        // switched off: they exist only because a person asked for them.
        return $this !== self::PasswordResetLink && ! $this->isManual();
    }
}
