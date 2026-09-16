<?php

declare(strict_types=1);

namespace App\Support;

use App\Enums\Feature;
use App\Models\Attendance;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\User;

/**
 * Builds the role- and permission-aware navigation for the tenant shell
 * (MEP.md Section 7). Hiding an item here is presentation only — every
 * destination re-authorizes in its own component's `mount()`, and a module
 * the organisation has switched off (App\Enums\Feature) answers 404.
 *
 * @phpstan-type NavItem array{label: string, route: string, icon: string, active: string, mobile?: bool}
 * @phpstan-type NavSection array{heading: string|null, items: array<int, NavItem>}
 */
final class Navigation
{
    /**
     * @return array<int, NavSection>
     */
    public static function forTenant(Organisation $organisation, ?OrganisationUser $membership): array
    {
        if (! $membership) {
            return [];
        }

        $isAdmin = $membership->isAdmin();
        $can = static fn (string $key): bool => $membership->hasPermission($key);
        $has = static fn (Feature $feature): bool => $organisation->hasFeature($feature);

        // The member roster is the natural first stop; an organisation
        // without members marks staff only.
        $attendanceRoute = $has(Feature::Members) ? 'tenant.attendance.members' : 'tenant.attendance.staff';
        $attendanceAllowed = ($has(Feature::Members) && ($isAdmin || $can('attendance.member.mark')))
            || ($has(Feature::Staff) && ($isAdmin || $can('attendance.staff.mark')));

        $sections = [];

        $sections[] = [
            'heading' => null,
            'items' => array_values(array_filter([
                [
                    'label' => 'Dashboard',
                    'route' => 'tenant.dashboard',
                    'icon' => 'squares-2x2',
                    'active' => 'tenant.dashboard',
                    'mobile' => true,
                ],
                $has(Feature::Members) && ($isAdmin || $can('members.view')) ? [
                    'label' => $organisation->term('member_plural'),
                    'route' => 'tenant.members.index',
                    'icon' => 'user-group',
                    'active' => 'tenant.members.*',
                    'mobile' => true,
                ] : null,
                $has(Feature::Attendance) && $attendanceAllowed ? [
                    'label' => 'Attendance',
                    'route' => $attendanceRoute,
                    'icon' => 'clipboard-document-check',
                    'active' => 'tenant.attendance.*',
                    'mobile' => true,
                ] : null,
                // Every active membership: tasks are how the team coordinates.
                $has(Feature::Tasks) ? [
                    'label' => 'Tasks',
                    'route' => 'tenant.tasks.index',
                    'icon' => 'check-circle',
                    'active' => 'tenant.tasks.*',
                    'mobile' => true,
                ] : null,
            ])),
        ];

        $organisationItems = array_values(array_filter([
            $has(Feature::Clubs) && ($isAdmin || $can('clubs.view_assigned')) ? [
                'label' => $organisation->term('club_plural'),
                'route' => 'tenant.clubs.index',
                'icon' => 'building-office-2',
                'active' => 'tenant.clubs.*',
            ] : null,
            $has(Feature::Staff) && ($isAdmin || $can('staff.view')) ? [
                'label' => $organisation->term('user_plural'),
                'route' => 'tenant.staff.index',
                'icon' => 'identification',
                'active' => 'tenant.staff.*',
            ] : null,
            $has(Feature::Plans) && $isAdmin ? [
                'label' => 'Plans',
                'route' => 'tenant.plans.index',
                'icon' => 'rectangle-stack',
                'active' => 'tenant.plans.*',
            ] : null,
        ]));

        if ($organisationItems !== []) {
            $sections[] = ['heading' => 'Organisation', 'items' => $organisationItems];
        }

        $financeItems = array_values(array_filter([
            $has(Feature::Payments) && ($isAdmin || $can('fees.collect') || $can('fees.view_own')) ? [
                'label' => 'Payments',
                'route' => 'tenant.finance.payments.index',
                'icon' => 'banknotes',
                'active' => 'tenant.finance.payments.*',
                'mobile' => true,
            ] : null,
            $has(Feature::Billing) && ($isAdmin || $can('billing.view')) ? [
                'label' => 'Invoices',
                'route' => 'tenant.billing.index',
                'icon' => 'document-text',
                'active' => 'tenant.billing.*',
            ] : null,
            $has(Feature::Payments) && $isAdmin ? [
                'label' => 'Confirmations',
                'route' => 'tenant.finance.confirmations',
                'icon' => 'check-badge',
                'active' => 'tenant.finance.confirmations',
            ] : null,
            $has(Feature::Expenses) && $isAdmin ? [
                'label' => 'Expenses',
                'route' => 'tenant.finance.expenses.index',
                'icon' => 'receipt-percent',
                'active' => 'tenant.finance.expenses.*',
            ] : null,
            $has(Feature::Accounts) && $isAdmin ? [
                'label' => 'Accounts',
                'route' => 'tenant.finance.accounts.index',
                'icon' => 'credit-card',
                'active' => 'tenant.finance.accounts.*',
            ] : null,
        ]));

        if ($financeItems !== []) {
            $sections[] = ['heading' => 'Finance', 'items' => $financeItems];
        }

        $messagingItems = $has(Feature::Messaging) && ($isAdmin || $can('notifications.send')) ? [[
            'label' => 'Messages',
            'route' => 'tenant.notifications.index',
            'icon' => 'chat-bubble-left-right',
            'active' => 'tenant.notifications.*',
        ]] : [];

        if ($messagingItems !== []) {
            $sections[] = ['heading' => 'Messaging', 'items' => $messagingItems];
        }

        $insightItems = array_values(array_filter([
            // Reports need something to report on.
            $has(Feature::Reports) && ($has(Feature::Members) || $has(Feature::Payments) || $has(Feature::Expenses)) && ($isAdmin || $can('reports.view_assigned')) ? [
                'label' => 'Reports',
                'route' => 'tenant.reports.index',
                'icon' => 'chart-bar',
                'active' => 'tenant.reports.*',
            ] : null,
            $isAdmin ? [
                'label' => 'Audit log',
                'route' => 'tenant.audit.index',
                'icon' => 'shield-check',
                'active' => 'tenant.audit.*',
            ] : null,
            $isAdmin ? [
                'label' => 'Settings',
                'route' => 'tenant.settings.organisation',
                'icon' => 'cog-6-tooth',
                'active' => 'tenant.settings.*',
            ] : null,
        ]));

        if ($insightItems !== []) {
            $sections[] = ['heading' => 'Insights', 'items' => $insightItems];
        }

        return $sections;
    }

