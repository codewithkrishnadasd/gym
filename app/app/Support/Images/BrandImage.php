<?php

declare(strict_types=1);

namespace App\Support\Images;

use GdImage;
use RuntimeException;

/**
 * Turns one uploaded picture into the two images the platform actually serves:
 * a logo for the sidebar and sign-in page, and a square favicon for the
 * browser tab.
 *
 * Operators upload whatever they have — usually a several-megabyte photo of a
 * printed sign — and neither the original dimensions nor the original weight
 * are ever useful to a browser. Both derivatives are re-encoded here so what
 * ships is small, and so nothing that came off an untrusted upload is served
 * back byte-for-byte.
 *
 * Uses GD, which is already compiled into the PHP image. No WebP: the image is
 * built without it, and at these sizes the saving is kilobytes.
 */
final class BrandImage
{
    /** Longest edge of the logo. Twice the largest place it is drawn, for retina. */
    public const LOGO_MAX_EDGE = 512;

    /** Square favicon edge. 64 covers every tab and bookmark size in use. */
    public const FAVICON_EDGE = 64;

    public const JPEG_QUALITY = 82;

    /**
     * Refuses anything larger than this many pixels regardless of file size.
     * A 60 KB PNG can decode to gigabytes of memory, and `upload_max_filesize`
     * does not protect against that.
     */
    private const MAX_SOURCE_PIXELS = 50_000_000;

    /** @var list<int> */
    private const READABLE_TYPES = [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF];

    /**
     * @return array{logo: string, logoExtension: string, favicon: string}
     *
     * @throws RuntimeException when the file is not a picture GD can read.
     */
    public static function derive(string $sourcePath): array
    {
        $source = self::read($sourcePath);

        try {
            $logo = self::contain($source, self::LOGO_MAX_EDGE);
            $favicon = self::squareCrop($source, self::FAVICON_EDGE);
        } finally {
            imagedestroy($source);
        }

        try {
            // A logo with transparency has to stay PNG or it gains a white box
            // on a dark sidebar. A photograph does not, and JPEG is a fraction
            // of the size for one.
            $transparent = self::hasTransparency($logo);

            return [
                'logo' => $transparent ? self::encodePng($logo) : self::encodeJpeg($logo),
                'logoExtension' => $transparent ? 'png' : 'jpg',
                'favicon' => self::encodePng($favicon),
            ];
        } finally {
            imagedestroy($logo);
            imagedestroy($favicon);
        }
    }

    /**
     * Whether GD in this build can read the file at all — used to reject an
     * upload with a clear message before any processing is attempted.
     */
    public static function isReadable(string $sourcePath): bool
    {
        $info = @getimagesize($sourcePath);

        return $info !== false && in_array($info[2], self::READABLE_TYPES, true);
    }

    private static function read(string $sourcePath): GdImage
    {
        $info = @getimagesize($sourcePath);

        if ($info === false || ! in_array($info[2], self::READABLE_TYPES, true)) {
            throw new RuntimeException('That file is not a JPEG, PNG, or GIF image.');
        }

        if ($info[0] * $info[1] > self::MAX_SOURCE_PIXELS) {
            throw new RuntimeException('That image has too many pixels to process.');
        }

        $image = match ($info[2]) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($sourcePath),
            IMAGETYPE_PNG => @imagecreatefrompng($sourcePath),
            default => @imagecreatefromgif($sourcePath),
        };

        if ($image === false) {
            throw new RuntimeException('That image could not be read. Try re-saving it and uploading again.');
        }

        return $image;
    }

    /**
     * Scales to fit inside a square of `$maxEdge` without cropping or
     * distorting, and never enlarges — upscaling a small logo only makes a
     * blurry, larger file.
     */
    private static function contain(GdImage $source, int $maxEdge): GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);

        $scale = min(1.0, $maxEdge / max($width, $height));

        $targetWidth = max(1, (int) round($width * $scale));
        $targetHeight = max(1, (int) round($height * $scale));

        $target = self::canvas($targetWidth, $targetHeight);

        imagecopyresampled($target, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

        return $target;
    }

    /**
     * Centre-crops to a square before resizing, so a wide logo becomes a
     * favicon of its middle rather than a squashed one.
     */
    private static function squareCrop(GdImage $source, int $edge): GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $side = min($width, $height);

        $target = self::canvas($edge, $edge);

        imagecopyresampled(
            $target,
            $source,
            0, 0,
            (int) (($width - $side) / 2),
            (int) (($height - $side) / 2),
            $edge, $edge,
            $side, $side,
        );

        return $target;
    }

    /**
     * A truecolour canvas that keeps alpha rather than compositing onto black,
     * which is GD's default and the usual cause of a logo gaining a dark halo.
     */
    private static function canvas(int $width, int $height): GdImage
    {
        $canvas = imagecreatetruecolor(max(1, $width), max(1, $height));

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);

        // GD returns false when the palette is exhausted, which cannot happen
        // on a fresh truecolour canvas — but filling with a `false` colour
        // would silently paint index 0 (black) over the whole image.
        if ($transparent !== false) {
            imagefill($canvas, 0, 0, $transparent);
        }

        imagealphablending($canvas, true);

        return $canvas;
    }

    /**
     * Checked on the already-resized image, where the pixel count is bounded,
     * rather than on a source that may be tens of megapixels.
     */
    private static function hasTransparency(GdImage $image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);

        for ($x = 0; $x < $width; $x++) {
            for ($y = 0; $y < $height; $y++) {
                if (((imagecolorat($image, $x, $y) >> 24) & 0x7F) > 0) {
                    return true;
                }
            }
        }

        return false;
    }

    private static function encodePng(GdImage $image): string
    {
        imagesavealpha($image, true);

        ob_start();
        imagepng($image, null, 9);

        return (string) ob_get_clean();
    }

    /**
     * JPEG has no alpha, so the image is composited onto white first —
     * otherwise GD fills the transparent areas with black.
     */
    private static function encodeJpeg(GdImage $image): string
    {
        $flattened = imagecreatetruecolor(max(1, imagesx($image)), max(1, imagesy($image)));

        $white = imagecolorallocate($flattened, 255, 255, 255);

        if ($white !== false) {
            imagefill($flattened, 0, 0, $white);
        }
        imagecopy($flattened, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));

        ob_start();
        imagejpeg($flattened, null, self::JPEG_QUALITY);
        $bytes = (string) ob_get_clean();

        imagedestroy($flattened);

        return $bytes;
    }
}
