<?php

declare(strict_types=1);

use App\Livewire\Settings\OrganisationSettings;
use App\Models\Domain;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

/**
 * One upload produces both images, and both are reachable without a session
 * because they appear on the sign-in page.
 */
beforeEach(function (): void {
    Storage::fake(config('filesystems.default'));

    $this->organisation = Organisation::factory()->create();

    Domain::factory()->create([
        'organisation_id' => $this->organisation->id,
        'hostname' => 'branding.test',
        'status' => 'active',
        'is_primary' => true,
    ]);

    app()->instance('tenant', $this->organisation);

    $user = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);
});

it('creates a logo and a favicon from a single upload', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('brandImage', UploadedFile::fake()->image('gym-sign.jpg', 1600, 1200))
        ->assertHasNoErrors();

    $organisation = $this->organisation->fresh();

    expect($organisation?->logo_path)->not->toBeNull()
        ->and($organisation?->favicon_path)->not->toBeNull();

    $disk = Storage::disk(config('filesystems.default'));

    expect($disk->exists((string) $organisation?->logo_path))->toBeTrue()
        ->and($disk->exists((string) $organisation?->favicon_path))->toBeTrue();
});

it('serves both images without a session, because the sign-in page needs them', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('brandImage', UploadedFile::fake()->image('logo.png', 400, 400));

    // A signed-out visitor is exactly who loads the sign-in page.
    auth('web')->logout();

    $this->get('http://branding.test/branding/logo')->assertOk();
    $this->get('http://branding.test/branding/favicon')->assertOk();
});

it('replaces the previous files rather than leaving them behind', function (): void {
    $component = Livewire::test(OrganisationSettings::class)
        ->set('brandImage', UploadedFile::fake()->image('first.png', 400, 400));

    $first = $this->organisation->fresh()?->logo_path;

    $component->set('brandImage', UploadedFile::fake()->image('second.png', 400, 400));

    $second = $this->organisation->fresh()?->logo_path;

    expect($second)->not->toBe($first)
        ->and(Storage::disk(config('filesystems.default'))->exists((string) $first))->toBeFalse();
});

it('removes both images together', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('brandImage', UploadedFile::fake()->image('logo.png', 400, 400))
        ->call('removeBrandImage');

    $organisation = $this->organisation->fresh();

    expect($organisation?->logo_path)->toBeNull()
        ->and($organisation?->favicon_path)->toBeNull()
        ->and($organisation?->logoUrl())->toBeNull();
});

it('rejects a file that is not an image', function (): void {
    Livewire::test(OrganisationSettings::class)
        ->set('brandImage', UploadedFile::fake()->create('accounts.pdf', 200, 'application/pdf'))
        ->assertHasErrors('brandImage');

    expect($this->organisation->fresh()?->logo_path)->toBeNull();
});

it('returns 404 for branding an organisation has not set', function (): void {
    $this->get('http://branding.test/branding/logo')->assertNotFound();
});