    /**
     * @return array<int, NavSection>
     */
    public static function forPlatform(): array
    {
        return [[
            'heading' => null,
            'items' => [
                [
                    'label' => 'Overview',
                    'route' => 'platform.dashboard',
                    'icon' => 'squares-2x2',
                    'active' => 'platform.dashboard',
                    'mobile' => true,
                ],
                [
                    'label' => 'Organisations',
                    'route' => 'platform.organisations.index',
                    'icon' => 'building-office-2',
                    'active' => 'platform.organisations.*',
                    'mobile' => true,
                ],
            ],
        ]];
    }

    /**
     * The icons a tab or a quick action may use, as heroicon name => label.
     *
     * @var array<string, string>
     */
    public const ICONS = [
        'squares-2x2' => 'Grid',
        'user-group' => 'People',
        'user-plus' => 'Add person',
        'identification' => 'ID card',
        'clipboard-document-check' => 'Checklist',
        'check-circle' => 'Tick',
        'banknotes' => 'Money',
        'credit-card' => 'Card',
        'document-text' => 'Document',
        'receipt-percent' => 'Receipt',
        'building-office-2' => 'Building',
        'rectangle-stack' => 'Stack',
        'chart-bar' => 'Chart',
        'chat-bubble-left-right' => 'Chat',
        'calendar-days' => 'Calendar',
        'bell' => 'Bell',
        'star' => 'Star',
        'heart' => 'Heart',
        'bolt' => 'Bolt',
        'fire' => 'Fire',
        'trophy' => 'Trophy',
        'shield-check' => 'Shield',
        'cog-6-tooth' => 'Settings',
        'home' => 'Home',
    ];

