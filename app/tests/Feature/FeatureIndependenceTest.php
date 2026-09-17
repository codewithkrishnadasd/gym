<?php

declare(strict_types=1);

use App\Enums\Feature;
use App\Enums\PlanStatus;
use App\Livewire\Billing\Form as InvoiceForm;
use App\Livewire\Clubs\Form as ClubForm;
use App\Livewire\Finance\Accounts\Form as AccountForm;
use App\Livewire\Finance\Expenses\Form as ExpenseForm;
use App\Livewire\Members\Show as MemberShow;
use App\Livewire\Plans\Form as PlanForm;
use App\Livewire\Staff\Form as StaffForm;
use App\Livewire\Tasks\Form as TaskForm;
use App\Models\BillableItem;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskStatus;
use App\Models\User;
use Livewire\Livewire;

/**
 * Every module works with only itself (and what it declares it needs)
 * switched on, against data recorded while other modules were on. Each
 * module's pages render and its main action saves; every other module's
 * pages answer 404 rather than error.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'solo.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create(['name' => 'Owner Omar']);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    // Data from a time when everything was on.
    $o = ['organisation_id' => $this->organisation->id];
    $this->club = Club::factory()->create($o + ['admission_fee_minor' => 50000]);
    $this->member = Member::factory()->create($o + ['primary_club_id' => $this->club->id, 'name' => 'Existing Esha']);
    $this->plan = Plan::factory()->create($o + ['name' => 'Monthly', 'price_minor' => 100000, 'duration_days' => 30, 'status' => PlanStatus::Active]);
    $this->subscription = MemberSubscription::factory()->create($o + ['member_id' => $this->member->id, 'club_id' => $this->club->id, 'plan_id' => $this->plan->id, 'amount_due_minor' => 100000]);
    $this->account = FinancialAccount::factory()->create($o);
    $this->payment = FeePayment::factory()->create($o + ['member_id' => $this->member->id, 'club_id' => $this->club->id, 'subscription_id' => $this->subscription->id, 'financial_account_id' => $this->account->id, 'collected_by' => $this->admin->id]);
    $this->invoice = Invoice::factory()->create($o + ['member_id' => $this->member->id, 'club_id' => $this->club->id, 'created_by' => $this->admin->id]);
    $this->expense = Expense::factory()->create($o + ['club_id' => $this->club->id, 'paid_from_financial_account_id' => $this->account->id, 'created_by' => $this->admin->id]);
    $this->category = TaskCategory::factory()->create($o);
    $this->status = TaskStatus::factory()->create($o + ['task_category_id' => $this->category->id, 'completes' => false, 'position' => 0]);
    $this->task = Task::factory()->create($o + ['task_category_id' => $this->category->id, 'task_status_id' => $this->status->id, 'created_by' => $this->admin->id, 'member_id' => $this->member->id]);
    $this->staff = OrganisationUser::factory()->create($o + ['user_id' => User::factory()->create(['name' => 'Trainer Tia'])->id]);
});

function only(Feature ...$features): void
{
    test()->organisation->update(['features' => array_map(fn (Feature $feature): string => $feature->value, $features)]);
    test()->organisation->refresh();
    app()->instance('tenant', test()->organisation);
}

/**
 * @return array<string, array<int, string>>
 */
function modulePages(): array
{
    $t = test();

    return [
        'members' => ['/members', '/members/create', '/members/'.$t->member->id, '/members/'.$t->member->id.'/edit', '/members/export'],
        'clubs' => ['/clubs', '/clubs/create', '/clubs/'.$t->club->id, '/clubs/'.$t->club->id.'/edit'],
        'staff' => ['/staff', '/staff/create', '/staff/'.$t->staff->id.'/edit'],
        'plans' => ['/plans', '/plans/create', '/plans/'.$t->plan->id.'/edit'],
        'member_attendance' => ['/attendance/members'],
        'staff_attendance' => ['/attendance/users'],
        'payments' => ['/finance/payments', '/finance/payments/create', '/finance/payments/'.$t->payment->id, '/finance/payments/'.$t->payment->id.'/receipt', '/finance/confirmations', '/finance/payments/export'],
        'billing' => ['/billing', '/billing/create', '/billing/'.$t->invoice->id, '/billing/'.$t->invoice->id.'/pdf'],
        'expenses' => ['/finance/expenses', '/finance/expenses/create', '/finance/expenses/'.$t->expense->id.'/edit', '/finance/expenses/export'],
        'accounts' => ['/finance/accounts', '/finance/accounts/create', '/finance/accounts/'.$t->account->id, '/finance/accounts/'.$t->account->id.'/edit'],
        'messaging' => ['/messages'],
        'tasks' => ['/tasks', '/tasks/create', '/tasks/'.$t->task->id, '/tasks/'.$t->task->id.'/edit'],
        'reports' => ['/reports', '/reports/export', '/reports/pdf'],
        'documents' => [],
    ];
}

