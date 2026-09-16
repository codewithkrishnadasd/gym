<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationRecipientType;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\CustomMessageTemplateFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A wording the organisation wrote for itself — a renewal nudge, a class
 * reminder, a festive greeting — offered in the WhatsApp menu on every
 * member's (or staff member's) page and filled in for that person when
 * chosen. See MessageComposer::MEMBER_VARIABLES for what it may mention.
 */
#[Fillable(['organisation_id', 'audience', 'name', 'body', 'created_by'])]
class CustomMessageTemplate extends Model
{
    /** @use HasFactory<CustomMessageTemplateFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'audience' => NotificationRecipientType::class,
        ];
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'created_by');
    }

    /**
     * Stamped onto notifications composed from this wording, so a snapshot
     * records where it came from (and "save as template" can offer to
     * update the same one).
     */
    public function versionLabel(): string
    {
        return 'custom:'.$this->id;
    }
}
