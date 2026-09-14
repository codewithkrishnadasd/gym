<?php

declare(strict_types=1);

use App\Enums\MembershipRole;
use App\Enums\StorageBucketStatus;
use App\Livewire\Documents\Panel;
use App\Models\Club;
use App\Models\Document;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\StorageBucket;
use App\Models\User;
use App\Support\Storage\BucketDisk;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

/**
 * Documents hold identity papers and medical records, so the properties worth
 * testing are the ones that go wrong quietly: credentials readable in the
 * database, an upload landing in a bucket the organisation did not choose, a
 * client filename deciding a storage key, and one gym's staff reaching
 * another's files.
 *
 * The S3 disk is swapped for an in-memory fake, so these exercise the real
 * upload path without needing a bucket.
 */
beforeEach(function (): void {
    $this->fakeDisk = Storage::fake('bucket-under-test');

    // Only `build()` is intercepted — Livewire's own temporary-upload disk has
    // to keep working, so a full facade mock is not an option here.
    Storage::partialMock()->shouldReceive('build')->andReturn($this->fakeDisk)->byDefault();

    $this->organisation = Organisation::factory()->create();
    app()->instance('tenant', $this->organisation);

    $this->club = Club::factory()->create(['organisation_id' => $this->organisation->id]);

    $adminUser = User::factory()->create();
    OrganisationUser::factory()->admin()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $adminUser->id,
    ]);
    $this->actingAs($adminUser);

    $this->member = Member::factory()->create([
        'organisation_id' => $this->organisation->id,
        'primary_club_id' => $this->club->id,
    ]);

    $this->bucket = StorageBucket::create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Primary',
        'bucket' => 'gym-docs',
        'endpoint' => 'https://storage.googleapis.com',
        'use_path_style' => true,
        'access_key' => 'GOOG1EXAMPLEKEY',
        'secret_key' => 'super-secret-value',
        'path_prefix' => 'fitzone',
        'is_default' => true,
        'status' => StorageBucketStatus::Active,
    ]);
});

it('encrypts the credentials at rest', function (): void {
    $raw = DB::table('storage_buckets')->where('id', $this->bucket->id)->first();

    expect($raw?->secret_key)->not->toContain('super-secret-value')
        ->and($raw?->access_key)->not->toContain('GOOG1EXAMPLEKEY')
        // Still readable through the model, which is the only path that should.
        ->and($this->bucket->fresh()?->secret_key)->toBe('super-secret-value');
});

it('never shows a working key back to the browser', function (): void {
    expect($this->bucket->maskedAccessKey())->toBe('••••EKEY')
        ->and($this->bucket->toArray())->not->toHaveKey('secret_key')
        ->and($this->bucket->toArray())->not->toHaveKey('access_key');
});

it('stores an upload under the organisation prefix with a generated name', function (): void {
    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->set('file', UploadedFile::fake()->create('../../etc/passwd.pdf', 12, 'application/pdf'))
        ->set('title', 'Identity proof')
        ->set('category', 'Identity proof')
        ->call('upload')
        ->assertHasNoErrors();

    $document = Document::query()->first();

    expect($document)->not->toBeNull()
        ->and($document?->path)->toStartWith('fitzone/members/'.$this->member->id.'/')
        // The client filename never reaches the storage key.
        ->and($document?->path)->not->toContain('passwd')
        ->and($document?->path)->not->toContain('..')
        // But it is kept for display and download.
        ->and($document?->original_filename)->toContain('passwd.pdf')
        ->and($document?->storage_bucket_id)->toBe($this->bucket->id);

    $this->fakeDisk->assertExists((string) $document?->path);
});

it('records which bucket a file went into, so changing the default does not orphan it', function (): void {
    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->set('file', UploadedFile::fake()->create('first.pdf', 5))
        ->set('title', 'First')
        ->call('upload');

    $second = StorageBucket::create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Archive',
        'bucket' => 'gym-archive',
        'access_key' => 'k',
        'secret_key' => 's',
        'status' => StorageBucketStatus::Active,
    ]);

    $this->bucket->forceFill(['is_default' => false])->save();
    $second->forceFill(['is_default' => true])->save();

    expect(Document::query()->first()?->storage_bucket_id)->toBe($this->bucket->id);
});

