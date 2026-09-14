<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use App\Enums\StorageBucketStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Document;
use App\Models\StorageBucket;
use App\Support\Storage\BucketDisk;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Manages the object-storage buckets an organisation uploads documents into
 * (MEP.md 8.5).
 *
 * Credentials are write-only from this screen: a saved secret is never sent
 * back to the browser, and leaving the field blank on an edit keeps the stored
 * one. That way the settings page cannot be used to read out a working key,
 * and an admin editing the bucket name does not have to re-enter credentials
 * they may not have.
 */
class StorageBuckets extends Component
{
    use ResolvesMembership;

    public ?int $editingId = null;

    public string $name = '';

    public string $bucket = '';

    public string $region = '';

    public string $endpoint = '';

    public bool $usePathStyle = false;

    public string $accessKey = '';

    public string $secretKey = '';

    public string $pathPrefix = '';

    public bool $makeDefault = false;

    public ?string $testResult = null;

    public bool $testPassed = false;

    public function mount(): void
    {
        $this->authorize('manage', StorageBucket::class);
    }

    public function startCreate(): void
    {
        $this->authorize('manage', StorageBucket::class);

        $this->resetForm();

        // Firebase and Google Cloud Storage are the common case here and both
        // need path-style addressing, so the form opens ready for them.
        $this->endpoint = 'https://storage.googleapis.com';
        $this->usePathStyle = true;
        $this->makeDefault = ! StorageBucket::query()->where('status', StorageBucketStatus::Active)->exists();

        $this->dispatch('open-modal', 'storage-bucket');
    }

    public function startEdit(int $bucketId): void
    {
        $this->authorize('manage', StorageBucket::class);

        $record = StorageBucket::query()->findOrFail($bucketId);

        $this->resetForm();

        $this->editingId = $record->id;
        $this->name = $record->name;
        $this->bucket = $record->bucket;
        $this->region = (string) $record->region;
        $this->endpoint = (string) $record->endpoint;
        $this->usePathStyle = $record->use_path_style;
        $this->pathPrefix = (string) $record->path_prefix;
        $this->makeDefault = $record->is_default;

        // Credentials are deliberately not loaded back into the form.
        $this->dispatch('open-modal', 'storage-bucket');
    }

