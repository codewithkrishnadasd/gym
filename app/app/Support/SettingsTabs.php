<?php

declare(strict_types=1);

namespace App\Support;

use App\Models\Organisation;

/**
 * The one list of settings tabs, read from two places: an organisation's own
 * settings page, and the platform console's page for that organisation. The
 * console shows the same tabs after its own — General, Features, Appearance,
 * Domains, People — which only the platform admin ever sees.
 *
 * A module's tab exists only while the module does (App\Enums\Feature); an
 * old link to a hidden tab lands on the first tab.
 */
final class SettingsTabs
{
    /** Tabs only the platform console has. */
    public const PLATFORM = [
        'general' => 'General',
        'features' => 'Features',
        'appearance' => 'Appearance',
        'domains' => 'Domains',
        'people' => 'People',
    ];

    /**
     * @return array<string, string> tab key => label, in display order
     */
    public static function for(Organisation $organisation, bool $platform): array
    {
        $organisationTabs = array_filter([
            'profile' => 'Profile',
            'terminology' => 'Terminology',
            'notifications' => $organisation->hasFeature('messaging') ? 'Notifications' : null,
            'expenses' => $organisation->hasFeature('expenses') ? 'Expense categories' : null,
            'billing' => $organisation->hasFeature('billing') ? 'Billing' : null,
            'storage' => $organisation->hasFeature('documents') ? 'Storage' : null,
            'tasks' => $organisation->hasFeature('tasks') ? 'Tasks' : null,
            'templates' => $organisation->hasFeature('messaging') ? 'Message templates' : null,
        ]);

        return $platform ? self::PLATFORM + $organisationTabs : $organisationTabs;
    }

    /**
     * The tab to show for a requested key: the key itself when it exists,
     * otherwise the first tab.
     */
    public static function resolve(Organisation $organisation, bool $platform, ?string $tab): string
    {
        $tabs = self::for($organisation, $platform);

        if ($tab !== null && isset($tabs[$tab])) {
            return $tab;
        }

        return (string) array_key_first($tabs);
    }

    public static function isPlatformTab(string $tab): bool
    {
        return isset(self::PLATFORM[$tab]);
    }

    public static function url(Organisation $organisation, bool $platform, string $tab): string
    {
        return $platform
            ? route('platform.organisations.edit', ['organisation' => $organisation, 'tab' => $tab])
            : route('tenant.settings.organisation', ['tab' => $tab]);
    }

    /**
     * Items for x-ui.tabs.
     *
     * @return array<int, array{label: string, url: string, active: bool}>
     */
    public static function items(Organisation $organisation, bool $platform, string $active): array
    {
        $items = [];

        foreach (self::for($organisation, $platform) as $key => $label) {
            $items[] = [
                'label' => $label,
                'url' => self::url($organisation, $platform, $key),
                'active' => $active === $key,
            ];
        }

        return $items;
    }
}
