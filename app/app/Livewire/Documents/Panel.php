<?php

declare(strict_types=1);

namespace App\Livewire\Documents;

use App\Enums\StorageBucketStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Document;
use App\Models\Member;
use App\Models\OrganisationUser;
use App\Models\StorageBucket;
use App\Support\Storage\BucketDisk;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use Throwable;

/**
 * The documents attached to one member or one staff member.
 *
 * Shared by both because the two are the same job — upload a file, find it
 * again, take it away — and a second copy of an upload path handling other
 * people's identity documents is a second place for an authorization mistake.
 */
class Panel extends Component
{
    use ResolvesMembership, WithFileUploads;

    /** 'member' or 'user', matching the enforced morph map. */
    public string $subjectType = 'member';

    public int $subjectId = 0;

    public ?TemporaryUploadedFile $file = null;

    public string $title = '';

    public string $category = '';

    public ?int $bucketId = null;

    public ?string $uploadError = null;

    /** Common document types offered as suggestions, never enforced. */
    public const SUGGESTED_CATEGORIES = [
        'Identity proof', 'Address proof', 'Medical certificate',
        'Contract', 'Waiver', 'Photograph', 'Other',
    ];

    public function mount(string $subjectType, int $subjectId): void
    {
        $this->subjectType = $subjectType;
        $this->subjectId = $subjectId;

        $this->authorize('viewAny', Document::class);

        $this->bucketId = $this->defaultBucket()?->id;
    }

    /**
     * The filename is a reasonable first guess at a title, and one an operator
     * will usually keep — offering it saves a step without hiding the field.
     */
    public function updatedFile(): void
    {
        $this->uploadError = null;

        if ($this->title === '' && $this->file !== null) {
            $this->title = Str::of($this->file->getClientOriginalName())
                ->beforeLast('.')
                ->replace(['_', '-'], ' ')
                ->trim()
                ->limit(120, '')
                ->value();
        }
    }

    public function upload(): void
    {
        $this->authorize('create', Document::class);

        $subject = $this->subject();

        abort_if($subject === null, 404);

        $validated = $this->validate([
            'file' => ['required', 'file', 'max:20480'],
            'title' => ['required', 'string', 'max:150'],
            'category' => ['nullable', 'string', 'max:80'],
            'bucketId' => ['required', 'integer'],
        ], [
            'file.max' => 'Files are capped at 20 MB. Scan or photograph documents at a lower resolution.',
            'bucketId.required' => 'Choose which bucket this file goes into.',
        ], ['bucketId' => 'bucket']);

        $bucket = $this->activeBuckets()->firstWhere('id', $validated['bucketId']);

        if ($bucket === null) {
            $this->addError('bucketId', 'That bucket is no longer available.');

            return;
        }

        /** @var TemporaryUploadedFile $upload */
        $upload = $this->file;

        // The stored name is generated, never the uploaded one: a client
        // filename is attacker-controlled and would otherwise decide a key in
        // the gym's own bucket. The original is kept as a column for display.
        $path = $bucket->prefixFor(sprintf(
            '%s/%d/%s.%s',
            $this->subjectType === 'member' ? 'members' : 'staff',
            $subject->getKey(),
            Str::random(32),
            strtolower($upload->getClientOriginalExtension() ?: 'bin'),
        ));

        // Streamed rather than read into a string: a 20 MB upload held in
        // memory alongside PHP's own copy of it is how a worker runs out.
        $stream = fopen($upload->getRealPath(), 'rb');

        if ($stream === false) {
            $this->uploadError = 'The uploaded file could not be read. Try again.';

            return;
        }

        try {
            BucketDisk::for($bucket)->put($path, $stream);
        } catch (Throwable $exception) {
            // A storage outage or a rotated key must read as a storage problem
            // the admin can act on, not a stack trace.
            $this->uploadError = 'Upload failed: '.trim(explode("\n", $exception->getMessage())[0]);

            return;
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $document = Document::create([
            'subject_type' => $this->subjectType,
            'subject_id' => $subject->getKey(),
            'storage_bucket_id' => $bucket->id,
            'title' => $validated['title'],
            'category' => $validated['category'] ?: null,
            'path' => $path,
            'original_filename' => $upload->getClientOriginalName(),
            'mime_type' => $upload->getMimeType(),
            'size_bytes' => $upload->getSize() ?: 0,
            'uploaded_by' => $this->currentMembership()->id,
        ]);

        AuditEvent::record($document, 'document.uploaded', $this->currentMembership(), null, [
            'subject_type' => $this->subjectType,
            'subject_id' => $subject->getKey(),
            'title' => $document->title,
            'bucket' => $bucket->name,
        ]);

        $this->reset(['file', 'title', 'category', 'uploadError']);

        session()->flash('status', 'Document uploaded.');
    }

    /**
     * Removes the record and the object together. The row goes first: a file
     * left in the bucket with nothing pointing at it is recoverable, whereas a
     * row pointing at a deleted object is a broken download for everyone.
     */
    public function remove(int $documentId): void
    {
        $document = Document::query()->with('bucket')->findOrFail($documentId);

        $this->authorize('delete', $document);

        $bucket = $document->bucket;

        AuditEvent::record($document, 'document.removed', $this->currentMembership(), [
            'title' => $document->title,
            'original_filename' => $document->original_filename,
        ], null);

        $path = $document->path;
        $document->delete();

        if ($bucket !== null) {
            try {
                BucketDisk::for($bucket)->delete($path);
            } catch (Throwable) {
                // The record is already gone and the audit event stands. A
                // failed object delete is a storage-side cleanup problem, not a
                // reason to leave the document listed.
            }
        }

        session()->flash('status', 'Document removed.');
    }

    private function subject(): ?Model
    {
        return $this->subjectType === 'member'
            ? Member::query()->find($this->subjectId)
            : OrganisationUser::query()->find($this->subjectId);
    }

    /**
     * @return Collection<int, StorageBucket>
     */
    protected function activeBuckets(): Collection
    {
        return StorageBucket::query()
            ->where('status', StorageBucketStatus::Active)
            ->orderByDesc('is_default')
            ->orderBy('name')
            ->get();
    }

    private function defaultBucket(): ?StorageBucket
    {
        $buckets = $this->activeBuckets();

        return $buckets->firstWhere('is_default', true) ?? $buckets->first();
    }

    /**
     * @return Collection<int, Document>
     */
    protected function documents(): Collection
    {
        return Document::query()
            ->with(['bucket:id,name,status', 'uploadedBy.user:id,name'])
            ->where('subject_type', $this->subjectType)
            ->where('subject_id', $this->subjectId)
            ->latest()
            ->get();
    }

    public function render(): View
    {
        $buckets = $this->activeBuckets();

        return view('livewire.documents.panel', [
            'documents' => $this->documents(),
            'buckets' => $buckets,
            // Nothing about this panel works until an admin has configured
            // storage, so the empty state explains that rather than offering a
            // form that can only fail.
            'storageConfigured' => $buckets->isNotEmpty(),
            'categories' => self::SUGGESTED_CATEGORIES,
        ]);
    }
}
