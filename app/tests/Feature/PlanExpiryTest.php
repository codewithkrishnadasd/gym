<?php

declare(strict_types=1);

use App\Enums\SubscriptionHealth;
use App\Enums\SubscriptionStatus;
use App\Models\Club;
use App\Models\Domain;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use App\Support\Reporting\OrganisationMetrics;
use App\Support\Reporting\ReportPeriod;
use Illuminate\Support\Carbon;

/**
 * Nothing writes `expired` to a subscription when its term simply runs out —
 * the status column only changes when a renewal supersedes it, or an operator
 * pauses or cancels it. Expiry is therefore derived from `end_date` at read
 * time, which is what these cover: the badge, the filter, and the metric all
 * have to agree, and all have to be right the moment the date passes.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['timezone' => 'Asia/Kolkata']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'expiry.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $this->plan = Plan::factory()->create(['organisation_id' => $this->organisation->id]);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);

    $this->today = Carbon::today($this->organisation->timezone);
});

function memberWithTerm(string $name, ?int $endsInDays, SubscriptionStatus $status = SubscriptionStatus::Active): Member
{
    $member = Member::factory()->create([
        'organisation_id' => test()->organisation->id,
        'primary_club_id' => test()->club->id,
        'name' => $name,
    ]);

    if ($endsInDays !== null) {
        MemberSubscription::factory()->create([
            'organisation_id' => test()->organisation->id,
            'member_id' => $member->id,
            'club_id' => test()->club->id,
            'plan_id' => test()->plan->id,
            'start_date' => test()->today->copy()->subDays(60)->toDateString(),
            'end_date' => test()->today->copy()->addDays($endsInDays)->toDateString(),
            'status' => $status,
        ]);
    }

    return $member;
}

it('derives the state from the end date, not the stored status', function (
    ?int $endsInDays,
    SubscriptionStatus $stored,
    SubscriptionHealth $expected,
): void {
    $member = memberWithTerm('Test Person', $endsInDays, $stored);
    $subscription = $member->subscriptions()->first();

    expect(SubscriptionHealth::for($subscription, $this->today))->toBe($expected);
})->with([
    // The case that matters: a term that ran out months ago is still stored
    // as active, because nothing ever writes to that column on its own.
    'long lapsed, still stored active' => fn () => [-90, SubscriptionStatus::Active, SubscriptionHealth::Expired],
    'ended yesterday' => fn () => [-1, SubscriptionStatus::Active, SubscriptionHealth::Expired],
    'ends today' => fn () => [0, SubscriptionStatus::Active, SubscriptionHealth::ExpiringSoon],
    'ends inside the window' => fn () => [7, SubscriptionStatus::Active, SubscriptionHealth::ExpiringSoon],
    'ends on the window edge' => fn () => [SubscriptionHealth::WARNING_DAYS, SubscriptionStatus::Active, SubscriptionHealth::ExpiringSoon],
    'ends beyond the window' => fn () => [SubscriptionHealth::WARNING_DAYS + 1, SubscriptionStatus::Active, SubscriptionHealth::Active],
    // An operator's decision is never overridden by the calendar.
    'paused mid-term' => fn () => [30, SubscriptionStatus::Paused, SubscriptionHealth::Paused],
    'cancelled mid-term' => fn () => [30, SubscriptionStatus::Cancelled, SubscriptionHealth::Cancelled],
    'no plan at all' => fn () => [null, SubscriptionStatus::Active, SubscriptionHealth::None],
]);

it('counts days in the label rather than saying "soon"', function (): void {
    $member = memberWithTerm('Soon Person', 3);
    $subscription = $member->subscriptions()->first();

    expect(SubscriptionHealth::ExpiringSoon->detailedLabel($subscription, $this->today))
        ->toBe('Expires in 3 days');

    $lapsed = memberWithTerm('Lapsed Person', -5)->subscriptions()->first();

    expect(SubscriptionHealth::Expired->detailedLabel($lapsed, $this->today))
        ->toBe('Expired 5 days ago');
});

it('badges an expired plan in the member list', function (): void {
    memberWithTerm('Lapsed Person', -20);
    memberWithTerm('Healthy Person', 90);

    $response = $this->get('http://expiry.test/members')->assertOk();

    $response->assertSee('Expired 20 days ago')
        // A healthy plan gets no badge — one on every row is noise people
        // learn to ignore.
        ->assertDontSee('Expires in 90 days');
});

it('filters the member list by derived plan state', function (): void {
    memberWithTerm('Lapsed Person', -20);
    memberWithTerm('Soon Person', 5);
    memberWithTerm('Healthy Person', 90);
    memberWithTerm('Planless Person', null);

    $expectations = [
        'expired' => ['Lapsed Person', ['Soon Person', 'Healthy Person', 'Planless Person']],
        'expiring' => ['Soon Person', ['Lapsed Person', 'Healthy Person', 'Planless Person']],
        'active' => ['Healthy Person', ['Lapsed Person', 'Soon Person', 'Planless Person']],
        'none' => ['Planless Person', ['Lapsed Person', 'Soon Person', 'Healthy Person']],
    ];

    foreach ($expectations as $filter => [$shown, $hidden]) {
        $response = $this->get('http://expiry.test/members?plan='.$filter)->assertOk();

        $response->assertSee($shown);

        foreach ($hidden as $name) {
            $response->assertDontSee($name);
        }
    }
});

it('shows the real state on the member detail page instead of a green card', function (): void {
    $member = memberWithTerm('Lapsed Person', -30);

    $this->get('http://expiry.test/members/'.$member->id)
        ->assertOk()
        ->assertSee('Expired 30 days ago');
});

it('counts lapsed plans separately from expiring ones', function (): void {
    memberWithTerm('Lapsed One', -20);
    memberWithTerm('Lapsed Two', -1);
    memberWithTerm('Soon Person', 5);
    memberWithTerm('Healthy Person', 90);

    $metrics = new OrganisationMetrics(
        $this->organisation,
        [$this->club->id],
        ReportPeriod::fromStrings(null, null, $this->organisation->timezone),
    );

    expect($metrics->lapsedSubscriptions())->toBe(2)
        // The expiring count looks forward only; the two are not the same
        // question and must not be merged.
        ->and($metrics->expiringSubscriptions(30))->toBe(1);
});

it('leaves the stored status untouched — expiry is a reading, not a write', function (): void {
    $member = memberWithTerm('Lapsed Person', -40);

    $this->get('http://expiry.test/members')->assertOk();

    expect($member->subscriptions()->first()?->status)->toBe(SubscriptionStatus::Active);
});
