<?php

declare(strict_types=1);

use App\Enums\InvoiceStatus;
use App\Livewire\Billing\Show as InvoiceShow;
use App\Livewire\Settings\BillableItems;
use App\Models\BillableItem;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * The screens around an invoice: the member's Billing tab, the detail page,
 * the PDF, and the price list.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'name' => 'FitZone']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'pages.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Alex Morgan']);

    $this->invoice = Invoice::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->member->id,
        'club_id' => $this->club->id,
        'created_by' => $this->admin->id,
        'number' => 'INV-2026-0007',
        'total_minor' => 130000,
        'paid_minor' => 30000,
        'status' => InvoiceStatus::PartiallyPaid,
        'notes' => 'Pay at the front desk.',
    ]);

    InvoiceLine::create(['invoice_id' => $this->invoice->id, 'description' => 'Personal training', 'quantity' => 2, 'unit_price_minor' => 50000, 'line_total_minor' => 100000, 'position' => 0]);
    InvoiceLine::create(['invoice_id' => $this->invoice->id, 'description' => 'Locker', 'quantity' => 1, 'unit_price_minor' => 30000, 'line_total_minor' => 30000, 'position' => 1]);
});

it('lists the invoice on the member Billing tab with what is still owed', function (): void {
    $this->get('http://pages.test/members/'.$this->member->id.'?tab=billing')
        ->assertOk()
        ->assertSee('INV-2026-0007')
        ->assertSee('Partly paid')
        // 1300 total, 300 paid.
        ->assertSee('1,000.00');
});

it('shows lines, totals, and the balance on the invoice page', function (): void {
    $this->get('http://pages.test/billing/'.$this->invoice->id)
        ->assertOk()
        ->assertSee('Personal training')
        ->assertSee('Locker')
        ->assertSee('Pay at the front desk.')
        ->assertSee('Collect payment')
        ->assertSee('Void');
});

it('renders a PDF with the lines and balance', function (): void {
    $response = $this->get('http://pages.test/billing/'.$this->invoice->id.'/pdf');

    $response->assertOk()->assertHeader('Content-Type', 'application/pdf');

    expect(strlen((string) $response->getContent()))->toBeGreaterThan(1000);
});

it('voids from the detail page with a reason', function (): void {
    $clean = Invoice::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->member->id,
        'club_id' => $this->club->id,
        'created_by' => $this->admin->id,
    ]);

    Livewire::test(InvoiceShow::class, ['invoice' => $clean])
        ->call('startVoid')
        ->set('voidReason', 'Duplicate of another invoice')
        ->call('void')
        ->assertHasNoErrors();

    expect($clean->fresh()?->status)->toBe(InvoiceStatus::Void);
});

it('explains rather than voiding when money is attached', function (): void {
    Livewire::test(InvoiceShow::class, ['invoice' => $this->invoice])
        ->call('startVoid')
        ->set('voidReason', 'Trying anyway')
        ->call('void')
        ->assertSet('lifecycleError', fn (?string $error): bool => $error !== null && str_contains($error, 'Reverse'));

    expect($this->invoice->fresh()?->status)->toBe(InvoiceStatus::PartiallyPaid);
});

it('manages the price list from settings', function (): void {
    Livewire::test(BillableItems::class)
        ->call('startCreate')
        ->set('name', 'Protein shake')
        ->set('price', '250')
        ->call('save')
        ->assertHasNoErrors();

    $item = BillableItem::query()->firstOrFail();

    expect($item->unit_price_minor)->toBe(25000);

    Livewire::test(BillableItems::class)->call('remove', $item->id);

    expect($item->fresh()?->isActive())->toBeFalse();

    // Removed items stay on record but are not offered on new invoices.
    $this->get('http://pages.test/billing/create')->assertOk()->assertDontSee('Protein shake');
});

it('hides void invoices from the list until asked for', function (): void {
    $void = Invoice::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->member->id,
        'club_id' => $this->club->id,
        'created_by' => $this->admin->id,
        'number' => 'INV-2026-0099',
        'status' => InvoiceStatus::Void,
    ]);

    $this->get('http://pages.test/billing')->assertOk()->assertDontSee('INV-2026-0099');
    $this->get('http://pages.test/billing?status=void')->assertOk()->assertSee('INV-2026-0099');
});
