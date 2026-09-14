<?php

declare(strict_types=1);

namespace App\Support\Images;

use App\Support\Theme\AccentPalette;
use GdImage;

/**
 * The installed-app icon: what a gym's staff see on a desktop dock or a phone
 * home screen once they install the platform.
 *
 * Two sources, in order of preference:
 *
 *   1. The organisation's own uploaded logo, centred on its accent colour.
 *   2. A generated mark in the accent colour, for organisations that have not
 *      uploaded anything — an install button that produces a blank or broken
 *      icon is worse than one that produces a plain but deliberate one.
 *
 * Drawn full-bleed with the mark inside the middle 60%, so the icon survives
 * being masked into a circle or a squircle by the operating system. That is
 * what `purpose: "maskable"` promises, and a transparent or edge-to-edge
 * design would be cropped into nonsense.
 */
final class AppIcon
{
    /** The safe zone for maskable icons: everything important inside 60%. */
    private const SAFE_ZONE = 0.60;

    /**
     * @param  string|null  $logo  Raw bytes of the organisation's logo, if any.
     */
    public static function render(string $accentHex, int $size, ?string $logo = null): string
    {
        $accent = AccentPalette::isValid($accentHex) ? $accentHex : AccentPalette::DEFAULT_ACCENT;

        $edge = max(1, $size);

        $canvas = imagecreatetruecolor($edge, $edge);

        self::fill($canvas, $accent);

        $mark = $logo === null ? null : @imagecreatefromstring($logo);

        if ($mark !== false && $mark !== null) {
            self::drawLogo($canvas, $mark, $edge);
            imagedestroy($mark);
        } else {
            self::drawBarbell($canvas, $edge, AccentPalette::readableOn($accent));
        }

        ob_start();
        imagepng($canvas, null, 9);
        $bytes = (string) ob_get_clean();

        imagedestroy($canvas);

        return $bytes;
    }

    private static function fill(GdImage $canvas, string $hex): void
    {
        [$r, $g, $b] = self::channels($hex);

        $colour = imagecolorallocate($canvas, $r, $g, $b);

        if ($colour !== false) {
            imagefilledrectangle($canvas, 0, 0, imagesx($canvas), imagesy($canvas), $colour);
        }
    }

    /**
     * Contains the logo inside the safe zone without distorting it, and keeps
     * its transparency so a cut-out mark shows the accent behind it.
     */
    private static function drawLogo(GdImage $canvas, GdImage $logo, int $size): void
    {
        $box = (int) ($size * self::SAFE_ZONE);
        $scale = min($box / imagesx($logo), $box / imagesy($logo));

        $width = max(1, (int) round(imagesx($logo) * $scale));
        $height = max(1, (int) round(imagesy($logo) * $scale));

        imagealphablending($canvas, true);
        imagecopyresampled(
            $canvas,
            $logo,
            (int) (($size - $width) / 2),
            (int) (($size - $height) / 2),
            0, 0,
            $width, $height,
            imagesx($logo), imagesy($logo),
        );
    }

    /**
     * A barbell built from rectangles — no font file needed, and it reads at
     * 32px as well as at 512.
     */
    private static function drawBarbell(GdImage $canvas, int $size, string $inkHex): void
    {
        [$r, $g, $b] = self::channels($inkHex);
        $ink = imagecolorallocate($canvas, $r, $g, $b);

        if ($ink === false) {
            return;
        }

        $centre = $size / 2;
        $unit = $size / 100;

        $rect = static function (float $x1, float $y1, float $x2, float $y2) use ($canvas, $ink, $centre, $unit): void {
            imagefilledrectangle(
                $canvas,
                (int) round($centre + $x1 * $unit),
                (int) round($centre + $y1 * $unit),
                (int) round($centre + $x2 * $unit),
                (int) round($centre + $y2 * $unit),
                $ink,
            );
        };

        // Bar.
        $rect(-24, -4, 24, 4);

        // Inner plates.
        $rect(-20, -16, -11, 16);
        $rect(11, -16, 20, 16);

        // Outer collars.
        $rect(-29, -9, -23, 9);
        $rect(23, -9, 29, 9);
    }

    /**
     * Clamped rather than merely cast: a malformed hex would otherwise hand GD
     * a channel outside 0-255, which it rejects, and the icon would come back
     * blank instead of wrong.
     *
     * @return array{0: int<0, 255>, 1: int<0, 255>, 2: int<0, 255>}
     */
    private static function channels(string $hex): array
    {
        $value = ltrim($hex, '#');

        $channel = static fn (int $offset): int => min(255, max(0, (int) hexdec(substr($value, $offset, 2))));

        return [$channel(0), $channel(2), $channel(4)];
    }
}
