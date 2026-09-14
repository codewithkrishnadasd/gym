<?php

declare(strict_types=1);

use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\User;

/**
 * Chrome only offers to install a site that meets every one of its criteria: a
 * manifest with a name, a start URL, a standalone display mode, icons at 192
 * and 512, and a registered service worker with a fetch handler. Miss one and
 * `beforeinstallprompt` never fires — and because the button is gated on that
 * event, it simply never appears, with nothing to explain why.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create([
        'name' => 'PowerHouse Gym',
        'accent_color' => '#b91c1c',
    ]);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'install.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->user = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $this->user->id,
    ]);
});

it('serves a manifest meeting every install criterion', function (): void {
    $manifest = $this->get('http://install.test/manifest.webmanifest')->assertOk()->json();

    expect($manifest['name'])->toBe('PowerHouse Gym')
        ->and($manifest['short_name'])->not->toBeEmpty()
        ->and($manifest['display'])->toBe('standalone')
        ->and($manifest['start_url'])->toBe('/dashboard')
        ->and($manifest['theme_color'])->toBe('#b91c1c');

    // Chrome requires at least a 192 and a 512.
    $sizes = array_column($manifest['icons'], 'sizes');

    expect($sizes)->toContain('192x192')->toContain('512x512');
});

it('renders a real square PNG at both icon sizes', function (): void {
    foreach ([192, 512] as $size) {
        $response = $this->get('http://install.test/branding/app-icon?size='.$size)
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $image = imagecreatefromstring($response->getContent());

        expect($image)->not->toBeFalse()
            ->and(imagesx($image))->toBe($size)
            ->and(imagesy($image))->toBe($size);
    }
});

it('falls back to a generated mark when the organisation has no logo', function (): void {
    $response = $this->get('http://install.test/branding/app-icon?size=192')->assertOk();

    $image = imagecreatefromstring($response->getContent());

    // Drawn in the accent colour, so the corner is the accent and the centre
    // is the readable ink — an empty or transparent icon would fail both.
    $corner = imagecolorsforindex($image, imagecolorat($image, 4, 4));
    $centre = imagecolorsforindex($image, imagecolorat($image, 96, 96));

    expect(sprintf('#%02x%02x%02x', $corner['red'], $corner['green'], $corner['blue']))->toBe('#b91c1c')
        ->and(sprintf('#%02x%02x%02x', $centre['red'], $centre['green'], $centre['blue']))->toBe('#ffffff');
});

it('refuses an icon size the manifest never asks for', function (): void {
    // The size is a query parameter, so it has to be an allowlist rather than
    // anything a caller can name — 20000 would be a memory exhaustion.
    $response = $this->get('http://install.test/branding/app-icon?size=20000')->assertOk();

    expect(imagesx(imagecreatefromstring($response->getContent())))->toBe(512);
});

it('serves a service worker with a fetch handler from the root', function (): void {
    $response = $this->get('http://install.test/sw.js')
        ->assertOk()
        ->assertHeader('Service-Worker-Allowed', '/');

    expect($response->getContent())
        ->toContain("addEventListener('fetch'")
        // Caching would let an installed window keep serving a stale deploy.
        ->not->toContain('caches.open');
});

it('links the manifest from a signed-in page', function (): void {
    $this->actingAs($this->user)
        ->get('http://install.test/dashboard')
        ->assertOk()
        ->assertSee('rel="manifest"', escape: false)
        ->assertSee('Install as app');
});

it('offers nothing to install on the platform console', function (): void {
    // The root console is an operator tool on its own hostname; it has no
    // manifest, so the button must not be rendered there either.
    $platformAdmin = PlatformAdmin::factory()->create();

    $this->actingAs($platformAdmin, 'platform')
        ->get('http://'.config('platform.hostname').'/dashboard')
        ->assertOk()
        ->assertDontSee('rel="manifest"', escape: false)
        ->assertDontSee('Install as app');
});
