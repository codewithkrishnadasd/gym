<?php

declare(strict_types=1);

use App\Enums\FinancialAccountType;
use App\Enums\NotificationRecipientType;
use App\Livewire\Billing\Form as InvoiceForm;
use App\Livewire\Finance\Expenses\Form;
use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Models\BillableItem;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use Livewire\Livewire;

/**
 * Money from someone who is not a member: a day visitor, a guest, a
 * company. The payment or invoice names them directly and everything
 * downstream — receipt, message, lists, exports — reads that name.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'walkin.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);
});

it('collects a fee from someone who is not a member and issues them a receipt', function (): void {
    Livewire::test(PaymentForm::class)
        ->set('memberSearch', 'Visitor Vik')
        ->call('startWalkIn')
        ->assertSet('walkIn', true)
        ->assertSet('payerName', 'Visitor Vik')
        ->set('payerPhone', '9876540000')
        ->set('amount', '300')
        ->set('financialAccountId', $this->account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    $payment = FeePayment::query()->latest('id')->firstOrFail();

    expect($payment->member_id)->toBeNull()
        ->and($payment->club_id)->toBeNull()
        ->and($payment->payer_name)->toBe('Visitor Vik')
        ->and($payment->payer_phone)->toBe('919876540000')
        ->and($payment->payerName())->toBe('Visitor Vik')
        ->and($payment->isConfirmed())->toBeTrue();

    // The receipt message goes to the payer as a contact.
    $notification = WhatsappActionNotification::query()->latest('id')->firstOrFail();
    expect($notification->recipient_type)->toBe(NotificationRecipientType::Contact)
        ->and($notification->recipient_id)->toBeNull()
        ->and($notification->recipient_name)->toBe('Visitor Vik')
        ->and($notification->recipient_phone)->toBe('919876540000');

    $this->get('http://walkin.test/finance/payments/'.$payment->id)->assertOk()->assertSee('Visitor Vik')->assertDontSee('Open profile');
    $this->get('http://walkin.test/finance/payments/'.$payment->id.'/receipt')->assertOk();
    $this->get('http://walkin.test/finance/payments?search=Vik')->assertOk()->assertSee('Visitor Vik');
    $this->get('http://walkin.test/finance/payments/export')->assertOk();
    $this->get($payment->publicUrl())->assertOk()->assertSee('Visitor Vik');
});

it('requires a payer name for a walk-in payment and a member otherwise', function (): void {
    Livewire::test(PaymentForm::class)
        ->set('amount', '300')
        ->set('financialAccountId', $this->account->id)
        ->call('save')
        ->assertHasErrors(['memberId']);

    Livewire::test(PaymentForm::class)
        ->call('startWalkIn')
        ->set('amount', '300')
        ->set('financialAccountId', $this->account->id)
        ->call('save')
        ->assertHasErrors(['payerName']);

    Livewire::test(PaymentForm::class)
        ->call('startWalkIn')
        ->set('payerName', 'Bad Phone')
        ->set('payerPhone', 'not a number')
        ->set('amount', '300')
        ->set('financialAccountId', $this->account->id)
        ->call('save')
        ->assertHasErrors(['payerPhone']);
});

it('issues an invoice to someone who is not a member and collects against it', function (): void {
    $item = BillableItem::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Day pass', 'unit_price_minor' => 50000]);

    Livewire::test(InvoiceForm::class)
        ->set('memberSearch', 'Guest Gita')
        ->call('startWalkIn')
        ->assertSet('payerName', 'Guest Gita')
        ->set('payerPhone', '9876541111')
        ->set('pickedItemId', $item->id)
        ->call('issue')
        ->assertHasNoErrors();

    $invoice = Invoice::query()->latest('id')->firstOrFail();

    expect($invoice->member_id)->toBeNull()
        ->and($invoice->payer_name)->toBe('Guest Gita')
        ->and($invoice->payer_phone)->toBe('919876541111')
        ->and($invoice->total_minor)->toBe(50000);

    $this->get('http://walkin.test/billing/'.$invoice->id)->assertOk()->assertSee('Guest Gita')->assertSee('Not a member');
    $this->get('http://walkin.test/billing?search=Gita')->assertOk()->assertSee('Guest Gita');
    $this->get('http://walkin.test/billing/'.$invoice->id.'/pdf')->assertOk();
    $this->get($invoice->publicUrl())->assertOk()->assertSee('Guest Gita');

    // "Collect" on the invoice opens the payment form already made out to the
    // payer, with that invoice as the thing being paid.
    Livewire::withQueryParams(['invoice' => $invoice->id])
        ->test(PaymentForm::class)
        ->assertSet('walkIn', true)
        ->assertSet('payerName', 'Guest Gita')
        ->assertSet('target', 'invoice:'.$invoice->id)
        ->assertSet('amount', '500')
        ->set('financialAccountId', $this->account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    expect($invoice->fresh()?->paid_minor)->toBe(50000)
        ->and($invoice->fresh()?->isOpen())->toBeFalse();
});

it('still requires a member or a name on an invoice', function (): void {
    $item = BillableItem::factory()->create(['organisation_id' => $this->organisation->id, 'unit_price_minor' => 50000]);

    Livewire::test(InvoiceForm::class)
        ->set('pickedItemId', $item->id)
        ->call('issue')
        ->assertHasErrors(['memberId']);

    Livewire::test(InvoiceForm::class)
        ->call('startWalkIn')
        ->set('pickedItemId', $item->id)
        ->call('issue')
        ->assertHasErrors(['payerName']);

    expect(Invoice::query()->count())->toBe(0);
});

it('keeps member payments exactly as before', function (): void {
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Regular Ria', 'primary_club_id' => null]);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $member->id)
        ->assertSet('walkIn', false)
        ->set('amount', '200')
        ->set('financialAccountId', $this->account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    $payment = FeePayment::query()->latest('id')->firstOrFail();
    expect($payment->member_id)->toBe($member->id)
        ->and($payment->payer_name)->toBe('Regular Ria')
        ->and($payment->payer_phone)->toBeNull();
});

it('makes every payment and invoice out by name when the Members module is off', function (): void {
    $this->organisation->update(['features' => ['payments', 'billing', 'accounts']]);
    $this->organisation->refresh();
    app()->instance('tenant', $this->organisation);

    $item = BillableItem::factory()->create(['organisation_id' => $this->organisation->id, 'unit_price_minor' => 50000]);

    // Both forms open straight on the name fields, with no member search
    // and no way back to one.
    $this->get('http://walkin.test/finance/payments/create')->assertOk()->assertSee('Enter who is paying')->assertDontSee('Choose a member instead')->assertDontSee('Search member');
    $this->get('http://walkin.test/billing/create')->assertOk()->assertSee('Enter who this invoice is for')->assertDontSee('Search member');

    Livewire::test(InvoiceForm::class)
        ->assertSet('walkIn', true)
        ->set('payerName', 'Company Co')
        ->set('pickedItemId', $item->id)
        ->call('issue')
        ->assertHasNoErrors();

    Livewire::test(PaymentForm::class)
        ->assertSet('walkIn', true)
        ->set('payerName', 'Drop-in Dan')
        ->set('amount', '150')
        ->set('financialAccountId', $this->account->id)
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    expect(Invoice::query()->whereNull('member_id')->where('payer_name', 'Company Co')->exists())->toBeTrue()
        ->and(FeePayment::query()->whereNull('member_id')->where('payer_name', 'Drop-in Dan')->exists())->toBeTrue();
});

it('sends cash to the cash account without asking, and asks only for other methods', function (): void {
    $cash = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Till', 'account_type' => FinancialAccountType::Cash]);
    $bank = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'HDFC', 'account_type' => FinancialAccountType::Bank]);
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => null]);

    $form = Livewire::test(PaymentForm::class)
        ->assertSet('paymentMethod', 'cash')
        ->assertSet('financialAccountId', $cash->id)
        ->assertDontSee('Received into')
        ->assertSee('Goes into Till');

    // A bank transfer needs an account named — and the cash account is not offered for it.
    $form->set('paymentMethod', 'bank_transfer')
        ->assertSet('financialAccountId', null)
        ->assertSee('Received into')
        ->assertSee('HDFC (')
        ->assertDontSee('Till (');

    $form->set('paymentMethod', 'cash')
        ->assertSet('financialAccountId', $cash->id)
        ->call('selectMember', $member->id)
        ->set('amount', '100')
        ->set('confirmImmediately', true)
        ->call('save')
        ->assertHasNoErrors();

    expect(FeePayment::query()->latest('id')->value('financial_account_id'))->toBe($cash->id);

    // Expenses: cash comes out of the cash account; otherwise pick a non-cash account.
    Livewire::test(Form::class)
        ->assertSet('paidBy', 'cash')
        ->assertSet('fundingAccountId', $cash->id)
        ->assertDontSee('Paid from')
        ->set('paidBy', 'account')
        ->assertSet('fundingAccountId', null)
        ->assertSee('Paid from')
        ->assertSee('HDFC (')
        ->assertDontSee('Till (');
});
