<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Serves an organisation's logo and favicon.
 *
 * Deliberately unauthenticated: both appear on the sign-in page, where there
 * is no session yet, and a browser fetches the favicon without cookies anyway.
 * That is safe here because these two files are the only ones reachable — the
 * path comes from the organisation record, never from the request — and both
 * were re-encoded by App\Support\Images\BrandImage rather than being whatever
 * bytes somebody uploaded.
 */
class BrandingController extends Controller
{
    public function logo(): StreamedResponse
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return $this->stream($organisation->logo_path);
    }

    public function favicon(): StreamedResponse
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return $this->stream($organisation->favicon_path);
    }

    private function stream(?string $path): StreamedResponse
    {
        abort_if($path === null, 404);

        $disk = Storage::disk(config('filesystems.default'));

        abort_unless($disk->exists($path), 404);

        return $disk->response($path, headers: [
            // The filename carries a random component and changes on every
            // upload, so a cached copy can never be the wrong one. The URL is
            // versioned by the caller for the same reason.
            'Cache-Control' => 'public, max-age=604800',
        ]);
    }
}
