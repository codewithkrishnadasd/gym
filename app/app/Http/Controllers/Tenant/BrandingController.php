<?php

declare(strict_types=1);

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Support\Images\AppIcon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    /** Sizes Chrome expects for an installable app. */
    private const MANIFEST_ICON_SIZES = [192, 512];

    /** Every size the icon route will render — 64 is the browser-tab fallback. */
    private const ICON_SIZES = [64, 192, 512];

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

    /**
     * The installed-app icon, at whichever size the manifest asked for.
     *
     * Rendered on demand rather than stored: it is derived from the logo and
     * the accent colour, both of which an admin can change, and a stale icon
     * cached in an operating system's dock is not something the platform can
     * reach in to fix.
     */
    public function appIcon(Request $request): Response
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        $size = (int) $request->integer('size', 512);
        $size = in_array($size, self::ICON_SIZES, true) ? $size : 512;

        $logo = null;
        $path = $organisation->logo_path;

        if ($path !== null) {
            $disk = Storage::disk(config('filesystems.default'));

            $logo = $disk->exists($path) ? $disk->get($path) : null;
        }

        $png = AppIcon::render(
            $organisation->brandColor(),
            $size,
            $logo,
        );

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }

    /**
     * The web app manifest, which is what makes Chrome offer to install this.
     *
     * Served per tenant so each gym installs as its own application — its name
     * on the dock, its accent as the window colour — rather than every
     * organisation collapsing into one shared entry.
     */
    public function manifest(): JsonResponse
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        $accent = $organisation->brandColor();

        $icons = array_map(static fn (int $size): array => [
            'src' => route('tenant.branding.app-icon', ['size' => $size]),
            'sizes' => $size.'x'.$size,
            'type' => 'image/png',
            // The icon is drawn full-bleed with its mark inside the safe zone,
            // so it can be masked into a circle or squircle without cropping.
            'purpose' => 'any maskable',
        ], self::MANIFEST_ICON_SIZES);

        return response()->json([
            'name' => $organisation->name,
            // What fits under a dock or home-screen icon. Cut at a word rather
            // than mid-syllable: "PowerHouse Gym" becomes "PowerHouse", not
            // "PowerHouse G".
            'short_name' => Str::length($organisation->name) <= 12
                ? $organisation->name
                : Str::limit(Str::words($organisation->name, 1, ''), 12, ''),
            'description' => 'Member, attendance, and fee management for '.$organisation->name.'.',
            // Opens on the dashboard rather than the sign-in page: an installed
            // app that always lands on a login form feels broken to someone who
            // is already signed in.
            'start_url' => route('tenant.dashboard', absolute: false),
            'scope' => '/',
            'display' => 'standalone',
            'orientation' => 'any',
            'theme_color' => $accent,
            'background_color' => '#080d18',
            'icons' => $icons,
        ], 200, [
            'Content-Type' => 'application/manifest+json',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }

    /**
     * A deliberately inert service worker.
     *
     * Chrome will not offer to install a site without one registered that has a
     * fetch handler, so this exists to satisfy that and nothing else: it passes
     * every request straight to the network and caches none of it. Caching
     * would mean an installed app could keep serving a build from before the
     * last deploy, with no way for anyone to clear it remotely — a much worse
     * problem than the one offline support would solve.
     */
    public function serviceWorker(): Response
    {
        $script = <<<'JS'
        self.addEventListener('install', () => self.skipWaiting());
        self.addEventListener('activate', (event) => event.waitUntil(self.clients.claim()));

        // Required for installability. Intentionally a pass-through: nothing is
        // cached, so an installed window always sees the current deploy.
        self.addEventListener('fetch', () => {});
        JS;

        return response($script, 200, [
            'Content-Type' => 'application/javascript',
            // Scope is the whole site, which the browser only allows when the
            // worker itself is served from the root.
            'Service-Worker-Allowed' => '/',
            'Cache-Control' => 'no-cache',
        ]);
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
