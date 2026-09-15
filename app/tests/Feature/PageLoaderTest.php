<?php

declare(strict_types=1);

use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;

/**
 * Loading feedback lives where the work happens — on the list that is
 * updating, on the menu item that was clicked — never as a full-screen veil.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'loader.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);
});

it('has no full-screen loader; lists carry their own and navigation is in-place', function (): void {
    $this->get('http://loader.test/')
        ->assertOk()
        ->assertDontSee('data-page-loader', false)
        // Livewire's own navigate bar stays off as well.
        ->assertSee('data-no-progress-bar', false);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->get('http://loader.test/members')
        ->assertOk()
        ->assertDontSee('data-page-loader', false)
        // The list dims and shows its own updating indicator.
        ->assertSee('aria-label="Updating"', false)
        ->assertSee('Updating…')
        // Menu items swap the page in place and prefetch on hover, with a
        // spinner on the item that was clicked.
        ->assertSee('wire:navigate.hover', false)
        ->assertSee('busy = true', false)
        ->assertSee('data-download', false);
});

it('asks before every download', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->get('http://loader.test/members')
        ->assertOk()
        ->assertSee('data-confirm="Download a CSV of the members shown? It uses the filters currently applied."', false)
        ->assertSee('data-confirm-action="Download"', false);

    $this->get('http://loader.test/reports')
        ->assertOk()
        ->assertSee('data-confirm="Download this report as a CSV?', false)
        ->assertSee('data-confirm="Download the PDF summary of this report?', false);
});
