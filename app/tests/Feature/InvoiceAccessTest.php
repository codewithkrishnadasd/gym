<?php

declare(strict_types=1);

use App\Enums\ClubAssignmentStatus;
use App\Enums\InvoiceStatus;
use App\Enums\MembershipRole;
use App\Livewire\Billing\Form as InvoiceForm;
use App\Livewire\Finance\Payments\Form as PaymentForm;
use App\Livewire\Settings\BillableItems;
use App\Models\BillableItem;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Domain;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Who can see and raise invoices, and what the forms refuse. Invoices follow
 * the member's club boundary, so a staff member at one branch cannot read or
 * raise bills at another.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'bill.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->clubA = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Club A']);
    $this->clubB = Club::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Club B']);
    $this->account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);

    $this->memberA = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->clubA->id, 'name' => 'Alex Morgan']);
    $this->memberB = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->clubB->id, 'name' => 'Blake Rivers']);

    $admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id]);

    $this->invoiceA = Invoice::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->memberA->id,
        'club_id' => $this->clubA->id,
        'created_by' => $admin->id,
        'total_minor' => 100000,
    ]);
    $this->invoiceB = Invoice::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->memberB->id,
        'club_id' => $this->clubB->id,
        'created_by' => $admin->id,
        'total_minor' => 50000,
    ]);
});

/**
 * @param  array<string, bool>  $permissions
 * @param  array<int, Club>  $clubs
 */
function staffOn(array $permissions, array $clubs): User
{
    $user = User::factory()->create();

    $membership = OrganisationUser::factory()->create([
        'organisation_id' => test()->organisation->id,
        'user_id' => $user->id,
        'role' => MembershipRole::User,
        'permissions' => $permissions,
    ]);

    foreach ($clubs as $club) {
        ClubUserAssignment::factory()->create([
            'organisation_id' => test()->organisation->id,
            'club_id' => $club->id,
            'organisation_user_id' => $membership->id,
            'status' => ClubAssignmentStatus::Active,
        ]);
    }

    test()->actingAs($user);

    return $user;
}

it('shows staff only the invoices for their own clubs', function (): void {
    staffOn(['billing.view' => true], [$this->clubA]);

    $this->get('http://bill.test/billing')
        ->assertOk()
        ->assertSee($this->invoiceA->number)
        ->assertDontSee($this->invoiceB->number);

    $this->get('http://bill.test/billing/'.$this->invoiceA->id)->assertOk();
    $this->get('http://bill.test/billing/'.$this->invoiceB->id)->assertForbidden();
    $this->get('http://bill.test/billing/'.$this->invoiceB->id.'/pdf')->assertForbidden();
});

it('keeps invoices closed to staff without the billing permission', function (): void {
    staffOn(['members.view' => true], [$this->clubA, $this->clubB]);

    $this->get('http://bill.test/billing')->assertForbidden();
    $this->get('http://bill.test/billing/'.$this->invoiceA->id)->assertForbidden();
});

it('lets staff with billing.create raise an invoice for their own club only', function (): void {
    staffOn(['billing.create' => true], [$this->clubA]);

    BillableItem::factory()->create(['organisation_id' => $this->organisation->id, 'name' => 'Locker', 'unit_price_minor' => 50000]);

    Livewire::test(InvoiceForm::class)
        ->call('selectMember', $this->memberA->id)
        ->call('addCustomLine')
        ->set('lines.0.description', 'Towel')
        ->set('lines.0.quantity', 2)
        ->set('lines.0.price', '150')
        ->call('issue')
        ->assertHasNoErrors()
        ->assertRedirect();

    $issued = Invoice::query()->where('member_id', $this->memberA->id)->latest('id')->first();

    expect($issued?->total_minor)->toBe(30000)
        ->and($issued?->club_id)->toBe($this->clubA->id);

    // The other branch's member cannot even be selected.
    Livewire::test(InvoiceForm::class)
        ->call('selectMember', $this->memberB->id)
        ->assertSet('memberId', null);
});

it('bumps the quantity when the same catalogue item is added twice', function (): void {
    staffOn(['billing.create' => true], [$this->clubA]);

    $item = BillableItem::factory()->create(['organisation_id' => $this->organisation->id, 'unit_price_minor' => 150000]);

    Livewire::test(InvoiceForm::class)
        ->set('pickedItemId', $item->id)
        ->call('addItem')
        ->set('pickedItemId', $item->id)
        ->call('addItem')
        ->assertSet('lines.0.quantity', 2)
        ->assertSet('lines.0.price', '1500')
        ->assertCount('lines', 1);
});

it('refuses a payment larger than what is still owed on the invoice', function (): void {
    staffOn(['fees.collect' => true, 'billing.view' => true], [$this->clubA]);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->memberA->id)
        ->set('invoiceId', $this->invoiceA->id)
        ->assertSet('amount', '1000')
        ->set('amount', '1500')
        ->set('financialAccountId', $this->account->id)
        ->call('save')
        ->assertHasErrors('amount');
});

it('defaults the payment amount to the invoice balance and records the link', function (): void {
    staffOn(['fees.collect' => true], [$this->clubA]);

    $this->invoiceA->forceFill(['paid_minor' => 40000, 'status' => InvoiceStatus::PartiallyPaid])->save();

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->memberA->id)
        ->set('invoiceId', $this->invoiceA->id)
        ->assertSet('amount', '600')
        ->set('financialAccountId', $this->account->id)
        ->call('save')
        ->assertHasNoErrors();

    $payment = $this->memberA->feePayments()->latest('id')->first();

    expect($payment?->invoice_id)->toBe($this->invoiceA->id)
        ->and($payment?->amount_minor)->toBe(60000);
});

it('will not accept an invoice belonging to a different member', function (): void {
    staffOn(['fees.collect' => true], [$this->clubA, $this->clubB]);

    Livewire::test(PaymentForm::class)
        ->call('selectMember', $this->memberA->id)
        ->set('invoiceId', $this->invoiceB->id)
        // The picker ignores it, so the id never lands.
        ->assertSet('invoiceId', null);
});

it('offers the price list only to administrators', function (): void {
    staffOn(['billing.create' => true, 'billing.view' => true], [$this->clubA]);

    Livewire::test(BillableItems::class)->assertForbidden();
});
