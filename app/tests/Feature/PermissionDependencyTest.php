<?php

declare(strict_types=1);

use App\Enums\MembershipRole;
use App\Enums\Permission;
use App\Livewire\Staff\Form as StaffForm;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Livewire\Livewire;

/**
 * Some permissions are meaningless alone — editing a member you cannot see,
 * collecting a fee you cannot then find, messaging someone you cannot look up.
 * Granting one has to grant what it needs (MEP.md 4.2).
 */
beforeEach(function (): void {
    $this->organisation = Organisation::factory()->create();
    app()->instance('tenant', $this->organisation);

    $admin = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $admin->id,
    ]);
    $this->actingAs($admin);
});

function staffWith(array $permissions): OrganisationUser
{
    return OrganisationUser::factory()->create([
        'organisation_id' => test()->organisation->id,
        'role' => MembershipRole::User,
        'permissions' => collect($permissions)->mapWithKeys(fn (string $key): array => [$key => true])->all(),
    ]);
}

it('grants the prerequisites of a permission that cannot work without them', function (
    string $granted,
    array $implied,
): void {
    $staff = staffWith([$granted]);

    foreach ($implied as $key) {
        expect($staff->hasPermission($key))->toBeTrue("expected {$granted} to imply {$key}");
    }
})->with([
    'create members' => fn () => ['members.create', ['members.view']],
    'edit members' => fn () => ['members.edit', ['members.view']],
    'transfer members' => fn () => ['members.transfer', ['members.view']],
    'collect fees' => fn () => ['fees.collect', ['fees.view_own']],
]);

it('lets messaging stand on its own', function (): void {
    // Messages go to whoever an action concerned — a member, a walk-in payer —
    // so sending them implies neither the member list nor the staff list.
    $staff = staffWith(['notifications.send']);

    expect($staff->hasPermission('notifications.send'))->toBeTrue()
        ->and($staff->hasPermission('members.view'))->toBeFalse()
        ->and($staff->hasPermission('staff.view'))->toBeFalse();
});

it('grants nothing extra to a permission that stands on its own', function (): void {
    $staff = staffWith(['members.view']);

    expect($staff->hasPermission('members.view'))->toBeTrue()
        ->and($staff->hasPermission('members.create'))->toBeFalse()
        ->and($staff->hasPermission('fees.collect'))->toBeFalse()
        ->and($staff->hasPermission('staff.view'))->toBeFalse();
});

it('expands a set stored before the dependency existed', function (): void {
    // The row an older release wrote: a grant with its prerequisite off.
    $staff = OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'role' => MembershipRole::User,
        'permissions' => ['members.create' => true, 'members.view' => false],
    ]);

    expect($staff->hasPermission('members.view'))->toBeTrue();
});

it('ticks the prerequisites in the form as soon as a dependent is ticked', function (): void {
    Livewire::test(StaffForm::class)
        ->set('permissions', ['members.edit'])
        ->assertSet('permissions', ['members.view', 'members.edit']);
});

it('expands the set on save rather than trusting what the form submitted', function (): void {
    // The guard `Form::save()` actually applies. Checked directly because a
    // Livewire ->set() always fires the auto-ticking hook first, so going
    // through the component cannot prove the server would catch a request
    // that skipped it.
    expect(Permission::map(['members.edit'])['members.view'])->toBeTrue()
        ->and(Permission::map(['fees.collect'])['fees.view_own'])->toBeTrue();
});

it('writes the expanded set to the database', function (): void {
    $staff = staffWith(['members.view']);

    Livewire::test(StaffForm::class, ['organisationUser' => $staff])
        ->set('permissions', ['members.edit'])
        ->call('save');

    /** @var array<string, bool> $stored */
    $stored = $staff->fresh()?->permissions;

    expect($stored['members.view'])->toBeTrue()
        ->and($stored['members.edit'])->toBeTrue()
        // Every key is written, so the settings screen shows the real state.
        ->and($stored)->toHaveKey('staff.view')
        ->and($stored['staff.view'])->toBeFalse();
});

it('stores no permission keys for an administrator', function (): void {
    $staff = staffWith(['members.view']);

    Livewire::test(StaffForm::class, ['organisationUser' => $staff])
        ->set('role', MembershipRole::Admin->value)
        ->call('save');

    expect($staff->fresh()?->permissions)->toBe([]);
});

it('reports which granted permissions depend on a given one', function (): void {
    $dependents = Permission::MembersView->requiredBy(['members.edit', 'billing.create', 'fees.collect']);

    expect(collect($dependents)->map(fn (Permission $p): string => $p->value)->all())
        ->toBe(['members.edit', 'billing.create']);
});

it('drops a stored key that is no longer a real permission', function (): void {
    expect(Permission::expand(['members.view', 'members.teleport']))->toBe(['members.view']);
});

it('lets staff with the new key see the staff list but not manage it', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
        'role' => MembershipRole::User,
        'permissions' => ['staff.view' => true],
    ]);

    expect($user->can('viewAny', OrganisationUser::class))->toBeTrue()
        ->and($user->can('create', OrganisationUser::class))->toBeFalse();
});
