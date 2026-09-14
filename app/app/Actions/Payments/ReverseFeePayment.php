<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Enums\ConfirmationStatus;
use App\Enums\PaymentPurpose;
use App\Exceptions\LifecycleViolation;
use App\Models\AuditEvent;
use App\Models\FeePayment;
use App\Models\Invoice;
use App\Models\Member;
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

            if ($locked->invoice_id !== null) {
                Invoice::query()->whereKey($locked->invoice_id)->lockForUpdate()->first()
                    ?->withdrawPayment($locked->settledMinor(), $locked->discount_minor);
            }

            if ($locked->purpose === PaymentPurpose::Admission) {
                Member::query()->whereKey($locked->member_id)->lockForUpdate()->first()
                    ?->withdrawAdmissionPayment($locked->settledMinor(), $locked->discount_minor);
            }

            if ($subscription) {
                $subscription->withdrawPayment($locked->settledMinor(), $locked->discount_minor);
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
                    'discount_minor' => $locked->discount_minor,
                    'credit_applied_minor' => $locked->credit_applied_minor,
                    'subscription_id' => $locked->subscription_id,
                    'invoice_id' => $locked->invoice_id,
                ],
            );

            return $locked;
        });
    }
}
