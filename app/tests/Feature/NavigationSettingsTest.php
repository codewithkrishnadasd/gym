<?php

declare(strict_types=1);

use App\Livewire\Account\NavigationSettings;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Support\Navigation;
use Livewire\Livewire;

/**
 * Each person arranges their own phone tab bar — which tabs, which icons —
 * and what the dashboard's floating button does. The Menu tab is fixed,
 * and one person's arrangement never touches another's.
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

function tabBar(string $html): string
{
    $start = strpos($html, 'aria-label="Primary"');

    return substr($html, $start, strpos($html, '</nav>', $start) - $start);
}

it('starts from the built-in bar and saves a personal one with icons and a dashboard button', function (): void {
    Livewire::test(NavigationSettings::class)
        ->assertSet('mobileTabs.0.route', 'tenant.dashboard')
        ->assertSet('mobileTabs.1.route', 'tenant.members.index')
        ->set('mobileTabs.0.route', 'tenant.tasks.index')
        ->assertSet('mobileTabs.0.icon', 'check-circle')
        ->set('mobileTabs.0.icon', 'bolt')
        ->set('mobileTabs.1.route', 'tenant.finance.payments.index')
        ->set('mobileTabs.2.route', '')
        ->set('mobileTabs.3.route', '')
        ->set('quickAction', 'tasks')
        ->call('save')
        ->assertHasNoErrors();

    $membership = $this->admin->fresh();

    expect($membership->mobileNavigation())->toBe([
        ['route' => 'tenant.tasks.index', 'icon' => 'bolt'],
        ['route' => 'tenant.finance.payments.index', 'icon' => 'banknotes'],
    ])->and($membership->quickAction())->toBe('tasks');

    $html = $this->get('http://nav.test/dashboard')->assertOk()->assertSee('aria-label="New task"', false)->getContent();
    expect(tabBar($html))->toContain('Tasks')->toContain('Payments')->not->toContain('Members')->toContain('Menu');

    // The page is reachable from the account menu, and can go back to the default.
    $this->get('http://nav.test/me/navigation')->assertOk()->assertSee('Back to the built-in arrangement');
    Livewire::test(NavigationSettings::class)->call('reset_');
    expect($this->admin->fresh()->navigation_settings)->toBeNull();
});

it('is personal: another member of staff keeps the built-in bar, and cannot pick tabs they may not open', function (): void {
    $this->admin->update(['navigation_settings' => ['mobile' => [['route' => 'tenant.finance.expenses.index', 'icon' => 'fire']]]]);

    $staffUser = User::factory()->create();
    OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => $staffUser->id, 'permissions' => ['members.view' => true]]);
    app()->forgetInstance('membership');

    $html = $this->actingAs($staffUser)->get('http://nav.test/dashboard')->assertOk()->getContent();
    expect(tabBar($html))->toContain('Members')->not->toContain('Expenses');

    Livewire::actingAs($staffUser)->test(NavigationSettings::class)
        ->set('mobileTabs.0.route', 'tenant.finance.expenses.index')
        ->set('mobileTabs.1.icon', 'not-an-icon')
        ->call('save')
        ->assertHasErrors(['mobileTabs.0.route', 'mobileTabs.1.icon']);
});

it('falls back through the quick actions to one the person may do', function (): void {
    $staffUser = User::factory()->create();
    $staff = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $staffUser->id,
        'permissions' => ['members.create' => true, 'members.view' => true],
        'navigation_settings' => ['quick_action' => 'expenses'],
    ]);
    app()->forgetInstance('membership');

    expect(Navigation::quickAction($this->organisation, $staffUser, $staff)['label'])->toBe('Add member');
});
