<?php

declare(strict_types=1);

namespace App\Actions\Billing;

use App\Models\Invoice;
use App\Models\Organisation;
use Illuminate\Support\Carbon;

/**
 * Allocates the next invoice number for an organisation.
 *
 * Must be called inside the transaction that inserts the invoice. The lock is
 * taken on the *organisation row*, not on the invoices being counted: Postgres
 * refuses `FOR UPDATE` on an aggregate, and locking the parent row is the
 * cleaner serialisation point anyway — every issuer for one gym queues on one
 * row, and issuers for different gyms never block each other. The lock holds
 * until the transaction commits, so two invoices issued at the same moment
 * cannot both read the same maximum and collide on the unique index.
 *
 * The number embeds the issue year purely for the reader's benefit; uniqueness
 * rests on the integer sequence.
 */
final class InvoiceNumber
{
    /**
     * @return array{sequence: int, number: string}
     */
    public static function next(Organisation $organisation, Carbon $issueDate): array
    {
        Organisation::query()->whereKey($organisation->id)->lockForUpdate()->firstOrFail();

        /** @var int|null $latest */
        $latest = Invoice::query()
            ->withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->max('sequence');

        $sequence = ((int) $latest) + 1;

        return [
            'sequence' => $sequence,
            'number' => sprintf('%s-%s-%04d', $organisation->idPrefix('invoice'), $issueDate->format('Y'), $sequence),
        ];
    }
}
