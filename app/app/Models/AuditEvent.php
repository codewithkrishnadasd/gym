<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\AuditEventFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Append-only. There is deliberately no update or delete path anywhere in
 * the application for this model — see MEP.md 5.13 and technology.md 4.2.
 */
#[Fillable(['organisation_id', 'actor_user_id', 'actor_role', 'action', 'entity_type', 'entity_id', 'before', 'after', 'metadata'])]
class AuditEvent extends Model
{
    /** @use HasFactory<AuditEventFactory> */
    use BelongsToOrganisation, HasFactory;

    /** The `actor_role` recorded when the platform admin acts. */
    public const PLATFORM_ROLE = 'platform';

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
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function actor(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'actor_user_id');
    }

    /**
     * @param  array<string, mixed>|null  $before
     * @param  array<string, mixed>|null  $after
     * @param  array<string, mixed>  $metadata
     */
    public static function record(
        Model $entity,
        string $action,
        ?OrganisationUser $actor,
        ?array $before = null,
        ?array $after = null,
        array $metadata = [],
    ): self {
        // No actor: the platform admin acting from the console, who has no
        // membership in the organisation to point at.
        return self::create([
            'actor_user_id' => $actor?->id,
            'actor_role' => $actor !== null ? $actor->role->value : self::PLATFORM_ROLE,
            'action' => $action,
            'entity_type' => $entity->getMorphClass(),
            'entity_id' => $entity->getKey(),
            'before' => $before,
            'after' => $after,
            'metadata' => $metadata,
        ]);
    }
}
