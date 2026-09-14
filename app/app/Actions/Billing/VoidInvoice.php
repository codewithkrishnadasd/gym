<?php

declare(strict_types=1);

namespace App\Actions\Billing;

use App\Enums\InvoiceStatus;
use App\Exceptions\LifecycleViolation;
use App\Models\AuditEvent;
use App\Models\Invoice;
use App\Models\OrganisationUser;
use Illuminate\Support\Facades\DB;

/**
 * Cancels an invoice that should never have been raised, or that a member will
 * not be paying.
 *
 * Refused while money is attached to it: a confirmed payment has to be
 * reversed first, and a pending one has to be confirmed or rejected, so that
 * nothing is ever left credited against a document that no longer stands. The
 * invoice row itself is kept — numbers are never reused, and a gap in the
 * sequence with no explanation is exactly what an auditor asks about.
 */
final class VoidInvoice
{
    public function handle(Invoice $invoice, OrganisationUser $actor, string $reason): Invoice
    {
        return DB::transaction(function () use ($invoice, $actor, $reason): Invoice {
            /** @var Invoice $locked */
            $locked = Invoice::query()->whereKey($invoice->getKey())->lockForUpdate()->firstOrFail();

            if ($locked->status === InvoiceStatus::Void) {
                throw LifecycleViolation::invoice('This invoice is already void.');
            }

            if ($locked->paid_minor > 0) {
                throw LifecycleViolation::invoice('Payments have been confirmed against this invoice. Reverse them before voiding it.');
            }

            if ($locked->hasPendingPayments()) {
                throw LifecycleViolation::invoice('A payment against this invoice is still awaiting confirmation. Confirm or reject it first.');
            }

            $before = ['status' => $locked->status->value];

            $locked->forceFill([
                'status' => InvoiceStatus::Void,
                'voided_by' => $actor->id,
                'voided_at' => now(),
                'void_reason' => $reason,
            ])->save();

            AuditEvent::record($locked, 'invoice.voided', $actor, $before, [
                'status' => InvoiceStatus::Void->value,
            ], ['reason' => $reason, 'number' => $locked->number]);

            return $locked;
        });
    }
}
