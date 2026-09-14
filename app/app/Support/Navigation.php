<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Organisation;
use App\Models\OrganisationUser;

/**
 * Builds the role- and permission-aware navigation for the tenant shell
 * (MEP.md Section 7). Hiding an item here is presentation only — every
 * destination re-authorizes in its own component's `mount()`.
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
                ($isAdmin || $can('members.view')) ? [
                    'label' => $organisation->term('member_plural'),
                    'route' => 'tenant.members.index',
                    'icon' => 'user-group',
                    'active' => 'tenant.members.*',
                    'mobile' => true,
                ] : null,
                ($isAdmin || $can('attendance.member.mark') || $can('attendance.staff.mark')) ? [
                    'label' => 'Attendance',
                    'route' => 'tenant.attendance.members',
                    'icon' => 'clipboard-document-check',
                    'active' => 'tenant.attendance.*',
                    'mobile' => true,
                ] : null,
            ])),
        ];

        $organisationItems = array_values(array_filter([
            ($isAdmin || $can('clubs.view_assigned')) ? [
                'label' => $organisation->term('club_plural'),
                'route' => 'tenant.clubs.index',
                'icon' => 'building-office-2',
                'active' => 'tenant.clubs.*',
            ] : null,
            ($isAdmin || $can('staff.view')) ? [
                'label' => $organisation->term('user_plural'),
                'route' => 'tenant.staff.index',
                'icon' => 'identification',
                'active' => 'tenant.staff.*',
            ] : null,
            $isAdmin ? [
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
            ($isAdmin || $can('fees.collect') || $can('fees.view_own')) ? [
                'label' => 'Payments',
                'route' => 'tenant.finance.payments.index',
                'icon' => 'banknotes',
                'active' => 'tenant.finance.payments.*',
                'mobile' => true,
            ] : null,
            ($isAdmin || $can('billing.view')) ? [
                'label' => 'Invoices',
                'route' => 'tenant.billing.index',
                'icon' => 'document-text',
                'active' => 'tenant.billing.*',
            ] : null,
            $isAdmin ? [
                'label' => 'Confirmations',
                'route' => 'tenant.finance.confirmations',
                'icon' => 'check-badge',
                'active' => 'tenant.finance.confirmations',
            ] : null,
            $isAdmin ? [
                'label' => 'Expenses',
                'route' => 'tenant.finance.expenses.index',
                'icon' => 'receipt-percent',
                'active' => 'tenant.finance.expenses.*',
            ] : null,
            $isAdmin ? [
                'label' => 'Accounts',
                'route' => 'tenant.finance.accounts.index',
                'icon' => 'credit-card',
                'active' => 'tenant.finance.accounts.*',
            ] : null,
        ]));

        if ($financeItems !== []) {
            $sections[] = ['heading' => 'Finance', 'items' => $financeItems];
        }

        $messagingItems = ($isAdmin || $can('notifications.send')) ? [[
            'label' => 'Messages',
            'route' => 'tenant.notifications.index',
            'icon' => 'chat-bubble-left-right',
            'active' => 'tenant.notifications.*',
        ]] : [];

        if ($messagingItems !== []) {
            $sections[] = ['heading' => 'Messaging', 'items' => $messagingItems];
        }

        $insightItems = array_values(array_filter([
            ($isAdmin || $can('reports.view_assigned')) ? [
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
     * The four highest-value destinations for the mobile bottom bar; anything
     * else stays reachable through the "More" drawer.
     *
     * @param  array<int, NavSection>  $sections
     * @return array<int, NavItem>
     */
    public static function mobilePrimary(array $sections): array
    {
        $items = [];

        foreach ($sections as $section) {
            foreach ($section['items'] as $item) {
                if ($item['mobile'] ?? false) {
                    $items[] = $item;
                }
            }
        }

        return array_slice($items, 0, 4);
    }
}
