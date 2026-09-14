<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

/**
 * Guards against the layout mistake that made the member page scroll sideways
 * on a phone.
 *
 * `shrink-0` and `flex-wrap` on the same element cancel each other out: the box
 * is told never to narrow, so the wrapping it was given can never trigger, and
 * a row of four buttons ends up wider than the viewport. It reads as harmless —
 * both classes look like they are doing something — which is exactly why it
 * needs a test rather than a comment.
 */
it('never combines shrink-0 with flex-wrap on the same element', function (): void {
    $offenders = [];

    foreach (File::allFiles(resource_path('views')) as $file) {
        $contents = $file->getContents();

        preg_match_all('/class="([^"]*)"/', $contents, $matches);

        foreach ($matches[1] as $classList) {
            $classes = preg_split('/\s+/', $classList) ?: [];

            // Responsive variants are fine — `sm:shrink-0` with a plain
            // `flex-wrap` only locks the box at that breakpoint and up.
            if (in_array('shrink-0', $classes, true) && in_array('flex-wrap', $classes, true)) {
                $offenders[] = $file->getRelativePathname().': '.$classList;
            }
        }
    }

    expect($offenders)->toBe([]);
});

/**
 * Anything wider than a phone screen has to bring its own horizontal scroll,
 * or it takes the whole page with it.
 */
it('keeps every fixed wide element inside a scroll container', function (): void {
    $offenders = [];

    foreach (File::allFiles(resource_path('views')) as $file) {
        // PDFs are rendered to paper, not to a viewport.
        if (str_starts_with($file->getRelativePathname(), 'pdf/')) {
            continue;
        }

        $contents = $file->getContents();

        preg_match_all('/min-width:\s*(\d{3,})px|\bmin-w-\[(\d{3,})px\]/', $contents, $matches, PREG_OFFSET_CAPTURE);

        foreach ($matches[0] as [$match, $offset]) {
            // A responsive variant only bites above that breakpoint.
            $prefix = substr($contents, max(0, $offset - 4), 4);

            if (str_contains($prefix, ':')) {
                continue;
            }

            $context = substr($contents, max(0, $offset - 500), 500);

            if (! str_contains($context, 'overflow-x-auto') && ! str_contains($context, 'overflow-auto')) {
                $offenders[] = $file->getRelativePathname().': '.$match;
            }
        }
    }

    expect($offenders)->toBe([]);
});
