<?php

declare(strict_types=1);

use App\Support\Images\BrandImage;

/**
 * One upload has to produce two usable images without an operator thinking
 * about formats or sizes. The cases that matter are the ones that look fine
 * until someone views them: a transparent logo flattened onto black, a wide
 * logo squashed into a square favicon, and a phone photo served at full size.
 */
function writeTestImage(string $extension, int $width, int $height, bool $transparent = false): string
{
    $path = sys_get_temp_dir().'/brand-'.uniqid().'.'.$extension;

    $image = imagecreatetruecolor($width, $height);

    if ($transparent) {
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagefill($image, 0, 0, (int) imagecolorallocatealpha($image, 0, 0, 0, 127));
        imagefilledrectangle($image, 0, 0, (int) ($width / 2), $height, (int) imagecolorallocate($image, 220, 40, 40));
    } else {
        imagefill($image, 0, 0, (int) imagecolorallocate($image, 30, 90, 200));
    }

    $extension === 'png' ? imagepng($image, $path) : imagejpeg($image, $path, 95);
    imagedestroy($image);

    return $path;
}

it('scales a large photo down and keeps it small', function (): void {
    $source = writeTestImage('jpg', 2400, 1600);

    $derived = BrandImage::derive($source);

    $logo = imagecreatefromstring($derived['logo']);

    expect($logo)->not->toBeFalse()
        ->and(max(imagesx($logo), imagesy($logo)))->toBe(BrandImage::LOGO_MAX_EDGE)
        // Aspect ratio preserved rather than stretched to a square.
        ->and(imagesx($logo) / imagesy($logo))->toEqualWithDelta(1.5, 0.01)
        ->and(strlen($derived['logo']))->toBeLessThan(filesize($source));

    unlink($source);
});

it('crops the favicon to a square at the icon size', function (): void {
    $source = writeTestImage('jpg', 1200, 300);

    $derived = BrandImage::derive($source);

    $favicon = imagecreatefromstring($derived['favicon']);

    expect(imagesx($favicon))->toBe(BrandImage::FAVICON_EDGE)
        ->and(imagesy($favicon))->toBe(BrandImage::FAVICON_EDGE);

    unlink($source);
});

it('keeps a transparent logo transparent instead of flattening it onto black', function (): void {
    $source = writeTestImage('png', 400, 400, transparent: true);

    $derived = BrandImage::derive($source);

    expect($derived['logoExtension'])->toBe('png');

    $logo = imagecreatefromstring($derived['logo']);

    // The right half of the source was left transparent.
    $alpha = (imagecolorat($logo, imagesx($logo) - 2, 2) >> 24) & 0x7F;

    expect($alpha)->toBeGreaterThan(0);

    unlink($source);
});

it('encodes an opaque logo as JPEG, which is far smaller than PNG', function (): void {
    $source = writeTestImage('png', 600, 600);

    $derived = BrandImage::derive($source);

    expect($derived['logoExtension'])->toBe('jpg');

    unlink($source);
});

it('never enlarges an image that is already small', function (): void {
    $source = writeTestImage('png', 64, 64, transparent: true);

    $logo = imagecreatefromstring(BrandImage::derive($source)['logo']);

    expect(imagesx($logo))->toBe(64);

    unlink($source);
});

it('rejects a file that is not an image GD can read', function (): void {
    $path = sys_get_temp_dir().'/brand-'.uniqid().'.png';
    file_put_contents($path, 'this is not a picture');

    expect(fn () => BrandImage::derive($path))->toThrow(RuntimeException::class);
    expect(BrandImage::isReadable($path))->toBeFalse();

    unlink($path);
});