it('renders every page of a module on its own, and 404s the rest', function (Feature $feature): void {
    only($feature);

    $enabled = $this->organisation->enabledFeatures();

    // Shared pages never depend on a module.
    foreach (['/dashboard', '/settings/organisation', '/audit-log'] as $path) {
        $this->get('http://solo.test'.$path)->assertOk();
    }

    foreach (modulePages() as $module => $paths) {
        foreach ($paths as $path) {
            $response = $this->get('http://solo.test'.$path);

            if (in_array($module, $enabled, true)) {
                // The staff roster is refused (403) rather than missing when
                // only the member side of attendance applies, and vice versa.
                expect($response->getStatusCode())->toBeIn([200, 302, 403], "$path with only {$feature->value}");
            } else {
                expect($response->getStatusCode())->toBe(404, "$path should be absent with only {$feature->value}");
            }
        }
    }

    // Settings tabs all render whatever is on.
    foreach (['profile', 'terminology', 'notifications', 'expenses', 'billing', 'storage', 'tasks', 'templates'] as $tab) {
        $this->get('http://solo.test/settings/organisation?tab='.$tab)->assertOk();
    }
})->with(Feature::cases());

it('creates a club with only Clubs on', function (): void {
    only(Feature::Clubs);

    Livewire::test(ClubForm::class)
        ->set('name', 'Solo Club')
        ->set('code', 'SOLO')
        ->call('save')
        ->assertHasNoErrors();

    expect(Club::query()->where('name', 'Solo Club')->exists())->toBeTrue();
});

it('invites staff with only Staff on', function (): void {
    only(Feature::Staff);

    Livewire::test(StaffForm::class)
        ->set('name', 'New Nadia')
        ->set('phone', '9876523456')
        ->set('role', 'user')
        ->call('save')
        ->assertHasNoErrors();

    expect(User::query()->where('name', 'New Nadia')->exists())->toBeTrue();
});

it('creates a plan and starts it for a member with only Plans on', function (): void {
    only(Feature::Plans);

    Livewire::test(PlanForm::class)
        ->set('name', 'Yearly')
        ->set('price', '9000')
        ->set('durationDays', '365')
        ->call('save')
        ->assertHasNoErrors();

    $plan = Plan::query()->where('name', 'Yearly')->firstOrFail();

    // Starting a plan redirects to fee collection when that module is on;
    // without it the member page is the destination.
    Livewire::test(MemberShow::class, ['member' => $this->member])
        ->set('planId', $plan->id)
        ->call('startPlan')
        ->assertHasNoErrors()
        ->assertRedirect(route('tenant.members.show', ['member' => $this->member->id, 'tab' => 'plans']));

    expect(MemberSubscription::query()->where('plan_id', $plan->id)->exists())->toBeTrue();
});

it('issues an invoice with only Invoices on', function (): void {
    only(Feature::Billing);

    $item = BillableItem::factory()->create(['organisation_id' => $this->organisation->id, 'unit_price_minor' => 25000]);

    // No Members module: the invoice is made out by name.
    Livewire::test(InvoiceForm::class)
        ->assertSet('walkIn', true)
        ->set('payerName', 'Cash Customer')
        ->set('pickedItemId', $item->id)
        ->call('issue')
        ->assertHasNoErrors();

    expect(Invoice::query()->count())->toBe(2);

    // Without fee collection the invoice page offers no "collect" action.
    $this->get('http://solo.test/billing/'.Invoice::query()->latest('id')->value('id'))->assertOk()->assertDontSee('Collect');
});

it('records an expense with only Expenses on', function (): void {
    only(Feature::Expenses);

    $this->get('http://solo.test/finance/expenses/create')->assertOk()->assertDontSee('Target type');

    Livewire::test(ExpenseForm::class)
        ->set('category', 'Rent')
        ->set('amount', '1200')
        ->set('payee', 'Landlord')
        ->set('fundingAccountId', $this->account->id)
        ->call('save')
        ->assertHasNoErrors();

    expect(Expense::query()->count())->toBe(2);
});

it('creates an account with only Accounts on', function (): void {
    only(Feature::Accounts);

    Livewire::test(AccountForm::class)
        ->set('name', 'Till')
        ->set('accountType', 'cash')
        ->call('save')
        ->assertHasNoErrors();

    expect(FinancialAccount::query()->where('name', 'Till')->exists())->toBeTrue();
});

it('creates a task with only Tasks on', function (): void {
    only(Feature::Tasks);

    Livewire::test(TaskForm::class)
        ->set('categoryId', $this->category->id)
        ->set('title', 'Fix the treadmill')
        ->call('save')
        ->assertHasNoErrors();

    expect(Task::query()->where('title', 'Fix the treadmill')->exists())->toBeTrue();
});
