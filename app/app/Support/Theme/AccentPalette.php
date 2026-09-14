<?php

declare(strict_types=1);

namespace App\Support\Theme;

/**
 * Derives a complete set of accent tokens from one colour an operator picked.
 *
 * Asking for four colours per theme and trusting them to be legible together
 * is how branding features produce white text on yellow buttons. One input is
 * chosen, and the tints, the darker "ink" variant, and — critically — the
 * foreground colour that sits *on* the accent are all computed, so an
 * unreadable combination cannot be configured.
 */
final class AccentPalette
{
    /** The built-in teal, used when an organisation has picked nothing. */
    public const DEFAULT_ACCENT = '#0e7490';

    /** Page background of each theme, mixed into the soft tints. */
    private const LIGHT_GROUND = '#ffffff';

    private const DARK_GROUND = '#080d18';

    /** Foreground candidates for text drawn on the accent itself. */
    private const LIGHT_INK = '#ffffff';

    private const DARK_INK = '#0f172a';

    public static function isValid(string $hex): bool
    {
        return preg_match('/^#[0-9a-fA-F]{6}$/', $hex) === 1;
    }

    /**
     * @return array{light: array<string, string>, dark: array<string, string>}
     */
    public static function for(string $hex): array
    {
        $base = self::isValid($hex) ? strtolower($hex) : self::DEFAULT_ACCENT;

        // The same hue reads as heavier on a dark ground, so the dark theme
        // uses a lifted version rather than the literal colour picked.
        $lifted = self::mix($base, self::LIGHT_INK, 0.35);

        return [
            'light' => [
                '--c-accent' => $base,
                '--c-accent-ink' => self::mix($base, self::DARK_INK, 0.12),
                '--c-accent-soft' => self::mix($base, self::LIGHT_GROUND, 0.92),
                '--c-on-accent' => self::readableOn($base),
            ],
            'dark' => [
                '--c-accent' => $lifted,
                '--c-accent-ink' => self::mix($base, self::LIGHT_INK, 0.55),
                '--c-accent-soft' => self::mix($base, self::DARK_GROUND, 0.80),
                '--c-on-accent' => self::readableOn($lifted),
            ],
        ];
    }

    /**
     * The tokens as a CSS block, ready to follow the stylesheet in the head.
     * Both theme states are emitted because the viewer can be on either.
     */
    public static function css(string $hex): string
    {
        $palette = self::for($hex);

        $declare = static fn (array $tokens): string => implode('', array_map(
            static fn (string $name, string $value): string => $name.':'.$value.';',
            array_keys($tokens),
            $tokens,
        ));

        return ':root{'.$declare($palette['light']).'}'
            ."[data-theme='dark']{".$declare($palette['dark']).'}';
    }

    /**
     * Black or white, whichever is actually readable on the given colour.
     *
     * Uses WCAG relative luminance rather than a naive brightness average: the
     * eye is far more sensitive to green than to blue, so averaging the
     * channels picks white text on colours where it disappears.
     */
    public static function readableOn(string $hex): string
    {
        return self::luminance($hex) > 0.45 ? self::DARK_INK : self::LIGHT_INK;
    }

    /**
     * Blends `$amount` of `$towards` into `$hex`, in sRGB.
     */
    private static function mix(string $hex, string $towards, float $amount): string
    {
        [$r1, $g1, $b1] = self::channels($hex);
        [$r2, $g2, $b2] = self::channels($towards);

        $blend = static fn (int $a, int $b): int => (int) round($a + ($b - $a) * $amount);

        return sprintf('#%02x%02x%02x', $blend($r1, $r2), $blend($g1, $g2), $blend($b1, $b2));
    }

    private static function luminance(string $hex): float
    {
        $linear = array_map(static function (int $channel): float {
            $value = $channel / 255;

            return $value <= 0.03928 ? $value / 12.92 : (($value + 0.055) / 1.055) ** 2.4;
        }, self::channels($hex));

        return 0.2126 * $linear[0] + 0.7152 * $linear[1] + 0.0722 * $linear[2];
    }

    /**
     * @return array{0: int, 1: int, 2: int}
     */
    private static function channels(string $hex): array
    {
        $value = ltrim($hex, '#');

        return [
            (int) hexdec(substr($value, 0, 2)),
            (int) hexdec(substr($value, 2, 2)),
            (int) hexdec(substr($value, 4, 2)),
        ];
    }
}
