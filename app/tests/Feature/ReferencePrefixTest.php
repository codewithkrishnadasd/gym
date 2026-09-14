<?php

declare(strict_types=1);

use App\Actions\Billing\IssueInvoice;
use App\Livewire\Settings\OrganisationSettings;
use App\Models\Club;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Every record is referred to by a prefixed number — MEM-42, PMT-17 — and the
 * prefix is the organisation's to choose. One helper produces it everywhere,
 * so a change in settings shows up in lists, headings, receipts and exports.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'refs.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $user = User::factory()->create(['name' => 'Admin Person']);
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);

    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
        'name' => 'Alex Morgan',
    ]);
});

it('shows the member reference in the list and on the detail page', function (): void {
    $reference = 'MEM-'.$this->member->id;

    $this->get('http://refs.test/members')->assertOk()->assertSee($reference);
    $this->get('http://refs.test/members/'.$this->member->id)->assertOk()->assertSee($reference);
});

it('shows references in the other listings too', function (): void {
    $payment = FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->member->id,
        'club_id' => $this->club->id,
        'collected_by' => $this->admin->id,
    ]);

    $this->get('http://refs.test/staff')->assertOk()->assertSee('STF-'.$this->admin->id);
    $this->get('http://refs.test/clubs')->assertOk()->assertSee('CLB-'.$this->club->id);
    $this->get('http://refs.test/finance/payments')->assertOk()->assertSee('PMT-'.$payment->id);
    $this->get('http://refs.test/finance/payments/'.$payment->id)->assertOk()->assertSee('Payment PMT-'.$payment->id);
});

it('lets the organisation choose its own prefixes, normalising case', function (): void {
    Livewire::test(OrganisationSettings::class, ['tab' => 'terminology'])
        ->set('idPrefixes.member', 'ath')
        ->set('idPrefixes.payment', 'RCPT')
        ->call('saveIdPrefixes')
        ->assertHasNoErrors();

    $organisation = $this->organisation->fresh();

    expect($organisation?->idPrefix('member'))->toBe('ATH')
        ->and($organisation?->idPrefix('payment'))->toBe('RCPT')
        // Untouched entities keep their defaults.
        ->and($organisation?->idPrefix('club'))->toBe('CLB')
        ->and($organisation?->reference('member', 42))->toBe('ATH-42');
});

it('rejects prefixes that are not short alphanumerics', function (): void {
    Livewire::test(OrganisationSettings::class, ['tab' => 'terminology'])
        ->set('idPrefixes.member', 'MEM-')
        ->call('saveIdPrefixes')
        ->assertHasErrors(['idPrefixes.member']);

    Livewire::test(OrganisationSettings::class, ['tab' => 'terminology'])
        ->set('idPrefixes.expense', 'TOOLONGPREFIX')
        ->call('saveIdPrefixes')
        ->assertHasErrors(['idPrefixes.expense']);

    expect($this->organisation->fresh()?->id_prefixes)->toBeNull();
});

it('carries a changed prefix through the screens, the export, the receipt and new invoice numbers', function (): void {
    $this->organisation->update(['id_prefixes' => ['member' => 'ATH', 'payment' => 'RCPT', 'invoice' => 'BILL']]);

    $payment = FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $this->member->id,
        'club_id' => $this->club->id,
        'collected_by' => $this->admin->id,
    ]);

    $this->get('http://refs.test/members')->assertOk()->assertSee('ATH-'.$this->member->id)->assertDontSee('MEM-');
    $this->get('http://refs.test/finance/payments')->assertOk()->assertSee('RCPT-'.$payment->id);

    $csv = $this->get('http://refs.test/members/export')->assertOk()->streamedContent();
    expect($csv)->toContain('ATH-'.$this->member->id);

    $this->get('http://refs.test/finance/payments/'.$payment->id.'/receipt')
        ->assertOk()
        ->assertHeader('Content-Disposition', 'attachment; filename=receipt-RCPT-'.$payment->id.'.pdf');

    $invoice = app(IssueInvoice::class)->handle($this->member, [[
        'billable_item_id' => null,
        'description' => 'Locker',
        'quantity' => 1,
        'unit_price_minor' => 50000,
    ]], $this->admin)->invoice;

    expect($invoice->number)->toStartWith('BILL-')->and($invoice->number)->toEndWith('-0001');
});
