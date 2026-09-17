<?php

declare(strict_types=1);

use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\User;
use App\Support\Theme\ThemeTokens;
use Illuminate\Testing\TestResponse;

/**
 * The full palette editor: every interface colour, per theme, set by the
 * platform admin and layered over the accent and the built-in defaults.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['accent_color' => '#b91c1c']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'theme.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);
});

function platformUpdate(Organisation $organisation, array $extra): TestResponse
{
    return test()->actingAs(PlatformAdmin::factory()->create(), 'platform')
        ->from('http://'.config('platform.hostname').'/organisations/'.$organisation->id.'/edit')
        ->put('http://'.config('platform.hostname').'/organisations/'.$organisation->id, [
            'name' => $organisation->name,
            'slug' => $organisation->slug,
            'status' => 'active',
            'timezone' => 'UTC',
            'currency_code' => 'INR',
            'locale' => 'en',
            'terminology_member_singular' => 'Member',
            'terminology_member_plural' => 'Members',
            'terminology_user_singular' => 'Staff',
            'terminology_user_plural' => 'Staff',
            'terminology_club_singular' => 'Club',
            'terminology_club_plural' => 'Clubs',
            ...$extra,
        ]);
}

it('mirrors the built-in palette in the stylesheet exactly', function (): void {
    $css = (string) file_get_contents(resource_path('css/app.css'));

    $block = function (string $selector) use ($css): array {
        preg_match('/'.preg_quote($selector, '/').'\s*\{(.*?)\n\}/s', $css, $match);
        preg_match_all('/--c-([a-z-]+):\s*(#[0-9a-f]{6});/', $match[1] ?? '', $pairs, PREG_SET_ORDER);

        return array_column($pairs, 2, 1);
    };

    $light = $block(':root');
    $dark = $block("[data-theme='dark']");

    foreach (ThemeTokens::keys() as $key) {
        expect($light[$key] ?? null)->toBe(ThemeTokens::DEFAULTS['light'][$key], "light --c-{$key}");
        expect($dark[$key] ?? null)->toBe(ThemeTokens::DEFAULTS['dark'][$key], "dark --c-{$key}");
    }
});

it('layers an explicit colour over the accent and the defaults', function (): void {
    $this->organisation->update(['theme_colors' => [
        'light' => ['app' => '#fdf6ee', 'accent' => '#123456'],
        'dark' => ['ink' => '#ffffff'],
    ]]);

    $resolved = ThemeTokens::resolve('#b91c1c', $this->organisation->theme_colors);

    expect($resolved['light']['app'])->toBe('#fdf6ee')
        ->and($resolved['light']['accent'])->toBe('#123456')
        // Untouched derived token still follows the accent.
        ->and($resolved['light']['accent-soft'])->toBe(ThemeTokens::derivedFromAccent('#b91c1c')['light']['accent-soft'])
        ->and($resolved['dark']['ink'])->toBe('#ffffff')
        ->and($resolved['dark']['app'])->toBe(ThemeTokens::DEFAULTS['dark']['app']);

    $css = (string) $this->organisation->fresh()?->themeCss();

    expect($css)->toContain('--c-app:#fdf6ee;')
        ->and($css)->toContain("[data-theme='dark']{")
        ->and($css)->toContain('--c-ink:#ffffff;')
        ->and(substr_count($css, '--c-accent:'))->toBe(2);
});

it('serves the palette to the app and the sign-in page', function (): void {
    $this->organisation->update(['theme_colors' => ['light' => ['button-secondary' => '#eeeeee']]]);

    $this->get('http://theme.test/')->assertOk()->assertSee('--c-button-secondary:#eeeeee;', false);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);

    $this->actingAs($user)->get('http://theme.test/dashboard')->assertOk()->assertSee('--c-button-secondary:#eeeeee;', false);
});

it('uses the chosen primary colour for the browser chrome and app icon', function (): void {
    expect($this->organisation->brandColor())->toBe('#b91c1c');

    $this->organisation->update(['theme_colors' => ['light' => ['accent' => '#00695c']]]);

    expect($this->organisation->fresh()?->brandColor())->toBe('#00695c');

    $this->get('http://theme.test/manifest.webmanifest')->assertOk()->assertJsonPath('theme_color', '#00695c');
});

it('is saved by the platform admin, keeping only deliberate valid choices', function (): void {
    platformUpdate($this->organisation, [
        'theme' => [
            'light' => ['app' => '#FAF7F2', 'ink' => '', 'bogus' => '#000000'],
            'dark' => ['surface' => '#111111'],
            'sepia' => ['app' => '#000000'],
        ],
    ])->assertRedirect()->assertSessionHasNoErrors();

    // jsonb does not preserve key order, so compare shape rather than sequence.
    expect($this->organisation->fresh()?->theme_colors)->toEqualCanonicalizing([
        'light' => ['app' => '#faf7f2'],
        'dark' => ['surface' => '#111111'],
    ]);
});

it('clears the palette when every colour is put back to default', function (): void {
    $this->organisation->update(['theme_colors' => ['light' => ['app' => '#faf7f2']]]);

    platformUpdate($this->organisation, ['theme' => ['light' => ['app' => '', 'accent' => '#b91c1c'], 'dark' => []]])->assertRedirect();

    expect($this->organisation->fresh()?->theme_colors)->toBe(['light' => ['accent' => '#b91c1c']])
        ->and($this->organisation->fresh()?->accent_color)->toBe('#b91c1c')
        ->and($this->organisation->fresh()?->themeCss())->toContain('--c-accent:#b91c1c;');
});

it('takes the brand accent from the palette\'s light primary, and shows an older accent there', function (): void {
    // Set before the palette existed: the form offers it as the light primary
    // so saving without changes keeps it.
    $this->actingAs(PlatformAdmin::factory()->create(), 'platform')
        ->get('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id.'/edit?tab=appearance')
        ->assertOk()
        ->assertSee('accent\u0022:\u0022#b91c1c', false)
        ->assertDontSee('name="accent_color"', false)
        ->assertDontSee('Hide full palette');

    platformUpdate($this->organisation, ['theme' => ['light' => ['accent' => '#00695c'], 'dark' => []]])->assertRedirect();

    expect($this->organisation->fresh()?->accent_color)->toBe('#00695c')
        ->and($this->organisation->fresh()?->brandColor())->toBe('#00695c');

    // Reset to default clears the accent too.
    platformUpdate($this->organisation, ['theme' => []])->assertRedirect();

    expect($this->organisation->fresh()?->accent_color)->toBeNull();
});

it('rejects a colour that is not a hex value', function (): void {
    platformUpdate($this->organisation, ['theme' => ['light' => ['app' => 'red;} body{display:none']]])
        ->assertRedirect()
        ->assertSessionHasErrors('theme.light.app');

    expect($this->organisation->fresh()?->theme_colors)->toBeNull();
});

it('shows every token for both themes in the platform editor', function (): void {
    $response = $this->actingAs(PlatformAdmin::factory()->create(), 'platform')
        ->get('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id.'/edit?tab=appearance')
        ->assertOk()
        ->assertSee('Appearance')
        ->assertSee('Secondary button')
        ->assertSee('Text on primary button');

    foreach (ThemeTokens::keys() as $key) {
        $response->assertSee('name="theme[light]['.$key.']"', false)
            ->assertSee('name="theme[dark]['.$key.']"', false);
    }
});

it('measures contrast the WCAG way', function (): void {
    expect(ThemeTokens::contrast('#000000', '#ffffff'))->toBe(21.0)
        ->and(ThemeTokens::contrast('#ffffff', '#ffffff'))->toBe(1.0)
        ->and(ThemeTokens::contrast(ThemeTokens::DEFAULTS['light']['ink'], ThemeTokens::DEFAULTS['light']['app']))->toBeGreaterThan(4.5)
        ->and(ThemeTokens::contrast(ThemeTokens::DEFAULTS['dark']['on-accent'], ThemeTokens::DEFAULTS['dark']['accent']))->toBeGreaterThan(4.5);
});
