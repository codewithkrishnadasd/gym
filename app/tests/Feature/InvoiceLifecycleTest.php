<?php

declare(strict_types=1);

use App\Actions\Billing\IssueInvoice;
use App\Actions\Billing\VoidInvoice;
use App\Actions\Payments\ConfirmFeePayment;
use App\Actions\Payments\RecordFeePayment;
use App\Actions\Payments\ReverseFeePayment;
use App\Enums\ConfirmationStatus;
use App\Enums\InvoiceStatus;
use App\Enums\NotificationActionType;
use App\Exceptions\LifecycleViolation;
use App\Models\BillableItem;
use App\Models\Club;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\WhatsappActionNotification;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use App\Support\WhatsApp\MessageComposer;

/**
 * An invoice is a promise to pay that can be kept in parts. The rules that
 * keep the balance honest are all here: only confirmed money counts, a
 * reversal takes it back, nothing can be voided while money is attached, and
 * two invoices issued at once cannot share a number.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);
    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id]);
    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'phone' => '919876543210',
    ]);
    $this->account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);

    $this->training = BillableItem::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Personal training',
        'unit_price_minor' => 150000,
    ]);
});

function issueFor(int ...$prices): Invoice
{
    $lines = array_map(fn (int $price, int $i): array => [
        'billable_item_id' => null,
        'description' => 'Line '.($i + 1),
        'quantity' => 1,
        'unit_price_minor' => $price,
    ], $prices, array_keys($prices));

    return app(IssueInvoice::class)->handle(test()->member, $lines, test()->admin)->invoice;
}

function payAgainst(Invoice $invoice, int $amountMinor, bool $confirm = true): FeePayment
{
    $result = app(RecordFeePayment::class)->handle([
        'club_id' => test()->club->id,
        'member_id' => test()->member->id,
        'subscription_id' => null,
        'invoice_id' => $invoice->id,
        'payer_name' => test()->member->name,
        'amount_minor' => $amountMinor,
        'currency_code' => 'INR',
        'payment_method' => 'cash',
        'financial_account_id' => test()->account->id,
        'transaction_reference' => null,
        'payment_date' => now()->toDateString(),
        'notes' => null,
    ], test()->admin, confirmImmediately: $confirm);

    return $result->payment;
}

it('totals the lines from the prices given, not the catalogue', function (): void {
    $issued = app(IssueInvoice::class)->handle($this->member, [
        // Catalogue says 1500; the counter agreed 1200 for this one.
        ['billable_item_id' => $this->training->id, 'description' => 'Personal training', 'quantity' => 4, 'unit_price_minor' => 120000],
        ['billable_item_id' => null, 'description' => 'Locker', 'quantity' => 1, 'unit_price_minor' => 50000],
    ], $this->admin);

    $invoice = $issued->invoice;

    expect($invoice->total_minor)->toBe(4 * 120000 + 50000)
        ->and($invoice->status)->toBe(InvoiceStatus::Issued)
        ->and($invoice->lines)->toHaveCount(2)
        ->and($invoice->lines->first()->line_total_minor)->toBe(480000);

    // Repricing the item afterwards changes nothing already issued.
    $this->training->update(['unit_price_minor' => 999999]);

    expect($invoice->fresh()?->total_minor)->toBe(530000);
});

it('numbers invoices sequentially per organisation with the year embedded', function (): void {
    $first = issueFor(10000);
    $second = issueFor(10000);

    expect($first->sequence)->toBe(1)
        ->and($second->sequence)->toBe(2)
        ->and($second->number)->toBe('INV-'.now()->format('Y').'-0002');

    // Another organisation starts its own count.
    $other = Organisation::factory()->create();
    app()->instance('tenant', $other);
    $club = Club::factory()->create(['organisation_id' => $other->id]);
    $admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $other->id]);
    $member = Member::factory()->create(['organisation_id' => $other->id, 'primary_club_id' => $club->id]);

    $theirs = app(IssueInvoice::class)->handle($member, [
        ['billable_item_id' => null, 'description' => 'x', 'quantity' => 1, 'unit_price_minor' => 100],
    ], $admin)->invoice;

    expect($theirs->sequence)->toBe(1);
});

it('composes a WhatsApp message on issue', function (): void {
    $invoice = issueFor(150000);

    $notification = WhatsappActionNotification::query()->latest('id')->first();

    expect($notification?->action_type)->toBe(NotificationActionType::InvoiceIssued)
        ->and($notification?->message_snapshot)->toContain($invoice->number)
        ->and($notification?->message_snapshot)->toContain('Line 1');
});

it('takes part payments and tracks the balance', function (): void {
    $invoice = issueFor(100000);

    payAgainst($invoice, 40000);

    $fresh = $invoice->fresh();

    expect($fresh?->paid_minor)->toBe(40000)
        ->and($fresh?->outstandingMinor())->toBe(60000)
        ->and($fresh?->status)->toBe(InvoiceStatus::PartiallyPaid);

    payAgainst($invoice, 60000);

    $fresh = $invoice->fresh();

    expect($fresh?->paid_minor)->toBe(100000)
        ->and($fresh?->outstandingMinor())->toBe(0)
        ->and($fresh?->status)->toBe(InvoiceStatus::Paid);
});

it('counts nothing until the payment is confirmed', function (): void {
    $invoice = issueFor(100000);

    // A staff submission sits pending; the invoice must not move.
    $pending = payAgainst($invoice, 100000, confirm: false);

    expect($pending->confirmation_status)->toBe(ConfirmationStatus::PendingAdminConfirmation)
        ->and($invoice->fresh()?->paid_minor)->toBe(0)
        ->and($invoice->fresh()?->status)->toBe(InvoiceStatus::Issued);

    app(ConfirmFeePayment::class)->handle($pending, $this->admin);

    expect($invoice->fresh()?->status)->toBe(InvoiceStatus::Paid);
});

it('takes the money back off the invoice when a payment is reversed', function (): void {
    $invoice = issueFor(100000);
    $payment = payAgainst($invoice, 100000);

    expect($invoice->fresh()?->status)->toBe(InvoiceStatus::Paid);

    app(ReverseFeePayment::class)->handle($payment->fresh(), $this->admin, 'Bounced');

    $fresh = $invoice->fresh();

    expect($fresh?->paid_minor)->toBe(0)
        ->and($fresh?->status)->toBe(InvoiceStatus::Issued);
});

it('tells the member how much is still owed in the payment message', function (): void {
    $invoice = issueFor(100000);

    payAgainst($invoice, 30000);

    $notification = WhatsappActionNotification::query()
        ->where('action_type', NotificationActionType::FeePaymentConfirmed)
        ->latest('id')
        ->first();

    // The default template does not print these, but the variables have to be
    // there for an organisation that adds them.
    $template = 'Paid {amount} against {invoiceNumber}. Balance: {balanceDue}.';
    $rendered = MessageComposer::render(
        NotificationActionType::FeePaymentConfirmed,
        $this->organisation,
        ['memberName' => 'x', 'amount' => '₹300', 'invoiceNumber' => $invoice->number, 'balanceDue' => '₹700'],
    );

    expect($notification)->not->toBeNull()
        ->and(MessageComposer::variablesFor(NotificationActionType::FeePaymentConfirmed))
        ->toHaveKeys(['invoiceNumber', 'balanceDue']);
});

it('refuses to void once money has been confirmed against it', function (): void {
    $invoice = issueFor(100000);
    payAgainst($invoice, 20000);

    expect(fn () => app(VoidInvoice::class)->handle($invoice->fresh(), $this->admin, 'Mistake'))
        ->toThrow(LifecycleViolation::class, 'Reverse them');

    expect($invoice->fresh()?->status)->toBe(InvoiceStatus::PartiallyPaid);
});

it('refuses to void while a payment is still awaiting confirmation', function (): void {
    $invoice = issueFor(100000);
    payAgainst($invoice, 20000, confirm: false);

    expect(fn () => app(VoidInvoice::class)->handle($invoice->fresh(), $this->admin, 'Mistake'))
        ->toThrow(LifecycleViolation::class, 'awaiting confirmation');
});

it('voids a clean invoice and keeps the row, so the numbering has no gap', function (): void {
    $invoice = issueFor(100000);

    app(VoidInvoice::class)->handle($invoice, $this->admin, 'Raised against the wrong member');

    $fresh = $invoice->fresh();

    expect($fresh)->not->toBeNull()
        ->and($fresh?->status)->toBe(InvoiceStatus::Void)
        ->and($fresh?->void_reason)->toBe('Raised against the wrong member')
        ->and($fresh?->voided_by)->toBe($this->admin->id);

    // The next invoice carries on the sequence rather than reusing the number.
    expect(issueFor(500)->sequence)->toBe(2);
});

it('stays void even after its balance is touched', function (): void {
    $invoice = issueFor(100000);
    app(VoidInvoice::class)->handle($invoice, $this->admin, 'x');

    $invoice->fresh()?->refreshStatus();

    expect($invoice->fresh()?->status)->toBe(InvoiceStatus::Void);
});

it('reports outstanding and overdue invoices separately from plan fees', function (): void {
    $onTime = issueFor(100000);
    $late = issueFor(50000);
    $late->forceFill(['due_date' => now()->subDays(3)->toDateString()])->save();

    payAgainst($onTime, 25000);

    $metrics = new OrganisationMetrics(
        $this->organisation,
        [$this->club->id],
        ReportPeriod::fromStrings(null, null, $this->organisation->timezone),
    );

    expect($metrics->outstandingInvoices())->toBe(75000 + 50000)
        ->and($metrics->overdueInvoices())->toBe(1)
        // Plan fees are untouched by any of this.
        ->and($metrics->outstandingFees())->toBe(0);
});

it('refuses an invoice with no lines or a zero total', function (): void {
    expect(fn () => app(IssueInvoice::class)->handle($this->member, [], $this->admin))
        ->toThrow(InvalidArgumentException::class);

    expect(fn () => app(IssueInvoice::class)->handle($this->member, [
        ['billable_item_id' => null, 'description' => 'Free', 'quantity' => 1, 'unit_price_minor' => 0],
    ], $this->admin))->toThrow(InvalidArgumentException::class);

    expect(Invoice::query()->count())->toBe(0);
});
