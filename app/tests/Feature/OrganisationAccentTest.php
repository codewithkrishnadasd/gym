<?php

declare(strict_types=1);

use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\User;
use App\Support\Theme\AccentPalette;

/**
 * One accent colour, chosen by the platform admin, derived into a full set of
 * tokens. The derivation is the point: asking anyone to pick four colours per
 * theme is how branding features end up with white text on yellow buttons.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['accent_color' => '#b91c1c']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'accent.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);
});

it('picks a readable foreground for the accent rather than always white', function (): void {
    // Dark red: white text reads. Pale yellow: it does not, and a naive
    // brightness average gets this wrong because green dominates perceived
    // luminance.
    expect(AccentPalette::readableOn('#b91c1c'))->toBe('#ffffff')
        ->and(AccentPalette::readableOn('#facc15'))->toBe('#0f172a')
        ->and(AccentPalette::readableOn('#0e7490'))->toBe('#ffffff')
        ->and(AccentPalette::readableOn('#a3e635'))->toBe('#0f172a');
});

it('lifts the accent for the dark theme instead of reusing the literal colour', function (): void {
    $palette = AccentPalette::for('#0e7490');

    expect($palette['light']['--c-accent'])->toBe('#0e7490')
        ->and($palette['dark']['--c-accent'])->not->toBe('#0e7490')
        // Soft tints sit against their own theme's ground, so they are not the
        // same colour in both.
        ->and($palette['light']['--c-accent-soft'])->not->toBe($palette['dark']['--c-accent-soft']);
});

it('falls back to the built-in accent for anything that is not a hex colour', function (): void {
    foreach (['', 'red', '#fff', 'javascript:alert(1)', '#12345g'] as $bad) {
        expect(AccentPalette::for($bad)['light']['--c-accent'])->toBe(AccentPalette::DEFAULT_ACCENT);
    }
});

it('serves the accent to the signed-in app and the sign-in page alike', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);

    // The sign-in page is branded too — it is the first thing a member sees.
    $this->get('http://accent.test/')->assertOk()->assertSee('--c-accent:#b91c1c', escape: false);

    $this->actingAs($user)
        ->get('http://accent.test/dashboard')
        ->assertOk()
        ->assertSee('--c-accent:#b91c1c', escape: false);
});

it('leaves the built-in palette alone when nothing is configured', function (): void {
    $this->organisation->update(['accent_color' => null]);

    $this->get('http://accent.test/')->assertOk()->assertDontSee('--c-accent:', escape: false);
});

it('is set by the platform admin, not the organisation', function (): void {
    $platformAdmin = PlatformAdmin::factory()->create();

    $this->actingAs($platformAdmin, 'platform')
        ->put('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id, [
            'name' => $this->organisation->name,
            'slug' => $this->organisation->slug,
            'status' => 'active',
            'timezone' => 'UTC',
            'currency_code' => 'INR',
            'locale' => 'en',
            'theme' => ['light' => ['accent' => '#7c3aed']],
            'terminology_member_singular' => 'Member',
            'terminology_member_plural' => 'Members',
            'terminology_user_singular' => 'Staff',
            'terminology_user_plural' => 'Staff',
            'terminology_club_singular' => 'Club',
            'terminology_club_plural' => 'Clubs',
        ])
        ->assertRedirect();

    expect($this->organisation->fresh()?->accent_color)->toBe('#7c3aed');
});

it('rejects anything that is not a hex colour', function (): void {
    $platformAdmin = PlatformAdmin::factory()->create();

    $this->actingAs($platformAdmin, 'platform')
        ->from('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id.'/edit')
        ->put('http://'.config('platform.hostname').'/organisations/'.$this->organisation->id, [
            'name' => $this->organisation->name,
            'slug' => $this->organisation->slug,
            'status' => 'active',
            'timezone' => 'UTC',
            'currency_code' => 'INR',
            'locale' => 'en',
            // A CSS injection attempt, since this value lands inside a <style>.
            'theme' => ['light' => ['accent' => '#fff;} body{display:none']],
            'terminology_member_singular' => 'Member',
            'terminology_member_plural' => 'Members',
            'terminology_user_singular' => 'Staff',
            'terminology_user_plural' => 'Staff',
            'terminology_club_singular' => 'Club',
            'terminology_club_plural' => 'Clubs',
        ])
        ->assertSessionHasErrors('theme.light.accent');

    expect($this->organisation->fresh()?->accent_color)->toBe('#b91c1c');
});
