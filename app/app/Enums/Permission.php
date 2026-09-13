<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The single source of truth for staff permission keys stored as booleans in
 * `organisation_users.permissions` (MEP.md Section 4.2). Admins bypass every
 * key via `OrganisationUser::hasPermission()`; finance administration,
 * settings, expenses, audit, and global reporting stay admin-only and
 * therefore intentionally have no key here.
 */
enum Permission: string
{
    case MembersView = 'members.view';
    case MembersCreate = 'members.create';
    case MembersEdit = 'members.edit';
    case MembersTransfer = 'members.transfer';
    case AttendanceMemberMark = 'attendance.member.mark';
    case AttendanceStaffMark = 'attendance.staff.mark';
    case FeesCollect = 'fees.collect';
    case FeesViewOwn = 'fees.view_own';
    case ClubsViewAssigned = 'clubs.view_assigned';
    case ReportsViewAssigned = 'reports.view_assigned';
    case NotificationsSend = 'notifications.send';

    public function label(): string
    {
        return match ($this) {
            self::MembersView => 'View members',
            self::MembersCreate => 'Create members',
            self::MembersEdit => 'Edit members',
            self::MembersTransfer => 'Transfer members between clubs',
            self::AttendanceMemberMark => 'Mark member attendance',
            self::AttendanceStaffMark => 'Mark staff attendance',
            self::FeesCollect => 'Collect fees (submitted for admin confirmation)',
            self::FeesViewOwn => 'View own collections',
            self::ClubsViewAssigned => 'View assigned clubs',
            self::ReportsViewAssigned => 'View reports for assigned clubs',
            self::NotificationsSend => 'Send WhatsApp messages to members and staff',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::MembersView, self::MembersCreate, self::MembersEdit, self::MembersTransfer => 'Members',
            self::AttendanceMemberMark, self::AttendanceStaffMark => 'Attendance',
            self::FeesCollect, self::FeesViewOwn => 'Fees',
            self::ClubsViewAssigned, self::ReportsViewAssigned => 'Clubs and reports',
            self::NotificationsSend => 'Messaging',
        };
    }

    /**
     * @return array<string, array<int, self>>
     */
    public static function grouped(): array
    {
        $groups = [];

        foreach (self::cases() as $case) {
            $groups[$case->group()][] = $case;
        }

        return $groups;
    }

    /**
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_map(static fn (self $case): string => $case->value, self::cases());
    }
}
