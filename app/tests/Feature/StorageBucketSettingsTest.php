<?php

declare(strict_types=1);

use App\Enums\MembershipRole;
use App\Enums\StorageBucketStatus;
use App\Livewire\Settings\StorageBuckets as StorageBucketSettings;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\StorageBucket;
use App\Models\User;
use Livewire\Livewire;

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

function makeBucket(array $attributes = []): StorageBucket
{
    return StorageBucket::create([
        'organisation_id' => test()->organisation->id,
        'name' => 'Primary',
        'bucket' => 'gym-docs',
        'access_key' => 'original-key',
        'secret_key' => 'original-secret',
        'status' => StorageBucketStatus::Active,
        ...$attributes,
    ]);
}

it('adds a bucket and makes the first one the default', function (): void {
    Livewire::test(StorageBucketSettings::class)
        ->call('startCreate')
        ->assertSet('makeDefault', true)
        ->set('name', 'Member documents')
        ->set('bucket', 'my-gym-docs')
        ->set('accessKey', 'AKIAEXAMPLE')
        ->set('secretKey', 'shhh')
        ->call('save')
        ->assertHasNoErrors();

    $bucket = StorageBucket::query()->firstOrFail();

    expect($bucket->is_default)->toBeTrue()
        ->and($bucket->secret_key)->toBe('shhh');
});

it('keeps the stored secret when the field is left blank on an edit', function (): void {
    $bucket = makeBucket();

    Livewire::test(StorageBucketSettings::class)
        ->call('startEdit', $bucket->id)
        // The form never receives the secret, so a blank field is the normal
        // case rather than an instruction to clear it.
        ->assertSet('secretKey', '')
        ->set('name', 'Renamed')
        ->call('save')
        ->assertHasNoErrors();

    $fresh = $bucket->fresh();

    expect($fresh?->name)->toBe('Renamed')
        ->and($fresh?->secret_key)->toBe('original-secret')
        ->and($fresh?->access_key)->toBe('original-key');
});

it('replaces the secret when a new one is typed', function (): void {
    $bucket = makeBucket();

    Livewire::test(StorageBucketSettings::class)
        ->call('startEdit', $bucket->id)
        ->set('secretKey', 'rotated-secret')
        ->call('save');

    expect($bucket->fresh()?->secret_key)->toBe('rotated-secret');
});

it('requires credentials on a new bucket', function (): void {
    Livewire::test(StorageBucketSettings::class)
        ->call('startCreate')
        ->set('name', 'No creds')
        ->set('bucket', 'nope')
        ->call('save')
        ->assertHasErrors(['accessKey' => 'required', 'secretKey' => 'required']);
});

it('moves the default rather than ending up with two', function (): void {
    $first = makeBucket(['is_default' => true]);
    $second = makeBucket(['name' => 'Archive', 'bucket' => 'archive']);

    Livewire::test(StorageBucketSettings::class)->call('makeDefaultBucket', $second->id);

    expect($first->fresh()?->is_default)->toBeFalse()
        ->and($second->fresh()?->is_default)->toBeTrue()
        ->and(StorageBucket::query()->where('is_default', true)->count())->toBe(1);
});

it('keeps stored documents when a bucket is taken out of rotation', function (): void {
    $bucket = makeBucket(['is_default' => true]);

    Livewire::test(StorageBucketSettings::class)->call('remove', $bucket->id);

    $fresh = $bucket->fresh();

    // Still on record, just not selectable — the files are in the gym's bucket
    // and the documents pointing at it must keep resolving.
    expect($fresh)->not->toBeNull()
        ->and($fresh?->status)->toBe(StorageBucketStatus::Archived)
        ->and($fresh?->is_default)->toBeFalse();
});

it('is closed to staff, whatever permissions they hold', function (): void {
    $user = User::factory()->create();
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
        'role' => MembershipRole::User,
        'permissions' => ['documents.manage' => true, 'members.view' => true],
    ]);

    $this->actingAs($user);

    // A bucket key reads every document the organisation has ever stored.
    Livewire::test(StorageBucketSettings::class)->assertForbidden();
});
