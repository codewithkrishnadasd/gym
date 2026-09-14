<?php

declare(strict_types=1);

namespace App\Actions\Payments;

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\ConfirmationStatus;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\PaymentPurpose;
use App\Enums\SubscriptionStatus;
use App\Exceptions\LifecycleViolation;
use App\Models\AuditEvent;
use App\Models\Club;
use App\Models\FeePayment;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Support\Money;
use Illuminate\Support\Facades\DB;

/**
 * Confirms a pending fee payment (MEP.md 5.11.1).
 *
 * Everything the confirmation touches — the payment lifecycle, the
 * subscription balance, the audit event, and the notification snapshot —
 * changes inside one transaction, so a failure part-way through cannot leave
 * a payment marked confirmed with an unapplied balance.
 *
 * Double-apply is prevented by re-reading the payment `FOR UPDATE` inside the
 * transaction: a second concurrent confirmation blocks, then finds the row
 * already confirmed and stops. Retrying a request that already succeeded is
 * therefore a no-op rather than a second credit (MEP.md 13.13).
 */
final class ConfirmFeePayment
{
    public function __construct(private readonly CreateActionNotification $notifications) {}

    public function handle(FeePayment $payment, OrganisationUser $actor): ConfirmationResult
    {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        return DB::transaction(function () use ($payment, $actor, $organisation): ConfirmationResult {
            /** @var FeePayment $locked */
            $locked = FeePayment::query()
                ->whereKey($payment->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($locked->confirmation_status !== ConfirmationStatus::PendingAdminConfirmation) {
                throw LifecycleViolation::payment($locked->confirmation_status->value, 'confirmed');
            }

            $before = ['confirmation_status' => $locked->confirmation_status->value];

            $locked->forceFill([
                'confirmation_status' => ConfirmationStatus::Confirmed,
                'confirmed_by' => $actor->id,
                'confirmed_at' => now(),
            ])->save();

            $subscription = $locked->subscription;

            // An invoice balance is maintained here, in the same locked
            // transaction as the payment's own status change, for the same
            // reason the subscription balance is: a payment marked confirmed
            // with its credit unapplied is the one state that must not exist.
            $invoice = $locked->invoice_id === null
                ? null
                : Invoice::query()->whereKey($locked->invoice_id)->lockForUpdate()->first();

            $invoice?->applyPayment($locked->amount_minor, $locked->discount_minor);

            // An admission fee is owed by the member rather than by a term or
            // a document, so its balance lives on the member row.
            if ($locked->purpose === PaymentPurpose::Admission) {
                /** @var Member $payer */
                $payer = Member::query()->whereKey($locked->member_id)->lockForUpdate()->firstOrFail();
                $payer->applyAdmissionPayment($locked->amount_minor, $locked->discount_minor);
            }

            if ($subscription) {
                $subscription->applyPayment($locked->amount_minor, $locked->discount_minor);
                $subscription->refresh();

                // A subscription that is now fully paid and still within its
                // dates is active again.
                if ($subscription->amount_paid_minor >= $subscription->amount_due_minor
                    && $subscription->status === SubscriptionStatus::Expired
                    && $subscription->end_date->isFuture()) {
                    $subscription->update(['status' => SubscriptionStatus::Active]);
                }
            }

            AuditEvent::record(
                $locked,
                'fee_payment.confirmed',
                $actor,
                $before,
                ['confirmation_status' => ConfirmationStatus::Confirmed->value],
                [
                    'amount_minor' => $locked->amount_minor,
                    'discount_minor' => $locked->discount_minor,
                    'purpose' => $locked->purpose->value,
                    'currency_code' => $locked->currency_code,
                    'subscription_id' => $locked->subscription_id,
                    'invoice_id' => $locked->invoice_id,
                ],
            );

            // Both FKs are NOT NULL with constraints, but a belongsTo always
            // types as nullable — asserted locally rather than guarded.
            /** @var Member $member */
            $member = $locked->member;
            /** @var Club $club */
            $club = $locked->club;

            $notification = $this->notifications->handle(
                organisation: $organisation,
                type: NotificationActionType::FeePaymentConfirmed,
                recipientType: NotificationRecipientType::Member,
                recipientId: $member->id,
                recipientName: $member->name,
                recipientPhone: $member->phone,
                entityType: NotificationEntityType::FeePayment,
                entityId: $locked->id,
                actor: $actor,
                operationId: 'fee_payment.confirm.'.$locked->id,
                context: [
                    'amount' => Money::ofMinor($locked->amount_minor, $locked->currency_code)->format($organisation->locale),
                    'planName' => $subscription?->plan?->name,
                    'purpose' => $locked->purposeLabel(),
                    'discount' => $locked->discount_minor > 0
                        ? Money::ofMinor($locked->discount_minor, $locked->currency_code)->format($organisation->locale)
                        : null,
                    'clubName' => $club->name,
                    'paymentDate' => $locked->payment_date->format('d M Y'),
                    'endDate' => $subscription?->end_date->format('d M Y'),
                    'reference' => $locked->transaction_reference ?: $organisation->reference('payment', $locked->id),
                    'invoiceNumber' => $invoice?->number,
                    'balanceDue' => $invoice === null
                        ? null
                        : Money::ofMinor($invoice->outstandingMinor(), $invoice->currency_code)->format($organisation->locale),
                ],
            );

            if ($notification) {
                $locked->forceFill([
                    'whatsapp_status' => $notification->status->value === 'unavailable' ? 'failed' : 'ready',
                    'whatsapp_message_template_version' => $notification->message_template_version,
                    'whatsapp_message_snapshot' => $notification->message_snapshot,
                ])->save();
            }

            return new ConfirmationResult($locked, $notification);
        });
    }
}
