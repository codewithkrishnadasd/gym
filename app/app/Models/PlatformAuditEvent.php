<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PlatformAuditEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Append-only audit trail for root/platform-level actions not scoped to any
 * single organisation. See MEP.md 3.3.
 */
#[Fillable(['actor_platform_admin_id', 'action', 'entity_type', 'entity_id', 'before', 'after', 'metadata'])]
class PlatformAuditEvent extends Model
{
    /** @use HasFactory<PlatformAuditEventFactory> */
    use HasFactory;

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'before' => 'array',
            'after' => 'array',
            'metadata' => 'array',
        ];
    }

    /**
     * @return BelongsTo<PlatformAdmin, $this>
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(PlatformAdmin::class, 'actor_platform_admin_id');
    }

    /**
     * @param  array<string, mixed>|null  $before
     * @param  array<string, mixed>|null  $after
     * @param  array<string, mixed>  $metadata
     */
    public static function record(
        PlatformAdmin $actor,
        string $action,
        string $entityType,
        ?int $entityId,
        ?array $before = null,
        ?array $after = null,
        array $metadata = [],
    ): self {
        return self::create([
            'actor_platform_admin_id' => $actor->id,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'before' => $before,
            'after' => $after,
            'metadata' => $metadata,
        ]);
    }
}
