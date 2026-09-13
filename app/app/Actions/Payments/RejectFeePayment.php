<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Enums\ConfirmationStatus;
use App\Exceptions\LifecycleViolation;
use App\Models\AuditEvent;
use App\Models\FeePayment;
use App\Models\OrganisationUser;
use Illuminate\Support\Facades\DB;

/**
 * Rejects a staff-submitted payment. The submitted record is preserved
 * exactly as it was — rejection only moves the lifecycle forward and records
 * the reason, so the collector can still see what they submitted and why it
 * was refused (MEP.md 6.8, 10).
 *
 * A rejected payment never touched the subscription balance (only
 * confirmation applies it), so there is nothing to unwind here.
 */
final class RejectFeePayment
{
    public function handle(FeePayment $payment, OrganisationUser $actor, string $reason): FeePayment
    {
        return DB::transaction(function () use ($payment, $actor, $reason): FeePayment {
            /** @var FeePayment $locked */
            $locked = FeePayment::query()
                ->whereKey($payment->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->confirmation_status !== ConfirmationStatus::PendingAdminConfirmation) {
                throw LifecycleViolation::payment($locked->confirmation_status->value, 'rejected');
            }

            $before = ['confirmation_status' => $locked->confirmation_status->value];

            $locked->forceFill([
                'confirmation_status' => ConfirmationStatus::Rejected,
                'confirmed_by' => $actor->id,
                'confirmed_at' => now(),
                'rejection_reason' => $reason,
            ])->save();

            AuditEvent::record(
                $locked,
                'fee_payment.rejected',
                $actor,
                $before,
                ['confirmation_status' => ConfirmationStatus::Rejected->value],
                ['reason' => $reason],
            );

            return $locked;
        });
    }
}
