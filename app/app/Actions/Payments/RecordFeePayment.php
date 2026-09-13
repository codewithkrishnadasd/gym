<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Enums\ConfirmationStatus;
use App\Models\AuditEvent;
use App\Models\FeePayment;
use App\Models\OrganisationUser;
use Illuminate\Support\Facades\DB;

/**
 * Records a fee collection. Every payment starts its life as
 * `pending_admin_confirmation` regardless of who entered it, so there is
 * exactly one lifecycle and one confirmation code path in the system
 * (MEP.md 5.11).
 *
 * An admin recording a payment at the counter may confirm it in the same
 * request, but that still runs through ConfirmFeePayment — it is not a
 * shortcut that writes `confirmed` directly.
 *
 * @phpstan-type PaymentAttributes array{club_id: int, member_id: int, subscription_id: int|null, payer_name: string, amount_minor: int, currency_code: string, payment_method: string, financial_account_id: int|null, transaction_reference: string|null, payment_date: string, notes: string|null}
 */
final class RecordFeePayment
{
    public function __construct(private readonly ConfirmFeePayment $confirm) {}

    /**
     * @param  PaymentAttributes  $attributes
     */
    public function handle(array $attributes, OrganisationUser $actor, bool $confirmImmediately = false): ConfirmationResult
    {
        $payment = DB::transaction(function () use ($attributes, $actor): FeePayment {
            $payment = FeePayment::create([
                ...$attributes,
                'collected_by' => $actor->id,
            ]);

            // `confirmation_status` and `whatsapp_status` are database
            // defaults deliberately kept out of `$fillable` (MEP.md 8.3), so
            // the in-memory model has them as null until it is refreshed.
            $payment->refresh();

            AuditEvent::record(
                $payment,
                'fee_payment.submitted',
                $actor,
                null,
                [
                    'confirmation_status' => ConfirmationStatus::PendingAdminConfirmation->value,
                    'amount_minor' => $payment->amount_minor,
                ],
                ['member_id' => $payment->member_id, 'club_id' => $payment->club_id],
            );

            return $payment;
        });

        // Only an admin may confirm, and the policy is the gate for that —
        // this flag is a convenience for the counter workflow, not authority.
        if ($confirmImmediately && $actor->isAdmin()) {
            return $this->confirm->handle($payment, $actor);
        }

        return new ConfirmationResult($payment, null);
    }
}
