<?php

declare(strict_types=1);

use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Livewire\Members\Show as MemberShow;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
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
