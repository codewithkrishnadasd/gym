<?php

declare(strict_types=1);

use App\Livewire\Members\Index as MemberIndex;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Lists grow as the reader scrolls: a first screen of rows, a "load more"
 * foot that asks for the next screen, and a fresh start whenever a filter
 * changes. No page numbers anywhere.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'more.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);

    foreach (range(1, 34) as $i) {
        Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $club->id, 'name' => sprintf('Member %02d', $i)]);
    }
});

it('shows the first screen, loads the next on request, and stops when everything is shown', function (): void {
    $list = Livewire::test(MemberIndex::class)
        ->assertSee('Member 01')
        ->assertSee('Member 15')
        ->assertDontSee('Member 16')
        ->assertSee('Showing 15 of 34 members')
        ->assertSee('Load more')
        ->assertDontSee('?page=', false);

    $list->call('loadMore')
        ->assertSee('Member 16')
        ->assertSee('Member 30')
        ->assertDontSee('Member 31')
        ->assertSee('Showing 30 of 34 members');

    $list->call('loadMore')
        ->assertSee('Member 34')
        ->assertDontSee('Load more')
        ->assertSee('All 34 members shown');
});

it('starts over from the first screen when a filter changes', function (): void {
    Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => Club::query()->firstOrFail()->id, 'name' => 'Zed Zulu']);

    Livewire::test(MemberIndex::class)
        ->call('loadMore')
        ->assertSet('limit', 30)
        ->set('search', 'Zulu')
        ->assertSet('limit', 15)
        ->assertSee('Zed Zulu')
        ->assertDontSee('Member 01')
        ->assertDontSee('Load more');
});
