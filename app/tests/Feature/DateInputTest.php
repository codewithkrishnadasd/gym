<?php

declare(strict_types=1);

use App\Models\Club;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ViewErrorBag;

/**
 * Every date in the app is typed and shown as dd/mm/yyyy, regardless of the
 * browser's locale, while the Livewire property underneath stays ISO.
 */
it('renders a dd/mm/yyyy field bound through $wire, deferred or live', function (): void {
    // Components read the shared error bag, which a bare Blade::render lacks.
    view()->share('errors', new ViewErrorBag);

    $deferred = Blade::render('<x-ui.input type="date" wire:model="dueDate" name="dueDate" label="Due date" />');

    expect($deferred)
        ->toContain('placeholder="dd/mm/yyyy"')
        ->toContain("dateField({ property: 'dueDate', live: false })")
        ->toContain('Due date')
        // The shared calendar, in single-date mode; nothing native.
        ->toContain("mode: 'single'")
        ->toContain('futureAllowed: true')
        ->not->toContain('type="date"')
        ->not->toContain('wire:model');

    $live = Blade::render('<x-ui.date-input bare wire:model.live="from" aria-label="From date" class="w-40" />');

    expect($live)
        ->toContain("dateField({ property: 'from', live: true })")
        ->toContain('aria-label="From date"')
        ->not->toContain('<label');
});

it('is used for every date on the main screens', function (): void {
    $organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $organisation->id,
        'hostname' => 'dates.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $organisation);

    // The attendance roster only shows its date once there is a club to mark.
    Club::factory()->create(['organisation_id' => $organisation->id]);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    // The dashboard's period is chosen with the range picker instead (see
    // components/ui/date-range.blade.php), so it is not in this list.
    foreach (['/members/create', '/finance/payments', '/finance/payments/create', '/finance/expenses', '/attendance/members', '/audit-log', '/tasks/create'] as $path) {
        $html = $this->get('http://dates.test'.$path)->assertOk()->getContent();

        expect(str_contains($html, 'placeholder="dd/mm/yyyy"'))->toBeTrue("No dd/mm/yyyy field on {$path}");

        // No native date input anywhere: every calendar is the shared one.
        expect(substr_count($html, 'type="date"'))->toBe(0);
    }
});
