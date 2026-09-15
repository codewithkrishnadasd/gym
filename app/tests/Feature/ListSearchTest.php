<?php

declare(strict_types=1);

use App\Models\Club;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\User;
use App\Support\Search;

/**
 * Every list searches the same way: the name, the phone number, or the
 * reference the organisation gave the record, always as a contains match.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'search.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Downtown']);
    $user = User::factory()->create(['name' => 'Admin Person']);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->alex = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Alex Morgan', 'phone' => '919876543210']);
    $this->priya = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Priya Nair', 'phone' => '919812345678']);
});

it('finds members by part of a name without matching everyone', function (): void {
    // Regression: a name has no digits, and the phone pattern used to
    // collapse to "%%" and match every row.
    $this->get('http://search.test/members?search=alex')->assertOk()->assertSee('Alex Morgan')->assertDontSee('Priya Nair');
    $this->get('http://search.test/members?search=Nair')->assertOk()->assertSee('Priya Nair')->assertDontSee('Alex Morgan');
    $this->get('http://search.test/members?search=zzz')->assertOk()->assertDontSee('Alex Morgan')->assertDontSee('Priya Nair');
});

it('finds members by part of a phone number however it is typed', function (): void {
    $this->get('http://search.test/members?search=98765')->assertOk()->assertSee('Alex Morgan')->assertDontSee('Priya Nair');
    $this->get('http://search.test/members?search=%2B91%2098765%2043210')->assertOk()->assertSee('Alex Morgan')->assertDontSee('Priya Nair');
    $this->get('http://search.test/members?search=45678')->assertOk()->assertSee('Priya Nair')->assertDontSee('Alex Morgan');
});

it('finds records by their reference, with or without the prefix', function (): void {
    $this->get('http://search.test/members?search=MEM-'.$this->priya->id)->assertOk()->assertSee('Priya Nair')->assertDontSee('Alex Morgan');
    $this->get('http://search.test/members?search=mem%20'.$this->priya->id)->assertOk()->assertSee('Priya Nair');
    // A bare number is both an id and a phone fragment, so it finds Priya by
    // id (and may also find anyone whose number contains those digits).
    $this->get('http://search.test/members?search='.$this->priya->id)->assertOk()->assertSee('Priya Nair');

    // A different kind of reference is not an id here.
    expect(Search::referenceId($this->organisation, 'member', 'PMT-'.$this->priya->id))->toBeNull()
        ->and(Search::referenceId($this->organisation, 'member', 'MEM-0042'))->toBe(42)
        ->and(Search::phoneDigits('Alex'))->toBeNull()
        ->and(Search::phoneDigits('+91 98765'))->toBe('9198765');
});

it('applies the same rules to payments, staff and tasks', function (): void {
    $account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);
    $payment = FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->alex->id,
        'club_id' => $this->club->id,
        'collected_by' => $this->admin->id,
        'financial_account_id' => $account->id,
        'payer_name' => 'Alex Morgan',
        'payment_date' => now()->toDateString(),
    ]);
    FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->priya->id,
        'club_id' => $this->club->id,
        'collected_by' => $this->admin->id,
        'financial_account_id' => $account->id,
        'payer_name' => 'Priya Nair',
        'payment_date' => now()->toDateString(),
    ]);

    $this->get('http://search.test/finance/payments?search=PMT-'.$payment->id)->assertOk()->assertSee('Alex Morgan')->assertDontSee('Priya Nair');
    $this->get('http://search.test/finance/payments?search=98765')->assertOk()->assertSee('Alex Morgan')->assertDontSee('Priya Nair');

    $helper = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Helper Hari', 'phone' => '919700000001'])->id]);
    OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create(['name' => 'Other Olly', 'phone' => '919700000002'])->id]);
    // (The signed-in admin's own name is in the page chrome, so the contrast is another staff member.)
    $this->get('http://search.test/staff?search=STF-'.$helper->id)->assertOk()->assertSee('Helper Hari')->assertDontSee('Other Olly');
    $this->get('http://search.test/staff?search=Hari')->assertOk()->assertSee('Helper Hari')->assertDontSee('Other Olly');

    $category = TaskCategory::factory()->create(['organisation_id' => $this->organisation->id]);
    $task = Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $category->id, 'title' => 'Fix the rowers', 'created_by' => $this->admin->id]);
    Task::factory()->create(['organisation_id' => $this->organisation->id, 'task_category_id' => $category->id, 'title' => 'Order towels', 'created_by' => $this->admin->id]);
    $this->get('http://search.test/tasks?search=TSK-'.$task->id)->assertOk()->assertSee('Fix the rowers')->assertDontSee('Order towels');
});

it('keeps filter and search requests off the full-screen loader', function (): void {
    $js = (string) file_get_contents(resource_path('js/loader.js'));

    expect($js)->toContain('\'$set\'')->toContain('\'gotoPage\'')->toContain('isQuiet(payload)');

    // Every list shows its own loader instead.
    foreach (['members', 'finance/payments', 'finance/expenses', 'finance/accounts', 'clubs', 'staff', 'plans', 'billing', 'tasks', 'messages', 'reports', 'audit-log'] as $path) {
        expect(str_contains((string) $this->get('http://search.test/'.$path)->assertOk()->getContent(), 'wire:loading.delay'))->toBeTrue("No internal loader on /{$path}");
    }
});
