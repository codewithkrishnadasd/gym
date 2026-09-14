<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\StorageBucketStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * One organisation's object-storage credentials (MEP.md 8.5).
 *
 * The two credential columns use the `encrypted` cast, so they are ciphertext
 * at rest and a database dump cannot be turned into bucket access. They are
 * also `Hidden`, which keeps them out of anything that serialises the model —
 * a log line, a queued job payload, an accidental `toArray()` in a view.
 */
#[Fillable([
    'organisation_id', 'name', 'driver', 'bucket', 'region', 'endpoint',
    'use_path_style', 'access_key', 'secret_key', 'path_prefix',
    'is_default', 'status', 'created_by',
])]
#[Hidden(['access_key', 'secret_key'])]
class StorageBucket extends Model
{
    use BelongsToOrganisation;

    protected function casts(): array
    {
        return [
            'status' => StorageBucketStatus::class,
            'access_key' => 'encrypted',
            'secret_key' => 'encrypted',
            'use_path_style' => 'boolean',
            'is_default' => 'boolean',
            'last_verified_at' => 'datetime',
        ];
    }

    /**
     * @return HasMany<Document, $this>
     */
    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function isActive(): bool
    {
        return $this->status === StorageBucketStatus::Active;
    }

    /**
     * Enough of the key to recognise which credential is configured, without
     * showing a working one back to anybody who opens the settings screen.
     */
    public function maskedAccessKey(): string
    {
        $key = (string) $this->access_key;

        return $key === '' ? '—' : str_repeat('•', 4).substr($key, -4);
    }

    /**
     * Where this organisation's files live inside the bucket. Always prefixed,
     * so a bucket shared with something else cannot be walked into from here.
     */
    public function prefixFor(string $path): string
    {
        $prefix = trim((string) $this->path_prefix, '/');

        return $prefix === '' ? ltrim($path, '/') : $prefix.'/'.ltrim($path, '/');
    }

    public function describeTarget(): string
    {
        $host = $this->endpoint === null ? 'Amazon S3' : (string) parse_url($this->endpoint, PHP_URL_HOST);

        return $this->bucket.' · '.($host ?: $this->endpoint);
    }
}
