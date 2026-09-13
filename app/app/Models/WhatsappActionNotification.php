<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\NotificationStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\WhatsappActionNotificationFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Not a delivery log: `opened` means the admin launched the WhatsApp deep
 * link, never that WhatsApp delivered the message. The unique index on
 * (entity_id, action_type, operation_id) is the idempotency guarantee for
 * retried operations — see MEP.md 5.14.
 */
#[Fillable([
    'organisation_id', 'recipient_type', 'recipient_id', 'recipient_name', 'recipient_phone',
    'entity_type', 'entity_id', 'action_type', 'message_template_version', 'message_snapshot',
    'status', 'created_by', 'operation_id',
])]
class WhatsappActionNotification extends Model
{
    /** @use HasFactory<WhatsappActionNotificationFactory> */
    use BelongsToOrganisation, HasFactory;

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'recipient_type' => NotificationRecipientType::class,
            'entity_type' => NotificationEntityType::class,
            'action_type' => NotificationActionType::class,
            'status' => NotificationStatus::class,
            'opened_at' => 'datetime',
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
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'opened_by');
    }
}
