<?php

declare(strict_types=1);

use App\Livewire\Settings\OrganisationSettings;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Support\Navigation;
use Livewire\Livewire;

/**
 * An admin chooses what the phone tab bar shows — and with which icons —
 * and what the dashboard's floating button does. The Menu tab is fixed.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'nav.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);
});

it('starts from the built-in bar and saves a custom one with icons', function (): void {
    Livewire::test(OrganisationSettings::class, ['tab' => 'navigation'])
        ->assertSet('mobileTabs.0.route', 'tenant.dashboard')
        ->assertSet('mobileTabs.1.route', 'tenant.members.index')
        ->set('mobileTabs.0.route', 'tenant.tasks.index')
        ->assertSet('mobileTabs.0.icon', 'check-circle')
        ->set('mobileTabs.0.icon', 'bolt')
        ->set('mobileTabs.1.route', 'tenant.finance.payments.index')
        ->set('mobileTabs.2.route', '')
        ->set('mobileTabs.3.route', '')
        ->set('quickAction', 'tasks')
        ->call('saveNavigation')
        ->assertHasNoErrors();

    $organisation = $this->organisation->fresh();

    expect($organisation->mobileNavigation())->toBe([
        ['route' => 'tenant.tasks.index', 'icon' => 'bolt'],
        ['route' => 'tenant.finance.payments.index', 'icon' => 'banknotes'],
    ])->and($organisation->quickAction())->toBe('tasks');

    // The bar follows: those two tabs, in order, then Menu — and the icon.
    $html = $this->get('http://nav.test/dashboard')->assertOk()->getContent();
    $start = strpos($html, 'aria-label="Primary"');
    $bar = substr($html, $start, strpos($html, '</nav>', $start) - $start);
    expect($bar)->toContain('Tasks')->toContain('Payments')->not->toContain('Members')->toContain('Menu');

    // As does the dashboard button.
    $this->get('http://nav.test/dashboard')->assertSee('aria-label="New task"', false)->assertDontSee('aria-label="Collect fee"', false);
});

it('leaves out a tab the viewer may not open, and refuses unknown routes and icons', function (): void {
    $this->organisation->update(['navigation_settings' => ['mobile' => [
        ['route' => 'tenant.finance.expenses.index', 'icon' => 'fire'],
        ['route' => 'tenant.tasks.index', 'icon' => 'star'],
    ]]]);

    $staffUser = User::factory()->create();
    OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => $staffUser->id, 'permissions' => []]);
    app()->forgetInstance('membership');

    // Expenses are admin-only, so a staff member's bar has Tasks and Menu only.
    $html = $this->actingAs($staffUser)->get('http://nav.test/dashboard')->assertOk()->getContent();
    $start = strpos($html, 'aria-label="Primary"');
    $bar = substr($html, $start, strpos($html, '</nav>', $start) - $start);
    expect($bar)->toContain('Tasks')->not->toContain('Expenses');

    app()->forgetInstance('membership');
    $this->actingAs($this->admin->user);

    Livewire::test(OrganisationSettings::class, ['tab' => 'navigation'])
        ->set('mobileTabs.0.route', 'tenant.nowhere')
        ->set('mobileTabs.1.icon', 'not-an-icon')
        ->call('saveNavigation')
        ->assertHasErrors(['mobileTabs.0.route', 'mobileTabs.1.icon']);
});

it('falls back through the quick actions to one the viewer may do', function (): void {
    $this->organisation->update(['navigation_settings' => ['quick_action' => 'expenses']]);

    $staffUser = User::factory()->create();
    OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => $staffUser->id, 'permissions' => ['members.create' => true, 'members.view' => true]]);
    app()->forgetInstance('membership');

    // Staff cannot record expenses, so they get the first action they can.
    expect(Navigation::quickAction($this->organisation->fresh(), $staffUser)['label'])->toBe('Add member');
});
