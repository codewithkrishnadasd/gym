<?php

declare(strict_types=1);

use App\Actions\Payments\ReverseFeePayment;
use App\Actions\Subscriptions\CreateSubscription;
use App\Enums\PaymentPurpose;
use App\Enums\PlanStatus;
use App\Livewire\Clubs\Form as ClubForm;
use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Livewire\Members\Form as MemberForm;
use App\Livewire\Members\Show as MemberShow;
use App\Models\Club;
use App\Models\ClubPlanDiscount;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use Livewire\Livewire;

/**
 * Admission fees set per club, discounts at every point money is collected,
 * and the club's standing discount on a plan flowing into what a member owes.
 * Every balance is owed − discounted − paid, and a reversal undoes both parts.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'fees.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id, 'admission_fee_minor' => 100000]);
    $this->plan = Plan::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Quarterly',
        'price_minor' => 450000,
        'duration_days' => 90,
        'status' => PlanStatus::Active,
    ]);
    $this->account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);
});

function collectFee(array $overrides = []): FeePayment
{
    $component = Livewire::test(PaymentForm::class)
        ->call('selectMember', test()->member->id)
        ->set('financialAccountId', test()->account->id)
        ->set('confirmImmediately', true);

    foreach ($overrides as $key => $value) {
        $component->set($key, $value);
    }

    $component->call('save')->assertHasNoErrors();

    return FeePayment::query()->latest('id')->firstOrFail();
}

it('lets the club set an admission fee and a discount per plan', function (): void {
    Livewire::test(ClubForm::class, ['club' => $this->club])
        ->set('admissionFee', '1500')
        ->set('planDiscounts.'.$this->plan->id, '500')
        ->call('save')
        ->assertHasNoErrors();

    $club = $this->club->fresh();

    expect($club?->admission_fee_minor)->toBe(150000)
        ->and($club?->discountFor($this->plan))->toBe(50000);

    // Clearing the field removes the discount rather than storing a zero.
    Livewire::test(ClubForm::class, ['club' => $this->club])
        ->set('planDiscounts.'.$this->plan->id, '')
        ->call('save')
        ->assertHasNoErrors();

    expect(ClubPlanDiscount::query()->count())->toBe(0);
});

it('refuses a club discount larger than the plan price', function (): void {
    Livewire::test(ClubForm::class, ['club' => $this->club])
        ->set('planDiscounts.'.$this->plan->id, '9999')
        ->call('save')
        ->assertHasErrors(['planDiscounts.'.$this->plan->id]);
});

it('snapshots the club admission fee onto a new member and shows it as owed', function (): void {
    Livewire::test(MemberForm::class)
        ->set('name', 'Priya Nair')
        ->set('phone', '9876543210')
        ->set('primaryClubId', $this->club->id)
        ->call('save')
        ->assertHasNoErrors();

    $member = Member::query()->where('name', 'Priya Nair')->firstOrFail();

    expect($member->admission_fee_minor)->toBe(100000)
        ->and($member->admissionOutstandingMinor())->toBe(100000);

    $this->get('http://fees.test/members/'.$member->id)
        ->assertOk()
        ->assertSee('Admission fee due')
        ->assertSee('Collect admission fee');
});

it('prefills the club discount when a plan is started, and lets it be changed', function (): void {
    ClubPlanDiscount::factory()->create([
        'organisation_id' => $this->organisation->id,
        'club_id' => $this->club->id,
        'plan_id' => $this->plan->id,
        'discount_minor' => 50000,
    ]);

    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);

    // Member page: picking the plan fills in the discount.
    Livewire::test(MemberShow::class, ['member' => $member])
        ->set('planId', $this->plan->id)
        ->assertSet('planDiscount', '500')
        ->set('planDiscount', '300')
        ->call('startPlan')
        ->assertHasNoErrors()
        // The dialog closes itself once the plan is on record.
        ->assertDispatched('close-modal', 'start-plan');

    $subscription = $member->subscriptions()->firstOrFail();

    expect($subscription->discount_minor)->toBe(30000)
        ->and($subscription->amount_due_minor)->toBe(420000);

    // New member form: the same prefill follows the chosen club.
    Livewire::test(MemberForm::class)
        ->set('primaryClubId', $this->club->id)
        ->set('planId', $this->plan->id)
        ->assertSet('planDiscount', '500');

    // And the action applies it by default when nothing is said.
    $another = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);
    $defaulted = app(CreateSubscription::class)->handle($another, $this->plan, $this->admin);

    expect($defaulted->discount_minor)->toBe(50000)->and($defaulted->amount_due_minor)->toBe(400000);
});

it('collects the admission fee, in parts, with a discount, and reverses cleanly', function (): void {
    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'admission_fee_minor' => 100000,
    ]);

    // Opening the form for this member offers admission first, at the balance.
    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->assertSet('target', 'admission')
        ->assertSet('amount', '1000');

    $first = collectFee(['amount' => '400']);

    expect($first->purpose)->toBe(PaymentPurpose::Admission)
        ->and($this->member->fresh()?->admissionOutstandingMinor())->toBe(60000);

    // Pay 300 and write off 300 with a discount: fully settled.
    $second = collectFee(['amount' => '300', 'discount' => '300']);

    $member = $this->member->fresh();

    expect($second->discount_minor)->toBe(30000)
        ->and($member?->admission_paid_minor)->toBe(70000)
        ->and($member?->admission_discount_minor)->toBe(30000)
        ->and($member?->owesAdmissionFee())->toBeFalse();

    $this->get('http://fees.test/finance/payments/'.$second->id)->assertOk()->assertSee('Admission fee')->assertSee('discount');

    app(ReverseFeePayment::class)->handle($second, $this->admin, 'Entered twice');

    expect($this->member->fresh()?->admissionOutstandingMinor())->toBe(60000);
});

it('refuses more than is owed, counting the discount', function (): void {
    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'admission_fee_minor' => 100000,
    ]);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->set('financialAccountId', $this->account->id)
        ->set('amount', '800')
        ->set('discount', '300')
        ->call('save')
        ->assertHasErrors(['discount']);

    // A discount with nothing to come off is refused too.
    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->set('target', 'other')
        ->set('financialAccountId', $this->account->id)
        ->set('amount', '100')
        ->set('discount', '50')
        ->call('save')
        ->assertHasErrors(['discount']);

    expect(FeePayment::query()->count())->toBe(0);
});

it('applies a discount given at the counter to the plan balance', function (): void {
    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);
    $subscription = app(CreateSubscription::class)->handle($this->member, $this->plan, $this->admin);

    expect($subscription->amount_due_minor)->toBe(450000);

    // Admission is not owed here (factory member, no snapshot), so the plan is offered.
    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->assertSet('target', 'plan:'.$subscription->id)
        ->assertSet('amount', '4500');

    $payment = collectFee(['amount' => '4000', 'discount' => '500']);

    $subscription->refresh();

    expect($payment->purpose)->toBe(PaymentPurpose::Plan)
        ->and($subscription->amount_paid_minor)->toBe(400000)
        ->and($subscription->discount_minor)->toBe(50000)
        ->and($subscription->amount_due_minor)->toBe(400000)
        ->and($subscription->outstandingMinor())->toBe(0);

    app(ReverseFeePayment::class)->handle($payment, $this->admin, 'Wrong member');

    $subscription->refresh();

    expect($subscription->amount_paid_minor)->toBe(0)
        ->and($subscription->amount_due_minor)->toBe(450000)
        ->and($subscription->discount_minor)->toBe(0);
});

it('shows money paid without a link and lets it be applied to a plan, separately from a real discount', function (): void {
    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Alex Morgan']);

    // Paid at the counter with nothing chosen: sits as credit.
    $unlinked = collectFee(['target' => 'other', 'amount' => '1000']);

    expect($unlinked->purpose)->toBe(PaymentPurpose::Other)
        ->and($this->member->fresh()?->unlinkedCreditMinor())->toBe(100000);

    $this->get('http://fees.test/members/'.$this->member->id.'?tab=payments')
        ->assertOk()
        ->assertSee('Paid without a link')
        ->assertSee('1,000.00 available')
        ->assertSee('Apply to a plan or bill')
        ->assertSee('Not linked')
        // Every row can be opened, where confirm / reject / reverse live.
        ->assertSee('/finance/payments/'.$unlinked->id, false)
        ->assertSeeText('View');

    // Then a plan starts; the counter takes 3000, writes off 500 as a real
    // discount and applies 700 of the unlinked money.
    $subscription = app(CreateSubscription::class)->handle($this->member, $this->plan, $this->admin);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->assertSee('Use money already paid without a link')
        ->set('amount', '3000')
        ->set('discount', '500')
        ->set('useCredit', true)
        // Proposes what is left to cover, capped by the credit: 4500 − 3000 − 500 = 1000, credit is 1000.
        ->assertSet('creditAmount', '1000')
        ->set('creditAmount', '700')
        ->set('financialAccountId', $this->account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    $payment = FeePayment::query()->latest('id')->firstOrFail();
    $subscription->refresh();

    expect($payment->amount_minor)->toBe(300000)
        ->and($payment->discount_minor)->toBe(50000)
        ->and($payment->credit_applied_minor)->toBe(70000)
        // Paid on the term = money now + credit; the discount lowers what is owed.
        ->and($subscription->amount_paid_minor)->toBe(370000)
        ->and($subscription->amount_due_minor)->toBe(400000)
        ->and($subscription->outstandingMinor())->toBe(30000)
        ->and($this->member->fresh()?->unlinkedCreditMinor())->toBe(30000);

    $this->get('http://fees.test/finance/payments/'.$payment->id)->assertOk()->assertSee('from money paid earlier without a link');

    // Reversing gives the credit back and undoes both parts on the term.
    app(ReverseFeePayment::class)->handle($payment, $this->admin, 'Entered wrongly');
    $subscription->refresh();

    expect($subscription->amount_paid_minor)->toBe(0)
        ->and($subscription->amount_due_minor)->toBe(450000)
        ->and($this->member->fresh()?->unlinkedCreditMinor())->toBe(100000);
});

it('will not apply more unlinked money than exists, nor to nothing, nor beyond what is owed', function (): void {
    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);
    collectFee(['target' => 'other', 'amount' => '200']);
    app(CreateSubscription::class)->handle($this->member, $this->plan, $this->admin);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->set('financialAccountId', $this->account->id)
        ->set('amount', '0')
        ->set('useCredit', true)
        ->set('creditAmount', '500')
        ->call('save')
        ->assertHasErrors(['creditAmount']);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->set('target', 'other')
        ->set('financialAccountId', $this->account->id)
        ->set('amount', '0')
        ->set('useCredit', true)
        ->set('creditAmount', '100')
        ->call('save')
        ->assertHasErrors(['creditAmount']);

    // Credit alone can settle a balance: zero handed over is fine here.
    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->member->id)
        ->set('financialAccountId', $this->account->id)
        ->set('amount', '0')
        ->set('useCredit', true)
        ->set('creditAmount', '200')
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    expect($this->member->fresh()?->unlinkedCreditMinor())->toBe(0)
        ->and(FeePayment::query()->latest('id')->firstOrFail()->amount_minor)->toBe(0);
});