    /**
     * The actions the dashboard's floating button can open, keyed by the
     * value stored in settings.
     *
     * @var array<string, array{label: string, route: string, feature: Feature, ability: array{0: string, 1: class-string}, symbol: string|null, icon: string}>
     */
    public const QUICK_ACTIONS = [
        'payments' => ['label' => 'Collect fee', 'route' => 'tenant.finance.payments.create', 'feature' => Feature::Payments, 'ability' => ['create', FeePayment::class], 'symbol' => 'currency', 'icon' => 'banknotes'],
        'members' => ['label' => 'Add member', 'route' => 'tenant.members.create', 'feature' => Feature::Members, 'ability' => ['create', Member::class], 'symbol' => '+', 'icon' => 'user-plus'],
        'tasks' => ['label' => 'New task', 'route' => 'tenant.tasks.create', 'feature' => Feature::Tasks, 'ability' => ['create', Task::class], 'symbol' => '+', 'icon' => 'check-circle'],
        'billing' => ['label' => 'New invoice', 'route' => 'tenant.billing.create', 'feature' => Feature::Billing, 'ability' => ['create', Invoice::class], 'symbol' => '+', 'icon' => 'document-text'],
        'expenses' => ['label' => 'Record expense', 'route' => 'tenant.finance.expenses.create', 'feature' => Feature::Expenses, 'ability' => ['create', Expense::class], 'symbol' => '−', 'icon' => 'receipt-percent'],
        'attendance' => ['label' => 'Mark attendance', 'route' => 'tenant.attendance.members', 'feature' => Feature::Attendance, 'ability' => ['markMembers', Attendance::class], 'symbol' => '✓', 'icon' => 'clipboard-document-check'],
    ];

    /**
     * The tabs of the phone bottom bar: the person's own arrangement where
     * they have made one (account menu → Navigation), otherwise the four
     * highest-value destinations. Either way only destinations they may
     * see are shown, and the Menu tab is always there beside them.
     *
     * @param  array<int, NavSection>  $sections
     * @return array<int, NavItem>
     */
    public static function mobilePrimary(array $sections, ?OrganisationUser $membership = null): array
    {
        $available = [];

        foreach ($sections as $section) {
            foreach ($section['items'] as $item) {
                $available[$item['route']] = $item;
            }
        }

        $chosen = $membership?->mobileNavigation();

        if ($chosen !== null) {
            $items = [];

            foreach ($chosen as $tab) {
                if (! isset($available[$tab['route']]) || isset($items[$tab['route']])) {
                    continue;
                }

                $item = $available[$tab['route']];

                if ($tab['icon'] !== '' && isset(self::ICONS[$tab['icon']])) {
                    $item['icon'] = $tab['icon'];
                }

                $items[$tab['route']] = $item;
            }

            if ($items !== []) {
                return array_slice(array_values($items), 0, 4);
            }
        }

        return array_slice(
            array_values(array_filter($available, static fn (array $item): bool => $item['mobile'] ?? false)),
            0,
            4,
        );
    }

    /**
     * The action behind the dashboard's floating button for this viewer:
     * their own choice when its module is on and they may do it, otherwise
     * the first of the built-in order they may.
     *
     * @return array{label: string, route: string, symbol: string, icon: string}|null
     */
    public static function quickAction(Organisation $organisation, User $user, ?OrganisationUser $membership = null): ?array
    {
        $order = array_keys(self::QUICK_ACTIONS);
        $preferred = $membership?->quickAction();

        if ($preferred !== null && isset(self::QUICK_ACTIONS[$preferred])) {
            $order = [$preferred, ...array_diff($order, [$preferred])];
        }

        foreach ($order as $key) {
            $action = self::QUICK_ACTIONS[$key];

            if (! $organisation->hasFeature($action['feature']) || ! $user->can($action['ability'][0], $action['ability'][1])) {
                continue;
            }

            return [
                'label' => $action['label'],
                'route' => $action['route'],
                'symbol' => $action['symbol'] === 'currency' ? $organisation->currencySymbol() : (string) $action['symbol'],
                'icon' => $action['icon'],
            ];
        }

        return null;
    }

    /**
     * Every destination an admin could put on the phone bar, for the
     * settings screen.
     *
     * @return array<string, string> route => label
     */
    public static function destinations(Organisation $organisation, OrganisationUser $membership): array
    {
        $labels = [];

        foreach (self::forTenant($organisation, $membership) as $section) {
            foreach ($section['items'] as $item) {
                $labels[$item['route']] = $item['label'];
            }
        }

        return $labels;
    }
}
