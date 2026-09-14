<?php

declare(strict_types=1);

use App\Livewire\Staff\Form as StaffForm;
use App\Models\Club;
use App\Models\Domain;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * The WhatsApp number is the sign-in identity, and one `users` row serves every
 * organisation a person belongs to (MEP.md 5.3). Leaving it uneditable meant a
 * staff member who changed their number was locked out permanently, so it is
 * editable — with the uniqueness that identity requires.
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create(['default_country_code' => 'IN']);

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'staffphone.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $admin = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $admin->id,
    ]);
    $this->actingAs($admin);

    $this->staffUser = User::factory()->create(['phone' => '919000000111', 'name' => 'Sam Rivera']);
    $this->staff = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $this->staffUser->id,
    ]);
});

it('lets an admin change a staff sign-in number', function (): void {
    Livewire::test(StaffForm::class, ['organisationUser' => $this->staff])
        ->assertSet('phone', '919000000111')
        ->set('phone', '98765 43210')
        ->call('save')
        ->assertHasNoErrors();

    // Stored normalised, so the login lookup still matches what they type.
    expect($this->staffUser->fresh()?->phone)->toBe('919876543210');
});

it('refuses a number that already signs another account in', function (): void {
    User::factory()->create(['phone' => '919876543210']);

    Livewire::test(StaffForm::class, ['organisationUser' => $this->staff])
        ->set('phone', '9876543210')
        ->call('save')
        ->assertHasErrors('phone');

    expect($this->staffUser->fresh()?->phone)->toBe('919000000111');
});

it('refuses a number that cannot be dialled', function (): void {
    Livewire::test(StaffForm::class, ['organisationUser' => $this->staff])
        ->set('phone', 'not a number')
        ->call('save')
        ->assertHasErrors('phone');

    expect($this->staffUser->fresh()?->phone)->toBe('919000000111');
});

it('accepts the number the person already has, unchanged', function (): void {
    Livewire::test(StaffForm::class, ['organisationUser' => $this->staff])
        ->set('name', 'Sam R')
        ->call('save')
        ->assertHasNoErrors();

    expect($this->staffUser->fresh()?->phone)->toBe('919000000111')
        ->and($this->staffUser->fresh()?->name)->toBe('Sam R');
});

it('prefills the member when collecting a fee from their page', function (): void {
    $club = Club::factory()->create(['organisation_id' => $this->organisation->id]);
    FinancialAccount::factory()->create(['organisation_id' => $this->organisation->id]);

    $member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $club->id,
        'name' => 'Alex Morgan',
    ]);

    // "Collect fee" links with ?member=<id>, a query parameter rather than a
    // route segment — Livewire does not hand those to mount() on its own.
    $this->get(route('tenant.finance.payments.create', ['member' => $member->id]))
        ->assertOk()
        ->assertSee('Alex Morgan');
});
