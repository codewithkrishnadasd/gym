<?php

declare(strict_types=1);

use App\Livewire\Settings\BillableItems;
use App\Livewire\Settings\OrganisationSettings;
use App\Models\AuditEvent;
use App\Models\BillableItem;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\PlatformAdmin;
use App\Models\User;
use App\Support\SettingsTabs;
use Livewire\Livewire;

/**
 * The platform admin's page for an organisation is tabbed like the
 * organisation's own settings page, and carries every tab that page has —
 * so a gym's settings can be changed from the console, without a membership
 * in the gym, with the change recorded against no member.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create([
        'name' => 'Console Gym',
        'features' => ['members', 'billing', 'tasks', 'messaging', 'expenses', 'documents'],
    ]);
    Domain::factory()->create(['organisation_id' => $this->organisation->id, 'hostname' => 'console.test', 'status' => 'active', 'is_primary' => true]);

    $this->platformAdmin = PlatformAdmin::factory()->create();
    $this->editUrl = 'http://'.config('platform.hostname').'/organisations/'.$this->organisation->id.'/edit';
});

it('lists the console tabs before the organisation tabs', function (): void {
    $tabs = SettingsTabs::for($this->organisation, true);

    expect(array_keys($tabs))->toBe([
        'general', 'features', 'appearance', 'domains', 'people',
        'profile', 'terminology', 'notifications', 'expenses', 'billing', 'storage', 'tasks', 'templates',
    ])
        // The organisation's own page has only its own tabs.
        ->and(array_keys(SettingsTabs::for($this->organisation, false)))->toBe([
            'profile', 'terminology', 'notifications', 'expenses', 'billing', 'storage', 'tasks', 'templates',
        ])
        // A module's tab exists only while the module does.
        ->and(SettingsTabs::for(Organisation::factory()->create(['features' => ['members']]), true))->not->toHaveKey('billing')
        // An unknown tab lands on the first.
        ->and(SettingsTabs::resolve($this->organisation, true, 'bogus'))->toBe('general')
        ->and(SettingsTabs::resolve($this->organisation, false, 'bogus'))->toBe('profile');
});

it('shows one tab at a time on the console page', function (): void {
    $this->actingAs($this->platformAdmin, 'platform')
        ->get($this->editUrl)
        ->assertOk()
        ->assertSee('name="section" value="general"', false)
        ->assertSee('Currency code')
        ->assertDontSee('name="features[]"', false)
        ->assertSee('?tab=profile')
        ->assertSee('?tab=billing');

    $this->actingAs($this->platformAdmin, 'platform')
        ->get($this->editUrl.'?tab=domains')
        ->assertOk()
        ->assertSee('Add domain')
        ->assertDontSee('Currency code');
});

it('renders the organisation settings tabs for the platform admin', function (): void {
    $this->actingAs($this->platformAdmin, 'platform')
        ->get($this->editUrl.'?tab=profile')
        ->assertOk()
        ->assertSee('Organisation profile')
        ->assertSee('Console Gym')
        // The settings component leaves the heading and tab row to the page.
        ->assertSeeInOrder(['Console Gym', 'Organisation profile'])
        ->assertDontSee('/settings/organisation?tab=');

    $this->actingAs($this->platformAdmin, 'platform')
        ->get($this->editUrl.'?tab=billing')
        ->assertOk()
        ->assertSee('Price list');
});

it('saves an organisation setting from the console with no member as actor', function (): void {
    $this->actingAs($this->platformAdmin, 'platform');

    Livewire::withQueryParams(['tab' => 'profile'])
        ->test(OrganisationSettings::class, ['platformOrganisationId' => $this->organisation->id])
        ->set('name', 'Renamed from the console')
        ->call('saveProfile')
        ->assertHasNoErrors();

    expect($this->organisation->fresh()?->name)->toBe('Renamed from the console');

    $event = AuditEvent::query()->withoutGlobalScopes()
        ->where('organisation_id', $this->organisation->id)
        ->where('action', 'organisation.profile_updated')
        ->firstOrFail();

    expect($event->actor_user_id)->toBeNull()
        ->and($event->actor_role)->toBe(AuditEvent::PLATFORM_ROLE);
});

it('manages a module\'s settings from the console', function (): void {
    $this->actingAs($this->platformAdmin, 'platform');

    Livewire::test(BillableItems::class, ['platformOrganisationId' => $this->organisation->id])
        ->call('startCreate')
        ->set('name', 'Locker')
        ->set('price', '300')
        ->call('save')
        ->assertHasNoErrors();

    $item = BillableItem::query()->withoutGlobalScopes()->where('name', 'Locker')->firstOrFail();

    expect($item->organisation_id)->toBe($this->organisation->id)
        ->and($item->created_by)->toBeNull();
});

it('refuses the console mode to anyone but a platform admin', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);

    // A member of another organisation must not reach this one by naming it.
    $other = Organisation::factory()->create();

    $this->actingAs($user);

    Livewire::test(OrganisationSettings::class, ['platformOrganisationId' => $other->id])
        ->assertForbidden();
});

it('keeps the organisation\'s own settings page as it was', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);

    $this->actingAs($user)
        ->get('http://console.test/settings/organisation?tab=profile')
        ->assertOk()
        ->assertSee('Organisation profile')
        ->assertSee('/settings/organisation?tab=billing')
        ->assertDontSee('?tab=features');
});
