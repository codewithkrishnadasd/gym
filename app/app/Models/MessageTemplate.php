<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationActionType;
use App\Models\Concerns\BelongsToOrganisation;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An organisation's override for one action's message body. No row means the
 * built-in default in `MessageComposer` applies, so deleting a row is how a
 * customised template is reverted (MEP.md 6.8).
 */
#[Fillable(['organisation_id', 'action_type', 'body', 'version', 'updated_by'])]
class MessageTemplate extends Model
{
    use BelongsToOrganisation;

    protected function casts(): array
    {
        return [
            'action_type' => NotificationActionType::class,
            'version' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'updated_by');
    }

    /**
     * Stamped onto every notification generated from this wording, so a
     * snapshot always records which revision produced it.
     */
    public function versionLabel(): string
    {
        return 'custom-v'.$this->version;
    }
}
