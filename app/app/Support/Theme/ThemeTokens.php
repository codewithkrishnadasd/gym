<?php

declare(strict_types=1);

namespace App\Support\Theme;

/**
 * The full colour vocabulary of the interface, per theme, and how an
 * organisation's choices layer onto it.
 *
 * Three layers, each overriding the last: the built-in palette (mirrored from
 * resources/css/app.css and checked against it by test), the tokens derived
 * from a single accent colour (AccentPalette), and finally any colour the
 * platform admin set explicitly for a theme. The result is emitted as one CSS
 * block after the stylesheet, so the utility classes never change — only the
 * variables they resolve to.
 */
final class ThemeTokens
{
    public const THEMES = ['light', 'dark'];

    /**
     * Tokens grouped the way an operator thinks about them, with the label
     * shown in the editor. The key is the `--c-{key}` variable name.
     *
     * @var array<string, array<string, string>>
     */
    public const GROUPS = [
        'Surfaces' => [
            'app' => 'Page background',
            'surface' => 'Cards and panels',
            'raised' => 'Raised panels',
            'sunken' => 'Recessed areas',
            'hairline' => 'Borders',
            'hairline-strong' => 'Input and strong borders',
        ],
        'Text' => [
            'ink' => 'Text',
            'ink-soft' => 'Secondary text',
            'ink-muted' => 'Muted text',
        ],
        'Primary button and links' => [
            'accent' => 'Primary button and links',
            'on-accent' => 'Text on primary button',
            'accent-soft' => 'Accent tint (badges, highlights)',
            'accent-ink' => 'Text on accent tint',
        ],
        'Secondary button' => [
            'button-secondary' => 'Background',
            'button-secondary-ink' => 'Text',
            'button-secondary-border' => 'Border',
        ],
        'Status colours' => [
            'positive' => 'Positive',
            'positive-soft' => 'Positive tint',
            'caution' => 'Caution',
            'caution-soft' => 'Caution tint',
            'critical' => 'Critical',
            'critical-soft' => 'Critical tint',
            'info' => 'Info',
            'info-soft' => 'Info tint',
        ],
    ];

    /**
     * @var array<string, array<string, string>>
     */
    public const DEFAULTS = [
        'light' => [
            'app' => '#f6f7f9',
            'surface' => '#ffffff',
            'raised' => '#f9fafb',
            'sunken' => '#f1f3f5',
            'hairline' => '#e5e8ec',
            'hairline-strong' => '#d3d8de',
            'ink' => '#0f172a',
            'ink-soft' => '#475569',
            'ink-muted' => '#7c8798',
            'accent' => '#0e7490',
            'on-accent' => '#ffffff',
            'accent-soft' => '#ecfdff',
            'accent-ink' => '#0e7490',
            'button-secondary' => '#ffffff',
            'button-secondary-ink' => '#0f172a',
            'button-secondary-border' => '#d3d8de',
            'positive' => '#047857',
            'positive-soft' => '#ecfdf5',
            'caution' => '#b45309',
            'caution-soft' => '#fffbeb',
            'critical' => '#be123c',
            'critical-soft' => '#fff1f2',
            'info' => '#4338ca',
            'info-soft' => '#eef2ff',
        ],
        'dark' => [
            'app' => '#080d18',
            'surface' => '#101828',
            'raised' => '#16203a',
            'sunken' => '#0b1122',
            'hairline' => '#1f2b45',
            'hairline-strong' => '#2c3b5c',
            'ink' => '#f1f5f9',
            'ink-soft' => '#a4b2c8',
            'ink-muted' => '#6f7f99',
            'accent' => '#22d3ee',
            'on-accent' => '#04212b',
            'accent-soft' => '#0c3441',
            'accent-ink' => '#67e8f9',
            'button-secondary' => '#101828',
            'button-secondary-ink' => '#f1f5f9',
            'button-secondary-border' => '#2c3b5c',
            'positive' => '#34d399',
            'positive-soft' => '#0a2f26',
            'caution' => '#fbbf24',
            'caution-soft' => '#35270a',
            'critical' => '#fb7185',
            'critical-soft' => '#3a1220',
            'info' => '#a5b4fc',
            'info-soft' => '#1e1f47',
        ],
    ];

