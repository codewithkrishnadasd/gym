<?php

declare(strict_types=1);

use App\Livewire\Finance\Expenses\Form;
use App\Livewire\Settings\OrganisationSettings;
use App\Models\Club;
use App\Models\Expense;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Expense categories belong to the organisation, not the codebase: a gym's
 * cost structure is its own, and category is what every expense report groups
 * by.
 *
 * That last part is why a used category can only be deactivated, never
 * deleted — deleting one would leave its expenses, and every report that
 * groups by it, describing something the system denies exists.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create([
        'expense_categories' => array_map(
            static fn (string $name): array => ['name' => $name, 'active' => true],
            Organisation::DEFAULT_EXPENSE_CATEGORIES,
        ),
    ]);
    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);
});

function recordExpenseIn(string $category): Expense
{
    $club = Club::factory()->create(['organisation_id' => test()->organisation->id]);

    return Expense::factory()->create([
        'organisation_id' => test()->organisation->id,
        'club_id' => $club->id,
        'category' => $category,
    ]);
}

it('starts a new organisation on the default categories', function (): void {
    $fresh = Organisation::factory()->create(['expense_categories' => null]);

    expect($fresh->expenseCategories())->toBe(Organisation::DEFAULT_EXPENSE_CATEGORIES);
});

it('saves a category list an admin edits', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('tab', 'expenses')
        ->assertSee('Expense categories')
        ->set('expenseCategories', [
            ['name' => 'Rent', 'active' => true],
            ['name' => 'Trainer commission', 'active' => true],
        ])
        ->set('newCategory', 'Franchise fee')
        ->call('addCategory')
        ->assertSee('Franchise fee')
        ->call('saveExpenseCategories')
        ->assertHasNoErrors();

    expect($this->organisation->fresh()?->expenseCategories())
        ->toBe(['Rent', 'Trainer commission', 'Franchise fee']);
});

it('refuses a duplicate category regardless of casing', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('expenseCategories', [['name' => 'Rent', 'active' => true]])
        ->set('newCategory', 'rent')
        ->call('addCategory')
        ->assertHasErrors('newCategory');
});

it('refuses to delete a category that has expenses filed under it', function (): void {
    recordExpenseIn('Rent');

    Livewire::test(OrganisationSettings::class)
        ->set('expenseCategories', [
            ['name' => 'Rent', 'active' => true],
            ['name' => 'Utilities', 'active' => true],
        ])
        ->call('removeCategory', 0)
        ->assertHasErrors('expenseCategories')
        // Still on the list, untouched.
        ->assertSet('expenseCategories.0.name', 'Rent');
});

it('deletes a category nothing has been filed under', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('expenseCategories', [
            ['name' => 'Rent', 'active' => true],
            ['name' => 'Unused', 'active' => true],
        ])
        ->call('removeCategory', 1)
        ->assertHasNoErrors()
        ->assertSet('expenseCategories', [['name' => 'Rent', 'active' => true]]);
});

it('takes a deactivated category off the new-expense list but leaves it in filters', function (): void {
    recordExpenseIn('Rent');

    Livewire::test(OrganisationSettings::class)
        ->set('expenseCategories', [
            ['name' => 'Rent', 'active' => true],
            ['name' => 'Utilities', 'active' => true],
        ])
        ->call('toggleCategory', 0)
        ->call('saveExpenseCategories')
        ->assertHasNoErrors();

    $organisation = $this->organisation->fresh();

    expect(Expense::categoriesForEntry($organisation))->not->toContain('Rent')
        ->and(Expense::categoriesForEntry($organisation))->toContain('Utilities')
        // Reporting and filtering still reach the money already spent.
        ->and(Expense::categoriesForFilter($organisation))->toContain('Rent');
});

it('refuses to save with every category deactivated', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('expenseCategories', [['name' => 'Rent', 'active' => false]])
        ->call('saveExpenseCategories')
        ->assertHasErrors('expenseCategories');
});

it('keeps offering a free-typed category that recorded expenses still use', function (): void {
    recordExpenseIn('Legacy category');

    $this->organisation->update(['expense_categories' => [['name' => 'Rent', 'active' => true]]]);

    expect(Expense::categoriesForFilter($this->organisation->fresh()))
        ->toContain('Legacy category')
        ->toContain('Rent');
});

it('reads a list still stored in the old string-only shape', function (): void {
    // A row written before categories gained an active flag.
    $this->organisation->update(['expense_categories' => ['Rent', 'Utilities']]);

    expect($this->organisation->fresh()?->expenseCategories())->toBe(['Rent', 'Utilities']);
});

it('rejects a deactivated category typed straight into the expense form', function (): void {
    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);

    $this->organisation->update(['expense_categories' => [
        ['name' => 'Rent', 'active' => false],
        ['name' => 'Utilities', 'active' => true],
    ]]);

    // The picker hides it, but the field takes typed values, so the rule has
    // to hold on the server too.
    Livewire::test(Form::class)
        ->set('category', 'Rent')
        ->set('amount', '500')
        ->set('clubId', $club->id)
        ->set('payee', 'Landlord')
        ->call('save')
        ->assertHasErrors(['category' => 'not_in']);

    expect(Expense::query()->count())->toBe(0);
});
