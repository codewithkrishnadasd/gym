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
 * Reverses a confirmed payment. Financial corrections are made by adding a
 * reversal, never by deleting or editing the original confirmation — the
 * confirmed event stays in history and in reports, marked as reversed
 * (MEP.md 6.11, 13.23).
 *
 * The subscription credit applied at confirmation is withdrawn here so that
 * confirmed-minus-reversed remains the true paid total.
 */
final class ReverseFeePayment
{
    public function handle(FeePayment $payment, OrganisationUser $actor, string $reason): FeePayment
    {
        return DB::transaction(function () use ($payment, $actor, $reason): FeePayment {
            /** @var FeePayment $locked */
            $locked = FeePayment::query()
                ->whereKey($payment->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->confirmation_status !== ConfirmationStatus::Confirmed) {
                throw LifecycleViolation::payment($locked->confirmation_status->value, 'reversed');
            }

            $before = ['confirmation_status' => $locked->confirmation_status->value];

            $locked->forceFill([
                'confirmation_status' => ConfirmationStatus::Reversed,
                'reversal_reason' => $reason,
            ])->save();

            $subscription = $locked->subscription;

            if ($subscription) {
                // Never let a withdrawal drive the paid total below zero, even
                // if the subscription was edited between the two events.
                $subscription->update([
                    'amount_paid_minor' => max(0, $subscription->amount_paid_minor - $locked->amount_minor),
                ]);
            }

            AuditEvent::record(
                $locked,
                'fee_payment.reversed',
                $actor,
                $before,
                ['confirmation_status' => ConfirmationStatus::Reversed->value],
                [
                    'reason' => $reason,
                    'amount_minor' => $locked->amount_minor,
                    'subscription_id' => $locked->subscription_id,
                ],
            );

            return $locked;
        });
    }
}
