<?php

declare(strict_types=1);

use App\Enums\ConfirmationStatus;
use App\Livewire\Dashboard\Index;
use App\Models\Club;
use App\Models\Domain;
use App\Models\FeePayment;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use Livewire\Livewire;

/**
 * Every KPI card on the dashboard opens the list it was counted from, with
 * the same period and club applied, so the rows shown add up to the figure.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'kpi.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);
});

it('links each admin card to a filtered list for the same period and club', function (): void {
    $html = $this->get('http://kpi.test/dashboard?range=custom&from=2026-09-01&to=2026-09-14&club='.$this->club->id)
        ->assertOk()
        ->getContent();

    $decoded = html_entity_decode($html);

    expect($decoded)
        ->toContain('/finance/payments?status=confirmed&from=2026-09-01&to=2026-09-14&club='.$this->club->id)
        ->toContain('/members?balance=due&club='.$this->club->id)
        ->toContain('/finance/expenses?status=completed&from=2026-09-01&to=2026-09-14&club='.$this->club->id)
        ->toContain('/reports?tab=finance&range=custom&from=2026-09-01&to=2026-09-14&club='.$this->club->id)
        ->toContain('/members?status=active&club='.$this->club->id)
        ->toContain('/members?joinedFrom=2026-09-01&joinedTo=2026-09-14&club='.$this->club->id)
        ->toContain('/members?endingBy=')
        ->toContain('/finance/confirmations?club='.$this->club->id)
        ->toContain('/reports?tab=attendance&range=custom')
        ->toContain('/attendance/members?date=');
});

it('narrows the member list the way the cards count', function (): void {
    $plan = Plan::factory()->create(['organisation_id' => $this->organisation->id]);

    $newcomer = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Newcomer Nair', 'joined_at' => '2026-09-05']);
    $veteran = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id, 'name' => 'Veteran Verma', 'joined_at' => '2024-01-10']);

    MemberSubscription::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $veteran->id,
        'club_id' => $this->club->id,
        'plan_id' => $plan->id,
        'status' => 'active',
        'start_date' => '2026-09-01',
        'end_date' => '2026-09-25',
        'amount_due_minor' => 300000,
        'amount_paid_minor' => 100000,
    ]);

    $this->get('http://kpi.test/members?joinedFrom=2026-09-01&joinedTo=2026-09-14')
        ->assertOk()->assertSee('Newcomer Nair')->assertDontSee('Veteran Verma')->assertSee('Showing:')->assertSee('Joined 01 Sep 2026');

    $this->get('http://kpi.test/members?balance=due')
        ->assertOk()->assertSee('Veteran Verma')->assertDontSee('Newcomer Nair')->assertSee('Owing plan fees');

    $this->travelTo('2026-09-14 10:00', function () use ($newcomer): void {
        $this->get('http://kpi.test/members?endingBy=2026-10-14')
            ->assertOk()->assertSee('Veteran Verma')->assertDontSee($newcomer->name);

        $this->get('http://kpi.test/members?endingBy=2026-09-20')
            ->assertOk()->assertDontSee('Veteran Verma');
    });
});

it('links the staff cards to their own payments across all time', function (): void {
    $staffUser = User::factory()->create();
    OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => $staffUser->id, 'permissions' => ['fees.collect' => true, 'fees.view_own' => true]]);

    $html = html_entity_decode((string) $this->actingAs($staffUser)->get('http://kpi.test/dashboard')->assertOk()->getContent());

    expect($html)
        ->toContain('/finance/payments?status=confirmed&from='.$this->organisation->created_at?->toDateString())
        ->toContain('/finance/payments?status=pending_admin_confirmation&from=')
        ->toContain('/finance/payments?status=rejected&from=');
});

it('offers presets as chips, a custom range picker only when Custom is chosen, and one floating Collect fee button', function (): void {
    $html = $this->get('http://kpi.test/dashboard')
        ->assertOk()
        ->assertSee('This month')
        ->assertSee('Custom')
        ->assertDontSee('dateRange({', false)
        ->assertSee('aria-label="Collect fee"', false)
        ->getContent();

    // No Reports button in the page header any more (the sidebar link remains).
    $header = substr($html, strpos($html, '<main'), strpos($html, 'Date range preset') - strpos($html, '<main'));
    expect($header)->not->toContain('Reports');

    Livewire::test(Index::class)
        ->call('startCustom')
        ->assertSet('range', 'custom')
        ->assertSee('dateRange({', false)
        ->call('setRange', '2026-09-20', '2026-09-01')
        // Ends are put in order, whichever was clicked first.
        ->assertSet('from', '2026-09-01')
        ->assertSet('to', '2026-09-20')
        ->assertSet('range', 'custom')
        ->call('applyPreset', 'today')
        ->assertSet('range', 'today');
});

it('folds alerts into one attention button that opens a list, coloured for the most serious', function (): void {
    // A staff collection awaiting confirmation is a caution-level alert.
    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);
    $collector = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id, 'user_id' => User::factory()->create()->id]);
    FeePayment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'member_id' => $member->id,
        'club_id' => $this->club->id,
        'collected_by' => $collector->id,
        'confirmation_status' => ConfirmationStatus::PendingAdminConfirmation,
        'payment_date' => now()->toDateString(),
    ]);

    $this->get('http://kpi.test/dashboard')
        ->assertOk()
        ->assertSee('open-modal\', \'alerts\'', false)
        ->assertSee('bg-caution text-white')
        ->assertSee('Needs attention')
        ->assertSee('awaiting confirmation')
        ->assertSee('/finance/confirmations', false)
        // Alerts no longer sit inline above the cards.
        ->assertDontSee('md:grid-cols-2 empty:hidden', false);
});
