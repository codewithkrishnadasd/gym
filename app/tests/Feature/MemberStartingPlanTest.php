<?php

declare(strict_types=1);

use App\Enums\ClubAssignmentStatus;
use App\Enums\PlanStatus;
use App\Livewire\Members\Form as MemberForm;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Plan;
use App\Models\User;
use Livewire\Livewire;

/**
 * A new member can be given a plan in the same breath as being created,
 * under the same rules as starting one from their page.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['currency_code' => 'INR', 'timezone' => 'Asia/Kolkata']);
    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    $this->plan = Plan::factory()->create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Quarterly',
        'price_minor' => 450000,
        'duration_days' => 90,
        'status' => PlanStatus::Active,
    ]);
});

function signInAdmin(Organisation $organisation): OrganisationUser
{
    $user = User::factory()->create();
    $admin = OrganisationUser::factory()->admin()->create(['organisation_id' => $organisation->id, 'user_id' => $user->id]);
    test()->actingAs($user);

    return $admin;
}

it('starts the chosen plan on the new member from the joining date', function (): void {
    signInAdmin($this->organisation);

    Livewire::test(MemberForm::class)
        ->set('name', 'Priya Nair')
        ->set('phone', '9876543210')
        ->set('primaryClubId', $this->club->id)
        ->set('joinedAt', '2026-09-01')
        ->set('planId', $this->plan->id)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect();

    $member = Member::query()->where('name', 'Priya Nair')->firstOrFail();
    $subscription = $member->subscriptions()->firstOrFail();

    expect($subscription->plan_id)->toBe($this->plan->id)
        ->and($subscription->start_date->toDateString())->toBe('2026-09-01')
        ->and($subscription->end_date->toDateString())->toBe('2026-11-29')
        ->and($subscription->amount_due_minor)->toBe(450000);
});

it('honours a discount and a separate start date', function (): void {
    signInAdmin($this->organisation);

    Livewire::test(MemberForm::class)
        ->set('name', 'Priya Nair')
        ->set('phone', '9876543210')
        ->set('primaryClubId', $this->club->id)
        ->set('planId', $this->plan->id)
        ->set('planStartDate', '2026-10-01')
        ->set('planDiscount', '500')
        ->call('save')
        ->assertHasNoErrors();

    $subscription = Member::query()->where('name', 'Priya Nair')->firstOrFail()->subscriptions()->firstOrFail();

    expect($subscription->start_date->toDateString())->toBe('2026-10-01')
        ->and($subscription->amount_due_minor)->toBe(400000)
        ->and($subscription->discount_minor)->toBe(50000);
});

it('creates the member with no plan when none is chosen', function (): void {
    signInAdmin($this->organisation);

    Livewire::test(MemberForm::class)
        ->assertSee('Starting plan')
        ->set('name', 'Priya Nair')
        ->set('phone', '9876543210')
        ->set('primaryClubId', $this->club->id)
        ->call('save')
        ->assertHasNoErrors();

    expect(Member::query()->where('name', 'Priya Nair')->firstOrFail()->subscriptions()->count())->toBe(0);
});

it('does not offer a plan to staff, who cannot start plans', function (): void {
    $user = User::factory()->create();
    $staff = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
        'permissions' => ['members.view' => true, 'members.create' => true],
    ]);
    ClubUserAssignment::factory()->create([
        'organisation_id' => $this->organisation->id,
        'organisation_user_id' => $staff->id,
        'club_id' => $this->club->id,
        'status' => ClubAssignmentStatus::Active,
    ]);
    $this->actingAs($user);

    Livewire::test(MemberForm::class)
        ->assertDontSee('Starting plan')
        ->set('name', 'Priya Nair')
        ->set('phone', '9876543210')
        ->set('planId', $this->plan->id)
        ->call('save')
        ->assertHasErrors(['planId']);

    expect(Member::query()->where('name', 'Priya Nair')->exists())->toBeFalse();
});

it('does not offer a plan while editing', function (): void {
    signInAdmin($this->organisation);

    $member = Member::factory()->create(['organisation_id' => $this->organisation->id, 'primary_club_id' => $this->club->id]);

    Livewire::test(MemberForm::class, ['member' => $member])->assertDontSee('Starting plan');
});
