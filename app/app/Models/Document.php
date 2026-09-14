<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * A file attached to a member or a staff member. The bucket is recorded per
 * document rather than looked up when the file is fetched: an organisation
 * that adds a second bucket, or changes which one is the default, must not
 * lose track of where earlier uploads went.
 */
#[Fillable([
    'organisation_id', 'subject_type', 'subject_id', 'storage_bucket_id',
    'title', 'category', 'path', 'original_filename', 'mime_type', 'size_bytes', 'uploaded_by',
])]
class Document extends Model
{
    use BelongsToOrganisation;

    protected function casts(): array
    {
        return [
            'size_bytes' => 'integer',
        ];
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<StorageBucket, $this>
     */
    public function bucket(): BelongsTo
    {
        return $this->belongsTo(StorageBucket::class, 'storage_bucket_id');
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function uploadedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'uploaded_by');
    }

    public function humanSize(): string
    {
        $bytes = $this->size_bytes;

        return match (true) {
            $bytes >= 1_048_576 => round($bytes / 1_048_576, 1).' MB',
            $bytes >= 1_024 => round($bytes / 1_024).' KB',
            default => $bytes.' B',
        };
    }

    public function isImage(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->mime_type === 'application/pdf';
    }
}