    /**
     * Pairs that must stay readable, checked in the editor as the operator
     * picks: [foreground, background, what it is].
     *
     * @var array<int, array{0: string, 1: string, 2: string}>
     */
    public const CONTRAST_PAIRS = [
        ['ink', 'app', 'Text on the page background'],
        ['ink', 'surface', 'Text on cards'],
        ['ink-soft', 'surface', 'Secondary text on cards'],
        ['on-accent', 'accent', 'Text on the primary button'],
        ['accent-ink', 'accent-soft', 'Text on accent tint'],
        ['button-secondary-ink', 'button-secondary', 'Text on the secondary button'],
    ];

    /**
     * @return array<int, string>
     */
    public static function keys(): array
    {
        return array_keys(array_merge(...array_values(self::GROUPS)));
    }

    /**
     * Keeps only known tokens holding a valid hex colour, so a stray or
     * malformed input can never reach the stylesheet.
     *
     * @param  array<mixed>  $input
     * @return array<string, array<string, string>>
     */
    public static function sanitize(array $input): array
    {
        $clean = [];
        $keys = self::keys();

        foreach (self::THEMES as $theme) {
            $chosen = $input[$theme] ?? [];

            if (! is_array($chosen)) {
                continue;
            }

            foreach ($keys as $key) {
                $value = $chosen[$key] ?? null;

                if (is_string($value) && AccentPalette::isValid($value)) {
                    $clean[$theme][$key] = strtolower($value);
                }
            }
        }

        return $clean;
    }

    /**
     * The tokens the accent colour alone would set, keyed like DEFAULTS.
     *
     * @return array<string, array<string, string>>
     */
    public static function derivedFromAccent(?string $accent): array
    {
        if ($accent === null || ! AccentPalette::isValid($accent)) {
            return ['light' => [], 'dark' => []];
        }

        $palette = AccentPalette::for($accent);
        $strip = static fn (array $tokens): array => array_combine(
            array_map(static fn (string $name): string => substr($name, 4), array_keys($tokens)),
            array_values($tokens),
        );

        return ['light' => $strip($palette['light']), 'dark' => $strip($palette['dark'])];
    }

    /**
     * Every token's effective value for both themes.
     *
     * @param  array<string, array<string, string>>|null  $overrides
     * @return array<string, array<string, string>>
     */
    public static function resolve(?string $accent, ?array $overrides): array
    {
        $derived = self::derivedFromAccent($accent);
        $resolved = [];

        foreach (self::THEMES as $theme) {
            $resolved[$theme] = [
                ...self::DEFAULTS[$theme],
                ...$derived[$theme],
                ...($overrides[$theme] ?? []),
            ];
        }

        return $resolved;
    }

    /**
     * The overriding CSS for the document head, or null when the organisation
     * has changed nothing — so the built-in palette is not restated on every
     * page for no reason.
     *
     * @param  array<string, array<string, string>>|null  $overrides
     */
    public static function css(?string $accent, ?array $overrides): ?string
    {
        $derived = self::derivedFromAccent($accent);
        $blocks = [];

        foreach (self::THEMES as $theme) {
            $tokens = [...$derived[$theme], ...($overrides[$theme] ?? [])];

            if ($tokens === []) {
                continue;
            }

            $declarations = implode('', array_map(
                static fn (string $key, string $value): string => '--c-'.$key.':'.$value.';',
                array_keys($tokens),
                $tokens,
            ));

            $blocks[] = ($theme === 'light' ? ':root' : "[data-theme='dark']").'{'.$declarations.'}';
        }

        return $blocks === [] ? null : implode('', $blocks);
    }

    /**
     * WCAG contrast ratio between two colours; 4.5 is the floor for body text.
     */
    public static function contrast(string $foreground, string $background): float
    {
        $lighter = max(self::luminance($foreground), self::luminance($background));
        $darker = min(self::luminance($foreground), self::luminance($background));

        return round(($lighter + 0.05) / ($darker + 0.05), 2);
    }

    private static function luminance(string $hex): float
    {
        $value = ltrim($hex, '#');
        $linear = [];

        foreach ([0, 2, 4] as $offset) {
            $channel = ((int) hexdec(substr($value, $offset, 2))) / 255;
            $linear[] = $channel <= 0.03928 ? $channel / 12.92 : (($channel + 0.055) / 1.055) ** 2.4;
        }

        return 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
    }
}
