<?php

declare(strict_types=1);

use App\Enums\ConfirmationStatus;
use App\Livewire\Finance\Confirmations;
use App\Livewire\Finance\Payments\Form;
use App\Models\Club;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Every fee collection has to name the account the money was received into.
 * Without it an account balance is only ever a partial picture, and the admin
 * confirming the payment has nothing to reconcile against a statement.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR']);
    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);

    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
    ]);

    $this->account = FinancialAccount::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Front Desk Cash',
    ]);
});

it('refuses to record a payment with no receiving account', function (): void {
    Livewire::test(Form::class)
        ->set('memberId', $this->member->id)
        ->set('amount', '500')
        ->set('paymentDate', now()->toDateString())
        ->set('financialAccountId', null)
        ->call('save')
        ->assertHasErrors(['financialAccountId' => 'required']);

    expect($this->member->feePayments()->count())->toBe(0);
});

it('records the account the money was received into', function (): void {
    Livewire::test(Form::class)
        ->set('memberId', $this->member->id)
        ->set('amount', '500')
        ->set('paymentDate', now()->toDateString())
        ->set('financialAccountId', $this->account->id)
        ->call('save')
        ->assertHasNoErrors();

    expect($this->member->feePayments()->first()?->financial_account_id)->toBe($this->account->id);
});

it('names the receiving account in the confirmation queue', function (): void {
    // The admin confirming a payment is signing off that the money reached a
    // particular account, so the queue has to say which one.
    FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'club_id' => $this->club->id,
        'member_id' => $this->member->id,
        'financial_account_id' => $this->account->id,
        'confirmation_status' => ConfirmationStatus::PendingAdminConfirmation,
    ]);

    Livewire::test(Confirmations::class)
        ->assertSee('Front Desk Cash')
        ->assertSee('Credits');
});

it('rejects an account belonging to another organisation', function (): void {
    $other = Organisation::factory()->create();
    $theirAccount = FinancialAccount::factory()->create(['organisation_id' => $other->id]);

    Livewire::test(Form::class)
        ->set('memberId', $this->member->id)
        ->set('amount', '500')
        ->set('paymentDate', now()->toDateString())
        ->set('financialAccountId', $theirAccount->id)
        ->call('save')
        ->assertHasErrors('financialAccountId');
});
