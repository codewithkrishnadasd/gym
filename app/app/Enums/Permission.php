<?php

declare(strict_types=1);

namespace App\Enums;

use App\Models\Organisation;

/**
 * The single source of truth for staff permission keys stored as booleans in
 * `organisation_users.permissions` (MEP.md Section 4.2). Admins bypass every
 * key via `OrganisationUser::hasPermission()`; finance administration,
 * settings, expenses, audit, and global reporting stay admin-only and
 * therefore intentionally have no key here.
 *
 * Some permissions are meaningless on their own — editing a member you cannot
 * see, or collecting a fee you cannot then find. Those dependencies are
 * declared in `requires()` and enforced everywhere a permission set is read or
 * written, so a granted permission always comes with what it needs to work.
 */
enum Permission: string
{
    case MembersView = 'members.view';
    case MembersCreate = 'members.create';
    case MembersEdit = 'members.edit';
    case MembersTransfer = 'members.transfer';
    case StaffView = 'staff.view';
    case AttendanceMemberMark = 'attendance.member.mark';
    case AttendanceStaffMark = 'attendance.staff.mark';
    case FeesCollect = 'fees.collect';
    case FeesViewOwn = 'fees.view_own';
    case BillingView = 'billing.view';
    case BillingCreate = 'billing.create';
    case DocumentsView = 'documents.view';
    case DocumentsManage = 'documents.manage';
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
            self::StaffView => 'View other staff',
            self::AttendanceMemberMark => 'Mark member attendance',
            self::AttendanceStaffMark => 'Mark staff attendance',
            self::FeesCollect => 'Collect fees (submitted for admin confirmation)',
            self::FeesViewOwn => 'View own collections',
            self::BillingView => 'View invoices',
            self::BillingCreate => 'Create invoices for members',
            self::DocumentsView => 'View member and staff documents',
            self::DocumentsManage => 'Upload and remove documents',
            self::ClubsViewAssigned => 'View assigned clubs',
            self::ReportsViewAssigned => 'View reports for assigned clubs',
            self::NotificationsSend => 'Send WhatsApp messages (see the message preview after an action)',
        };
    }

    /**
     * Permissions that must be granted alongside this one for it to mean
     * anything. Granting the dependent grants these too — they are not
     * suggestions, and the set is closed transitively before it is stored or
     * read.
     *
     * @return array<int, self>
     */
    public function requires(): array
    {
        return match ($this) {
            // Acting on a member you cannot look up is not a workable
            // permission: every one of these starts from the member list.
            self::MembersCreate,
            self::MembersEdit,
            self::MembersTransfer => [self::MembersView],

            // A collector who cannot see their own submissions has no way to
            // tell what an admin has confirmed or rejected.
            self::FeesCollect => [self::FeesViewOwn],

            // Uploading a document you cannot then see back is a one-way
            // action with no way to check what you attached.
            self::DocumentsManage => [self::DocumentsView],

            // An invoice is raised against a member and has to be found again
            // afterwards, so creating one needs both the member list and the
            // invoice list.
            self::BillingCreate => [self::BillingView, self::MembersView],

            default => [],
        };
    }

    /**
     * The module this permission belongs to. A permission for a module the
     * organisation has switched off is not offered, and granting it would
     * mean nothing since the module's policies deny everything anyway.
     */
    public function feature(): Feature
    {
        return match ($this) {
            self::MembersView, self::MembersCreate, self::MembersEdit => Feature::Members,
            self::MembersTransfer, self::ClubsViewAssigned => Feature::Clubs,
            self::StaffView => Feature::Staff,
            self::AttendanceMemberMark => Feature::MemberAttendance,
            self::AttendanceStaffMark => Feature::StaffAttendance,
            self::FeesCollect, self::FeesViewOwn => Feature::Payments,
            self::BillingView, self::BillingCreate => Feature::Billing,
            self::DocumentsView, self::DocumentsManage => Feature::Documents,
            self::ReportsViewAssigned => Feature::Reports,
            self::NotificationsSend => Feature::Messaging,
        };
    }

    /**
     * The grantable permissions for an organisation, grouped, leaving out
     * every module it has switched off.
     *
     * @return array<string, array<int, self>>
     */
    public static function groupedFor(Organisation $organisation): array
    {
        $groups = [];

        foreach (self::cases() as $case) {
            if ($organisation->hasFeature($case->feature())) {
                $groups[$case->group()][] = $case;
            }
        }

        return $groups;
    }

    public function group(): string
    {
        return match ($this) {
            self::MembersView, self::MembersCreate, self::MembersEdit, self::MembersTransfer => 'Members',
            self::StaffView => 'Staff',
            self::AttendanceMemberMark, self::AttendanceStaffMark => 'Attendance',
            self::FeesCollect, self::FeesViewOwn => 'Fees',
            self::BillingView, self::BillingCreate => 'Billing',
            self::DocumentsView, self::DocumentsManage => 'Documents',
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

    /**
     * Adds every prerequisite of every granted key, following chains until
     * nothing new appears. Unknown keys are dropped, so a permission removed
     * from this enum cannot linger in stored data and be read back as a grant.
     *
     * @param  array<int, string>  $granted
     * @return array<int, string>
     */
    public static function expand(array $granted): array
    {
        /** @var array<string, true> $resolved */
        $resolved = [];

        /** @var array<int, self> $queue */
        $queue = array_values(array_filter(array_map(
            static fn (string $key): ?self => self::tryFrom($key),
            $granted,
        )));

        while ($queue !== []) {
            $permission = array_shift($queue);

            if (isset($resolved[$permission->value])) {
                continue;
            }

            $resolved[$permission->value] = true;

            foreach ($permission->requires() as $prerequisite) {
                $queue[] = $prerequisite;
            }
        }

        // Enum order rather than the order they were granted, so two equal
        // permission sets always store identically and audit diffs stay clean.
        return array_values(array_filter(
            self::keys(),
            static fn (string $key): bool => isset($resolved[$key]),
        ));
    }

    /**
     * The stored shape: every key present, expanded grants set to true.
     *
     * @param  array<int, string>  $granted
     * @return array<string, bool>
     */
    public static function map(array $granted): array
    {
        $expanded = self::expand($granted);

        /** @var array<string, bool> $map */
        $map = [];

        foreach (self::keys() as $key) {
            $map[$key] = in_array($key, $expanded, true);
        }

        return $map;
    }

    /**
     * Which of the given permissions depend on this one — used to explain why
     * a checkbox cannot be unticked while something else is on.
     *
     * @param  array<int, string>  $granted
     * @return array<int, self>
     */
    public function requiredBy(array $granted): array
    {
        $dependents = [];

        foreach (self::cases() as $case) {
            if ($case === $this || ! in_array($case->value, $granted, true)) {
                continue;
            }

            if (in_array($this->value, self::expand([$case->value]), true)) {
                $dependents[] = $case;
            }
        }

        return $dependents;
    }
}
