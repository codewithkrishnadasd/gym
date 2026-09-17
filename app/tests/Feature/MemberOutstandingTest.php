<?php

declare(strict_types=1);

use App\Enums\InvoiceStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use App\Support\WhatsApp\RecipientContext;

/**
 * "Outstanding" for a member is one figure wherever it is shown — the list
 * column, the member page, the dashboard card, and the {balanceDue} message
 * variable: plan balances, what is left of the admission fee, and open
 * invoices. Before, the list read only the live plan and the page ignored
 * invoices, so a member with an unpaid invoice showed as owing nothing.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);
    Domain::factory()->create(['organisation_id' => $this->organisation->id, 'hostname' => 'owed.test', 'status' => 'active', 'is_primary' => true]);
    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $user = User::factory()->create();
    $this->admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $this->organisation->id, 'user_id' => $user->id]);
    $this->actingAs($user);
});

function memberWith(array $attributes = []): Member
{
    return Member::factory()->create([
        'organisation_id' => test()->organisation->id,
        'primary_club_id' => test()->club->id,
        'admission_fee_minor' => 0,
        'admission_discount_minor' => 0,
        'admission_paid_minor' => 0,
        ...$attributes,
    ]);
}

it('adds open invoices, lapsed plan balances and the admission fee together', function (): void {
    $member = memberWith(['name' => 'Owes Everything', 'admission_fee_minor' => 50000, 'admission_paid_minor' => 20000]);
    $plan = Plan::factory()->create(['organisation_id' => $this->organisation->id]);

    // A lapsed term still half unpaid, and a live one fully paid.
    MemberSubscription::factory()->create([
        'organisation_id' => $this->organisation->id, 'member_id' => $member->id, 'club_id' => $this->club->id, 'plan_id' => $plan->id,
        'status' => SubscriptionStatus::Expired, 'amount_due_minor' => 100000, 'amount_paid_minor' => 40000,
        'start_date' => now()->subMonths(2), 'end_date' => now()->subMonth(),
    ]);
    MemberSubscription::factory()->create([
        'organisation_id' => $this->organisation->id, 'member_id' => $member->id, 'club_id' => $this->club->id, 'plan_id' => $plan->id,
        'status' => SubscriptionStatus::Active, 'amount_due_minor' => 100000, 'amount_paid_minor' => 100000,
    ]);

    // An invoice part-paid with a discount, and a paid one that must not count.
    Invoice::factory()->create([
        'organisation_id' => $this->organisation->id, 'member_id' => $member->id, 'club_id' => $this->club->id, 'created_by' => $this->admin->id,
        'status' => InvoiceStatus::PartiallyPaid, 'total_minor' => 30000, 'discount_minor' => 5000, 'paid_minor' => 10000,
    ]);
    Invoice::factory()->create([
        'organisation_id' => $this->organisation->id, 'member_id' => $member->id, 'club_id' => $this->club->id, 'created_by' => $this->admin->id,
        'status' => InvoiceStatus::Paid, 'total_minor' => 30000, 'paid_minor' => 30000,
    ]);

    // 60,000 plan + 15,000 invoice + 30,000 admission.
    expect($member->outstandingMinor())->toBe(105000)
        ->and(Member::query()->withOutstanding()->findOrFail($member->id)->listedOutstandingMinor())->toBe(105000)
        ->and(RecipientContext::forMember($member, $this->organisation)['balanceDue'])->toBe($this->organisation->money(105000))
        ->and((new OrganisationMetrics($this->organisation, null, ReportPeriod::fromStrings(null, null, $this->organisation->timezone)))->outstandingTotal())->toBe(105000);
});

it('shows an unpaid invoice as outstanding on the list and the member page', function (): void {
    $owing = memberWith(['name' => 'Invoice Pending']);
    $clear = memberWith(['name' => 'All Square']);

    Invoice::factory()->create([
        'organisation_id' => $this->organisation->id, 'member_id' => $owing->id, 'club_id' => $this->club->id, 'created_by' => $this->admin->id,
        'status' => InvoiceStatus::Issued, 'total_minor' => 250000, 'paid_minor' => 0,
    ]);

    $this->get('http://owed.test/members')
        ->assertOk()
        ->assertSee('Invoice Pending')
        ->assertSee($this->organisation->money(250000));

    // The balance filter finds them too, and leaves the settled member out.
    $this->get('http://owed.test/members?balance=due')
        ->assertOk()
        ->assertSee('Invoice Pending')
        ->assertDontSee('All Square');

    $this->get('http://owed.test/members/'.$owing->id)
        ->assertOk()
        ->assertSee($this->organisation->money(250000));

    expect($clear->outstandingMinor())->toBe(0);
});
