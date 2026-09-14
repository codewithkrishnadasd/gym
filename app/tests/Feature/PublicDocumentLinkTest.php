<?php

declare(strict_types=1);

use App\Actions\Billing\IssueInvoice;
use App\Actions\Payments\RecordFeePayment;
use App\Enums\NotificationActionType;
use App\Models\Club;
use App\Models\Domain;
use App\Models\FinancialAccount;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\WhatsappActionNotification;
use Illuminate\Support\Facades\Storage;

/**
 * Shareable invoice and receipt links: open without signing in, preview the
 * PDF, download it — and travel inside the WhatsApp message.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'name' => 'FitZone']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'links.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $this->account = FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);
    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Alex Morgan', 'phone' => '919876543210']);
});

function issueOne(): Invoice
{
    test()->actingAs(test()->admin->user);

    return app(IssueInvoice::class)->handle(test()->member, [[
        'billable_item_id' => null,
        'description' => 'Locker rental',
        'quantity' => 1,
        'unit_price_minor' => 50000,
    ]], test()->admin)->invoice;
}

it('gives every invoice a stable unguessable link that opens without signing in', function (): void {
    $invoice = issueOne();
    auth()->logout();

    $url = $invoice->publicUrl();

    expect($url)->toStartWith('http://links.test/i/')
        ->and(strlen($invoice->publicToken()))->toBe(40)
        ->and($invoice->fresh()?->publicUrl())->toBe($url);

    $this->get($url)
        ->assertOk()
        ->assertSee($invoice->number)
        ->assertSee('Alex Morgan')
        ->assertSee('Download PDF')
        ->assertSee('/i/'.$invoice->publicToken().'/pdf?inline=1', false);

    $this->get($url.'/pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf')
        ->assertHeader('Content-Disposition', 'attachment; filename=invoice-'.$invoice->number.'.pdf');

    $this->get($url.'/pdf?inline=1')->assertOk()->assertHeader('Content-Disposition', 'inline; filename=invoice-'.$invoice->number.'.pdf');
});

it('refuses a wrong token and a token from another organisation', function (): void {
    $invoice = issueOne();
    auth()->logout();

    $this->get('http://links.test/i/'.str_repeat('x', 40))->assertNotFound();

    $other = Organisation::factory()->create();
    Domain::factory()->create(['organisation_id' => $other->id, 'hostname' => 'other.test', 'status' => 'active', 'is_primary' => true]);

    $this->get('http://other.test/i/'.$invoice->publicToken())->assertNotFound();
});

it('puts the invoice link in the WhatsApp message', function (): void {
    $invoice = issueOne();

    $message = WhatsappActionNotification::query()
        ->where('action_type', NotificationActionType::InvoiceIssued)
        ->latest('id')
        ->firstOrFail();

    expect($message->message_snapshot)->toContain('View and download: '.$invoice->publicUrl());
});

it('shares a receipt for a confirmed payment, with the link in the confirmation message', function (): void {
    $this->actingAs($this->admin->user);

    $payment = app(RecordFeePayment::class)->handle([
        'club_id' => $this->club->id,
        'member_id' => $this->member->id,
        'subscription_id' => null,
        'purpose' => 'other',
        'payer_name' => $this->member->name,
        'amount_minor' => 120000,
        'currency_code' => 'INR',
        'payment_method' => 'cash',
        'financial_account_id' => $this->account->id,
        'transaction_reference' => null,
        'payment_date' => now()->toDateString(),
        'notes' => null,
    ], $this->admin, confirmImmediately: true)->payment;

    $message = WhatsappActionNotification::query()
        ->where('action_type', NotificationActionType::FeePaymentConfirmed)
        ->latest('id')
        ->firstOrFail();

    expect($message->message_snapshot)->toContain('View your receipt: '.$payment->publicUrl());

    $this->get('http://links.test/finance/payments/'.$payment->id)->assertOk()->assertSee('Share the receipt')->assertSee($payment->publicUrl());

    auth()->logout();

    $this->get($payment->publicUrl())->assertOk()->assertSee('Receipt')->assertSee('Alex Morgan')->assertSee('Download PDF');
    $this->get($payment->publicUrl().'/pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf');
});

it('shows the share panel on the invoice page and hides it once void', function (): void {
    $invoice = issueOne();

    $this->get('http://links.test/billing/'.$invoice->id)->assertOk()->assertSee('Share with the member')->assertSee($invoice->publicUrl());

    $invoice->forceFill(['status' => 'void'])->save();

    $this->get('http://links.test/billing/'.$invoice->id)->assertOk()->assertDontSee('Share with the member');
});

it('prints the organisation logo on invoices and receipts', function (): void {
    Storage::fake(config('filesystems.default'));

    // A 1×1 PNG, enough to be a real image for the PDF renderer.
    $png = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPhfDwAChwGA60e6kgAAAABJRU5ErkJggg==', true);
    Storage::disk(config('filesystems.default'))->put('branding/logo.png', (string) $png);
    $this->organisation->update(['logo_path' => 'branding/logo.png']);

    $invoice = issueOne();

    expect($this->organisation->fresh()?->logoDataUri())->toStartWith('data:image/png;base64,');

    $html = view('pdf.invoice', ['organisation' => $this->organisation->fresh(), 'invoice' => $invoice->load('lines', 'member', 'club', 'payments')])->render();

    expect($html)->toContain('<img src="data:image/png;base64,')->toContain('class="logo"');

    // And the rendered PDF still comes out.
    auth()->logout();
    $this->get($invoice->publicUrl().'/pdf')->assertOk()->assertHeader('Content-Type', 'application/pdf');

    // Without a logo nothing is printed in its place.
    $this->organisation->update(['logo_path' => null]);

    expect(view('pdf.invoice', ['organisation' => $this->organisation->fresh(), 'invoice' => $invoice])->render())->not->toContain('class="logo"');
});