    public function save(): void
    {
        $this->authorize('manage', StorageBucket::class);

        $isNew = $this->editingId === null;

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:80'],
            'bucket' => ['required', 'string', 'max:255'],
            'region' => ['nullable', 'string', 'max:60'],
            'endpoint' => ['nullable', 'url', 'max:255'],
            'pathPrefix' => ['nullable', 'string', 'max:180', 'regex:/^[A-Za-z0-9._\-\/]+$/'],
            // Required on create, optional on edit — blank means "keep what is
            // already stored", which is the only way to edit a bucket whose
            // secret the current admin does not have.
            'accessKey' => [Rule::requiredIf($isNew), 'nullable', 'string', 'max:255'],
            'secretKey' => [Rule::requiredIf($isNew), 'nullable', 'string', 'max:512'],
        ], [
            'pathPrefix.regex' => 'Use letters, numbers, dots, dashes, and slashes only.',
        ], [
            'accessKey' => 'access key',
            'secretKey' => 'secret key',
            'pathPrefix' => 'folder prefix',
        ]);

        $record = $isNew
            ? new StorageBucket(['created_by' => $this->currentMembership()->id])
            : StorageBucket::query()->findOrFail($this->editingId);

        $before = $isNew ? null : $record->only(['name', 'bucket', 'region', 'endpoint', 'path_prefix', 'is_default']);

        $record->fill([
            'name' => $validated['name'],
            'driver' => 's3',
            'bucket' => $validated['bucket'],
            'region' => $validated['region'] ?: null,
            'endpoint' => $validated['endpoint'] ?: null,
            'use_path_style' => $this->usePathStyle,
            'path_prefix' => trim((string) $validated['pathPrefix'], '/') ?: null,
            'status' => StorageBucketStatus::Active,
        ]);

        if ($validated['accessKey']) {
            $record->access_key = $validated['accessKey'];
        }

        if ($validated['secretKey']) {
            $record->secret_key = $validated['secretKey'];
        }

        $record->save();

        if ($this->makeDefault) {
            $this->promote($record);
        }

        // Credentials are never part of the audit payload; the columns are
        // encrypted precisely so they exist in one place only.
        AuditEvent::record(
            $record,
            $isNew ? 'storage_bucket.created' : 'storage_bucket.updated',
            $this->currentMembership(),
            $before,
            $record->only(['name', 'bucket', 'region', 'endpoint', 'path_prefix', 'is_default']),
        );

        $this->dispatch('close-modal');
        $this->resetForm();

        session()->flash('status', 'Storage settings saved. Run "Test connection" to confirm the credentials work.');
    }

    /**
     * Proves the credentials by writing, reading and deleting a probe object,
     * and records the outcome so the list shows whether a bucket is known-good
     * rather than merely filled in.
     */
    public function test(int $bucketId): void
    {
        $this->authorize('manage', StorageBucket::class);

        $record = StorageBucket::query()->findOrFail($bucketId);

        $result = BucketDisk::verify($record);

        $record->forceFill([
            'last_verified_at' => $result['ok'] ? now() : null,
            'last_error' => $result['error'],
        ])->save();

        $this->testPassed = $result['ok'];
        $this->testResult = $result['ok']
            ? '"'.$record->name.'" is reachable and writable.'
            : '"'.$record->name.'" failed: '.$result['error'];
    }

    public function makeDefaultBucket(int $bucketId): void
    {
        $this->authorize('manage', StorageBucket::class);

        $record = StorageBucket::query()->findOrFail($bucketId);

        abort_unless($record->isActive(), 422);

        $this->promote($record);

        session()->flash('status', '"'.$record->name.'" is now the default for new uploads.');
    }

    /**
     * Removing a bucket stops new uploads reaching it. Documents already there
     * keep working: the file is still in the gym's own bucket and the row still
     * knows which one, so taking the configuration out of rotation must not
     * make existing paperwork unreachable.
     */
    public function remove(int $bucketId): void
    {
        $this->authorize('manage', StorageBucket::class);

        $record = StorageBucket::query()->findOrFail($bucketId);

        $record->forceFill([
            'status' => StorageBucketStatus::Archived,
            'is_default' => false,
        ])->save();

        AuditEvent::record($record, 'storage_bucket.removed', $this->currentMembership(), null, [
            'name' => $record->name,
            'documents' => $record->documents()->count(),
        ]);

        session()->flash('status', '"'.$record->name.'" was removed. Documents already stored there still open.');
    }

    public function restore(int $bucketId): void
    {
        $this->authorize('manage', StorageBucket::class);

        StorageBucket::query()->findOrFail($bucketId)
            ->forceFill(['status' => StorageBucketStatus::Active])
            ->save();
    }

    /**
     * The unique partial index allows one default per organisation, so the
     * previous holder is cleared before the new one is set.
     */
    private function promote(StorageBucket $record): void
    {
        StorageBucket::query()
            ->where('id', '!=', $record->id)
            ->where('is_default', true)
            ->update(['is_default' => false]);

        $record->forceFill(['is_default' => true])->save();
    }

    private function resetForm(): void
    {
        $this->reset([
            'editingId', 'name', 'bucket', 'region', 'endpoint',
            'usePathStyle', 'accessKey', 'secretKey', 'pathPrefix', 'makeDefault',
        ]);

        $this->resetErrorBag();
    }

    /**
     * @return Collection<int, StorageBucket>
     */
    protected function buckets(): Collection
    {
        return StorageBucket::query()
            ->withCount('documents')
            ->orderByDesc('is_default')
            ->orderBy('status')
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.settings.storage-buckets', [
            'buckets' => $this->buckets(),
            'documentCount' => Document::query()->count(),
        ]);
    }
}
