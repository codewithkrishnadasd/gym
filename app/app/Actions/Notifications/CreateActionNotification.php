<?php

declare(strict_types=1);

namespace App\Actions\Notifications;

use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\NotificationStatus;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\WhatsappActionNotification;
use App\Support\PhoneNumber;
use App\Support\WhatsApp\MessageComposer;
use Illuminate\Database\QueryException;

/**
 * Creates the message snapshot an admin can preview, copy, or open in
 * WhatsApp after a business action has already succeeded (MEP.md 5.14, 8.2).
 *
 * Two invariants matter here:
 *   - Creation is idempotent. `operation_id` plus the entity and action type
 *     form a unique index, so retrying the same operation (a double submit, a
 *     refreshed detail page, a re-queued job) reuses the existing row instead
 *     of producing a second notification.
 *   - Failure is never fatal. A missing phone number or a losing race on the
 *     unique index must not roll back the completed business action, so this
 *     records an `unavailable` state or returns the winner rather than
 *     throwing.
 */
final class CreateActionNotification
{
    /**
     * @param  array<string, string|null>  $context  Placeholder values for the message template.
     */
    public function handle(
        Organisation $organisation,
        NotificationActionType $type,
        NotificationRecipientType $recipientType,
        int $recipientId,
        string $recipientName,
        ?string $recipientPhone,
        NotificationEntityType $entityType,
        int $entityId,
        OrganisationUser $actor,
        string $operationId,
        array $context = [],
    ): ?WhatsappActionNotification {
        if (! $organisation->notificationsEnabled($type)) {
            return null;
        }

        $existing = WhatsappActionNotification::query()
            ->where('entity_id', $entityId)
            ->where('action_type', $type)
            ->where('operation_id', $operationId)
            ->first();

        if ($existing) {
            return $existing;
        }

        $normalisedPhone = PhoneNumber::normalise($recipientPhone, $organisation->defaultCountry());

        $message = MessageComposer::render($type, $organisation, [
            'memberName' => $recipientName,
            ...$context,
        ]);

        $attributes = [
            'recipient_type' => $recipientType,
            'recipient_id' => $recipientId,
            'recipient_name' => $recipientName,
            'recipient_phone' => $normalisedPhone,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action_type' => $type,
            'message_template_version' => MessageComposer::versionFor($organisation, $type),
            'message_snapshot' => $message,
            // No usable number still produces a snapshot so the admin can copy
            // the message manually (MEP.md 10).
            'status' => $normalisedPhone === null ? NotificationStatus::Unavailable : NotificationStatus::Ready,
            'created_by' => $actor->id,
            'operation_id' => $operationId,
        ];

        try {
            return WhatsappActionNotification::create($attributes);
        } catch (QueryException $exception) {
            if (! $this->isUniqueViolation($exception)) {
                throw $exception;
            }

            // Another concurrent writer won the idempotency race — its row is
            // the canonical one.
            return WhatsappActionNotification::query()
                ->where('entity_id', $entityId)
                ->where('action_type', $type)
                ->where('operation_id', $operationId)
                ->first();
        }
    }

    private function isUniqueViolation(QueryException $exception): bool
    {
        return $exception->getCode() === '23505';
    }
}
