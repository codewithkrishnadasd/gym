<?php

declare(strict_types=1);

namespace App\Enums;

/**
 * The modules a platform admin can switch on for an organisation. What is
 * not switched on is absent for that organisation: no navigation, routes
 * answer 404, policies deny, and every screen that would have shown the
 * module's data skips it.
 *
 * Modules lean on each other — a fee is collected from a member into an
 * account — so a module that cannot exist without another declares it in
 * `requires()`, and enabling one enables what it needs. Everything else is a
 * soft link: a member page without Plans has no plans tab, a payment form
 * without Invoices offers no invoice to pay, and both keep working.
 */
enum Feature: string
{
    case Members = 'members';
    case Clubs = 'clubs';
    case Staff = 'staff';
    case Plans = 'plans';
    case Attendance = 'attendance';
    case Payments = 'payments';
    case Billing = 'billing';
    case Expenses = 'expenses';
    case Accounts = 'accounts';
    case Documents = 'documents';
    case Messaging = 'messaging';
    case Tasks = 'tasks';
    case Reports = 'reports';

    public function label(): string
    {
        return match ($this) {
            self::Members => 'Members',
            self::Clubs => 'Clubs',
            self::Staff => 'Staff',
            self::Plans => 'Plans',
            self::Attendance => 'Attendance',
            self::Payments => 'Fee collection',
            self::Billing => 'Invoices',
            self::Expenses => 'Expenses',
            self::Accounts => 'Accounts',
            self::Documents => 'Documents',
            self::Messaging => 'WhatsApp messages',
            self::Tasks => 'Tasks',
            self::Reports => 'Reports',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::Members => 'The member register: profiles, joining dates, status, and search.',
            self::Clubs => 'Several locations under one organisation, with members, staff, and money grouped by club. Without it everything is organisation-wide.',
            self::Staff => 'The team page: invite staff, set permissions, and assign them to clubs.',
            self::Plans => 'Membership plans, renewals, expiry tracking, and plan balances.',
            self::Attendance => 'Daily rosters for members and staff, with the attendance calendar and rate.',
            self::Payments => 'Collecting fees with receipts, discounts, partial payments, and admin confirmation of staff collections.',
            self::Billing => 'Invoices built from a price list, with balances and shareable links.',
            self::Expenses => 'Recording what the organisation spends, by category.',
            self::Accounts => 'Cash and bank accounts that money is received into and paid from.',
            self::Documents => 'Files attached to members and staff, kept in the organisation\'s own storage.',
            self::Messaging => 'Ready-to-send WhatsApp messages after every action, and the message queue.',
            self::Tasks => 'Team tasks with categories, statuses, assignees, comments, and reminders.',
            self::Reports => 'Finance, membership, and attendance reports with exports.',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Members => 'user-group',
            self::Clubs => 'building-office-2',
            self::Staff => 'identification',
            self::Plans => 'rectangle-stack',
            self::Attendance => 'clipboard-document-check',
            self::Payments => 'banknotes',
            self::Billing => 'document-text',
            self::Expenses => 'receipt-percent',
            self::Accounts => 'credit-card',
            self::Documents => 'folder',
            self::Messaging => 'chat-bubble-left-right',
            self::Tasks => 'check-circle',
            self::Reports => 'chart-bar',
        };
    }

    public function group(): string
    {
        return match ($this) {
            self::Members, self::Clubs, self::Staff, self::Plans, self::Attendance => 'People',
            self::Payments, self::Billing, self::Expenses, self::Accounts => 'Finance',
            self::Documents, self::Messaging, self::Tasks, self::Reports => 'Operations',
        };
    }

    /**
     * Modules this one cannot exist without. Enabling this module enables
     * these too, and the set is closed transitively before it is stored.
     *
     * @return array<int, self>
     */
    public function requires(): array
    {
        return match ($this) {
            // A plan is sold to a member. Fees and invoices, by contrast, can
            // be made out to anyone by name, so they stand without Members.
            self::Plans => [self::Members],
            // Every collection names the account the money landed in.
            self::Payments => [self::Accounts],
            // Every expense is paid from an account.
            self::Expenses => [self::Accounts],
            default => [],
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
     * Adds every prerequisite of every enabled module, following chains until
     * nothing new appears. Unknown keys are dropped, so a module removed from
     * this enum cannot linger in stored data. The result keeps the enum's own
     * order so stored lists read the same regardless of how they were ticked.
     *
     * @param  array<int, string>  $enabled
     * @return array<int, string>
     */
    public static function expand(array $enabled): array
    {
        /** @var array<string, true> $resolved */
        $resolved = [];

        /** @var array<int, self> $queue */
        $queue = array_values(array_filter(array_map(
            static fn (string $key): ?self => self::tryFrom($key),
            $enabled,
        )));

        while ($queue !== []) {
            $feature = array_shift($queue);

            if (isset($resolved[$feature->value])) {
                continue;
            }

            $resolved[$feature->value] = true;

            foreach ($feature->requires() as $required) {
                $queue[] = $required;
            }
        }

        return array_values(array_filter(
            self::keys(),
            static fn (string $key): bool => isset($resolved[$key]),
        ));
    }
}
