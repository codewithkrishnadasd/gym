<?php

declare(strict_types=1);

use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;

/**
 * The global loading bar is on every page, signed in or not, and Livewire's
 * own navigate bar is switched off so the two never appear together.
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

it('renders the loader on the sign-in page', function (): void {
    $this->get('http://loader.test/')
        ->assertOk()
        ->assertSee('data-page-loader', false)
        ->assertSee('data-no-progress-bar', false);
});

it('renders the loader inside the app and tags downloads so they do not trigger it', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->get('http://loader.test/members')
        ->assertOk()
        ->assertSee('data-page-loader', false)
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