it('uploads into the bucket the operator chose, not the default', function (): void {
    $second = StorageBucket::create([
        'organisation_id' => $this->organisation->id,
        'name' => 'Archive',
        'bucket' => 'gym-archive',
        'access_key' => 'k',
        'secret_key' => 's',
        'status' => StorageBucketStatus::Active,
    ]);

    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->set('file', UploadedFile::fake()->create('scan.pdf', 5))
        ->set('title', 'Scan')
        ->set('bucketId', $second->id)
        ->call('upload');

    expect(Document::query()->first()?->storage_bucket_id)->toBe($second->id);
});

it('attaches documents to staff as well as members', function (): void {
    $staff = OrganisationUser::factory()->create(['organisation_id' => $this->organisation->id]);

    Livewire::test(Panel::class, ['subjectType' => 'user', 'subjectId' => $staff->id])
        ->set('file', UploadedFile::fake()->create('contract.pdf', 5))
        ->set('title', 'Employment contract')
        ->call('upload')
        ->assertHasNoErrors();

    expect(Document::query()->first()?->path)->toStartWith('fitzone/staff/'.$staff->id.'/');
});

it('offers no upload form until storage is configured', function (): void {
    StorageBucket::query()->delete();

    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->assertViewHas('storageConfigured', false)
        ->assertSee('Storage is not set up yet');
});

it('deletes the object as well as the record', function (): void {
    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->set('file', UploadedFile::fake()->create('scan.pdf', 5))
        ->set('title', 'Scan')
        ->call('upload');

    $document = Document::query()->firstOrFail();
    $path = $document->path;

    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->call('remove', $document->id);

    expect(Document::query()->count())->toBe(0);
    $this->fakeDisk->assertMissing($path);
});

it('keeps documents readable after their bucket is taken out of rotation', function (): void {
    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->set('file', UploadedFile::fake()->create('scan.pdf', 5))
        ->set('title', 'Scan')
        ->call('upload');

    $this->bucket->forceFill(['status' => StorageBucketStatus::Archived, 'is_default' => false])->save();

    $document = Document::query()->firstOrFail();

    $this->get(route('tenant.documents.download', $document))->assertOk();
});

it('refuses the download to staff without the document permission', function (): void {
    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->set('file', UploadedFile::fake()->create('scan.pdf', 5))
        ->set('title', 'Scan')
        ->call('upload');

    $document = Document::query()->firstOrFail();

    $plainStaff = User::factory()->create();
    OrganisationUser::factory()->create([
        'organisation_id' => $this->organisation->id,
        'user_id' => $plainStaff->id,
        'role' => MembershipRole::User,
        'permissions' => ['members.view' => true],
    ]);

    $this->actingAs($plainStaff);

    // Seeing a member is not seeing their identity papers.
    $this->get(route('tenant.documents.download', $document))->assertForbidden();
});

it('sends the file as an attachment so an uploaded HTML file cannot run', function (): void {
    Livewire::test(Panel::class, ['subjectType' => 'member', 'subjectId' => $this->member->id])
        ->set('file', UploadedFile::fake()->create('note.html', 2, 'text/html'))
        ->set('title', 'Note')
        ->call('upload');

    $response = $this->get(route('tenant.documents.download', Document::query()->firstOrFail()));

    $response->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff');

    expect($response->headers->get('Content-Disposition'))->toStartWith('attachment;');
});

it('confines each organisation to its own prefix', function (): void {
    expect($this->bucket->prefixFor('members/1/x.pdf'))->toBe('fitzone/members/1/x.pdf');

    $unprefixed = new StorageBucket(['path_prefix' => null]);

    expect($unprefixed->prefixFor('/members/1/x.pdf'))->toBe('members/1/x.pdf');
});

it('builds a filesystem from the stored credentials', function (): void {
    Storage::partialMock()
        ->shouldReceive('build')
        ->once()
        ->withArgs(function (array $config): bool {
            return $config['driver'] === 's3'
                && $config['key'] === 'GOOG1EXAMPLEKEY'
                && $config['secret'] === 'super-secret-value'
                && $config['bucket'] === 'gym-docs'
                && $config['endpoint'] === 'https://storage.googleapis.com'
                && $config['use_path_style_endpoint'] === true
                && $config['visibility'] === 'private';
        })
        ->andReturn($this->fakeDisk);

    expect(BucketDisk::for($this->bucket))->toBeInstanceOf(Filesystem::class);
});
