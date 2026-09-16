<?php

declare(strict_types=1);

use App\Enums\MemberStatus;
use App\Livewire\Members\Index as MemberIndex;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * A list remembers how it was narrowed: leave through the menu, come back,
 * and the same filters are on. A link that names its own filters still wins.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'remember.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'North']);
    Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Paused Pat', 'status' => MemberStatus::Paused]);
    Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Active Ana', 'status' => MemberStatus::Active]);
});

it('brings the last filters back when the list is opened plain', function (): void {
    Livewire::test(MemberIndex::class)
        ->set('status', 'paused')
        ->set('search', 'Pat')
        ->assertSee('Paused Pat')
        ->assertDontSee('Active Ana');

    // Opened from the menu — no filters in the URL — the narrowing is back.
    $this->get('http://remember.test/members')
        ->assertOk()
        ->assertSee('Paused Pat')
        ->assertDontSee('Active Ana');

    Livewire::test(MemberIndex::class)->assertSet('status', 'paused')->assertSet('search', 'Pat');
});

it('lets a link with its own filters override what was remembered', function (): void {
    Livewire::test(MemberIndex::class)->set('status', 'paused');

    $this->get('http://remember.test/members?status=active')
        ->assertOk()
        ->assertSee('Active Ana')
        ->assertDontSee('Paused Pat');

    Livewire::withQueryParams(['status' => 'active'])->test(MemberIndex::class)->assertSet('status', 'active')->assertSet('search', '');
});

it('forgets a filter once it is cleared', function (): void {
    Livewire::test(MemberIndex::class)->set('status', 'paused')->set('status', '');

    $this->get('http://remember.test/members')->assertOk()->assertSee('Active Ana')->assertSee('Paused Pat');
});

it('does not reinstate a club filter after the Clubs module is switched off', function (): void {
    Livewire::test(MemberIndex::class)->set('club', (string) $this->club->id);

    $this->organisation->update(['features' => ['members']]);
    app()->instance('tenant', $this->organisation->fresh());

    Livewire::test(MemberIndex::class)->assertSet('club', '');
});
