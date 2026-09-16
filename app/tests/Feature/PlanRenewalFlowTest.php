<?php

declare(strict_types=1);

use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Livewire\Members\Show as MemberShow;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Support\Carbon;
use Livewire\Livewire;

/**
 * Renewing is the common case, so the dialog opens ready for it: the plan
 * that is running, starting the day after the latest active term ends — and
 * saving leads straight to collecting the fee for the new term.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'renew.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);
    $this->monthly = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Monthly', 'price_minor' => 150000, 'duration_days' => 30]);
    $this->quarterly = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Quarterly', 'price_minor' => 400000, 'duration_days' => 90]);
});

function term(Member $member, Plan $plan, string $start, string $end): MemberSubscription
{
    return MemberSubscription::factory()->create([
        'organisation_id' => $member->organisation_id,
        'member_id' => $member->id,
        'club_id' => $member->primary_club_id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'start_date' => $start,
        'end_date' => $end,
        'amount_due_minor' => $plan->price_minor,
        'amount_paid_minor' => $plan->price_minor,
    ]);
}

it('opens the dialog on the running plan, starting after the latest active term', function (): void {
    $this->travelTo('2026-09-14 10:00');

    term($this->member, $this->monthly, '2026-08-20', '2026-09-18');
    // An older, overlapping term that ends later decides the start date.
    term($this->member, $this->quarterly, '2026-07-01', '2026-09-28');

    Livewire::test(MemberShow::class, ['member' => $this->member])
        ->call('prepareRenewal')
        ->assertDispatched('open-modal', 'start-plan')
        // Latest-ending term wins for both the plan and the date.
        ->assertSet('planId', $this->quarterly->id)
        ->assertSet('planStartDate', '2026-09-29')
        ->assertSee('Start today instead')
        ->call('startPlanToday')
        ->assertSet('planStartDate', '2026-09-14')
        ->assertDontSee('Start today instead');
});

it('starts today when every term has already lapsed, and hides the shortcut', function (): void {
    $this->travelTo('2026-09-14 10:00');

    term($this->member, $this->monthly, '2026-07-01', '2026-07-30');

    Livewire::test(MemberShow::class, ['member' => $this->member])
        ->call('prepareRenewal')
        ->assertSet('planId', $this->monthly->id)
        ->assertSet('planStartDate', '2026-09-14');
});

it('goes straight to collecting the fee for the new term after renewing', function (): void {
    $this->travelTo('2026-09-14 10:00');

    term($this->member, $this->monthly, '2026-08-20', '2026-09-18');

    Livewire::test(MemberShow::class, ['member' => $this->member])
        ->call('prepareRenewal')
        ->set('planDiscount', '100')
        ->call('startPlan')
        ->assertHasNoErrors()
        ->assertRedirectContains('/finance/payments/create?member='.$this->member->id.'&subscription=');

    $renewal = $this->member->subscriptions()->orderByDesc('id')->firstOrFail();

    expect($renewal->start_date->toDateString())->toBe('2026-09-19')
        ->and($renewal->amount_due_minor)->toBe(140000);

    // The fee form arrives with the member and the new term chosen, at the
    // discounted amount.
    $this->get('http://renew.test/finance/payments/create?member='.$this->member->id.'&subscription='.$renewal->id)
        ->assertOk()
        ->assertSee('collect the fee below');

    Livewire::withQueryParams(['member' => $this->member->id, 'subscription' => $renewal->id])
        ->test(PaymentForm::class)
        ->assertSet('memberId', $this->member->id)
        ->assertSet('target', 'plan:'.$renewal->id)
        ->assertSet('subscriptionId', $renewal->id)
        ->assertSet('amount', '1400');
});

it('offers to start or renew a plan inside fee collection and pays for the new term', function (): void {
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Lapsed Lena']);
    $plan = Plan::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Monthly', 'price_minor' => 120000, 'duration_days' => 30, 'status' => PlanStatus::Active]);
    $account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);

    // No plan at all: the form says so and offers to start one.
    $form = Livewire::test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->assertSee('No plan yet')
        ->assertSee('Start a plan')
        ->call('preparePlan')
        ->assertDispatched('open-modal')
        ->set('planId', $plan->id)
        ->call('startPlan')
        ->assertHasNoErrors()
        ->assertDispatched('close-modal');

    $subscription = MemberSubscription::query()->where('member_id', $member->id)->firstOrFail();

    // The new term is what the payment is for, with its balance as the amount.
    $form->assertSet('target', 'plan:'.$subscription->id)
        ->assertSet('amount', '1200')
        ->assertDontSee('No plan yet')
        ->set('financialAccountId', $account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    expect($subscription->fresh()?->amount_paid_minor)->toBe(120000);

    // Later, with that term run out, the same spot offers a renewal from the
    // day after it ended.
    $subscription->update(['start_date' => now()->subDays(60)->toDateString(), 'end_date' => now()->subDays(31)->toDateString()]);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->assertSee('Their plan has run out')
        ->assertSee('Renew plan')
        ->call('preparePlan')
        ->assertSet('planId', $plan->id)
        ->assertSet('planStartDate', Carbon::today($this->organisation->timezone)->toDateString())
        ->call('startPlan')
        ->assertHasNoErrors()
        ->assertSet('target', fn (string $target): bool => str_starts_with($target, 'plan:') && $target !== 'plan:'.$subscription->id);

    expect(MemberSubscription::query()->where('member_id', $member->id)->count())->toBe(2);
});

it('does not offer the plan step to staff who cannot start plans, nor without the Plans module', function (): void {
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);

    $staffUser = User::factory()->create();
    $staff = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => $staffUser->id, 'permissions' => ['fees.collect' => true, 'fees.view_own' => true]]);
    ClubUserAssignment::factory()->create(['organisation_id' => $this->organisation->id, 'organisation_user_id' => $staff->id, 'club_id' => $this->club->id, 'status' => 'active']);
    app()->forgetInstance('membership');

    Livewire::actingAs($staffUser)->test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->assertSee('No plan yet')
        ->assertSee('An administrator can start it')
        ->assertDontSee('name="planId"', false);

    $this->organisation->update(['features' => ['members', 'payments', 'accounts', 'clubs']]);
    app()->instance('tenant', $this->organisation->fresh());

    Livewire::actingAs($staffUser)->test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->assertDontSee('No plan yet');
});

it('renews from the day after the term that ends last, ignoring cancelled terms', function (): void {
    $today = Carbon::today($this->organisation->timezone);

    // Two live terms bought out of order, plus a cancelled one that runs later still.
    term($this->member, $this->monthly, $today->copy()->subDays(10)->toDateString(), $today->copy()->addDays(20)->toDateString());
    $furthest = term($this->member, $this->quarterly, $today->copy()->addDays(21)->toDateString(), $today->copy()->addDays(110)->toDateString());
    $cancelled = term($this->member, $this->monthly, $today->copy()->addDays(111)->toDateString(), $today->copy()->addDays(140)->toDateString());
    $cancelled->update(['status' => SubscriptionStatus::Cancelled]);

    Livewire::test(MemberShow::class, ['member' => $this->member])
        ->call('prepareRenewal')
        ->assertSet('planId', $this->quarterly->id)
        ->assertSet('planStartDate', $today->copy()->addDays(111)->toDateString());

    // The same rule from fee collection.
    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->call('preparePlan')
        ->assertSet('planId', $this->quarterly->id)
        ->assertSet('planStartDate', $today->copy()->addDays(111)->toDateString());

    expect($furthest->end_date->toDateString())->toBe($today->copy()->addDays(110)->toDateString());
});
