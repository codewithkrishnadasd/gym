<?php

declare(strict_types=1);

namespace App\Actions\Billing;

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\InvoiceStatus;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Models\AuditEvent;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\WhatsappActionNotification;
use App\Support\Money;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Raises an invoice against a member (MEP.md 6.8).
 *
 * The invoice, its lines, its number and its audit event are written in one
 * transaction, and the lines are priced from the values passed in — not looked
 * up again from the catalogue — so what the operator saw on the form is exactly
 * what the member is billed. A catalogue item repriced a minute later changes
 * nothing already issued.
 *
 * @phpstan-type LineInput array{billable_item_id: int|null, description: string, quantity: int, unit_price_minor: int}
 */
final class IssueInvoice
{
    public function __construct(private readonly CreateActionNotification $notifications) {}

    /**
     * @param  array<int, LineInput>  $lines
     */
    public function handle(
        Member $member,
        array $lines,
        OrganisationUser $actor,
        ?Carbon $dueDate = null,
        ?string $notes = null,
    ): IssuedInvoice {
        /** @var Organisation $organisation */
        $organisation = app('tenant');

        if ($lines === []) {
            throw new InvalidArgumentException('An invoice needs at least one line.');
        }

        if ($member->primary_club_id === null) {
            throw new InvalidArgumentException('The member has no club to bill against.');
        }

        $issueDate = Carbon::today($organisation->timezone);

        $total = 0;
        $rows = [];

        foreach (array_values($lines) as $position => $line) {
            $quantity = max(1, $line['quantity']);
            $unit = max(0, $line['unit_price_minor']);
            $lineTotal = $quantity * $unit;
            $total += $lineTotal;

            $rows[] = [
                'billable_item_id' => $line['billable_item_id'],
                'description' => trim($line['description']),
                'quantity' => $quantity,
                'unit_price_minor' => $unit,
                'line_total_minor' => $lineTotal,
                'position' => $position,
            ];
        }

        // Checked before the transaction, so a bad invoice never takes the
        // numbering lock or burns a sequence number.
        if ($total <= 0) {
            throw new InvalidArgumentException('An invoice has to come to more than nothing.');
        }

        $invoice = DB::transaction(function () use ($organisation, $member, $rows, $total, $actor, $issueDate, $dueDate, $notes): Invoice {
            $numbering = InvoiceNumber::next($organisation, $issueDate);

            $invoice = Invoice::create([
                'member_id' => $member->id,
                'club_id' => $member->primary_club_id,
                'sequence' => $numbering['sequence'],
                'number' => $numbering['number'],
                'status' => InvoiceStatus::Issued,
                'issue_date' => $issueDate->toDateString(),
                'due_date' => $dueDate?->toDateString(),
                'currency_code' => $organisation->currency_code,
                'total_minor' => $total,
                'paid_minor' => 0,
                'notes' => $notes ?: null,
                'created_by' => $actor->id,
            ]);

            $invoice->lines()->createMany($rows);

            AuditEvent::record($invoice, 'invoice.issued', $actor, null, [
                'number' => $invoice->number,
                'total_minor' => $total,
                'lines' => count($rows),
            ], ['member_id' => $member->id, 'club_id' => $member->primary_club_id]);

            return $invoice;
        });

        $invoice->load('lines');

        $notification = $this->notify($organisation, $invoice, $member, $actor);

        return new IssuedInvoice($invoice, $notification);
    }

    private function notify(Organisation $organisation, Invoice $invoice, Member $member, OrganisationUser $actor): ?WhatsappActionNotification
    {
        $items = $invoice->lines
            ->map(fn ($line): string => $line->quantity > 1
                ? $line->quantity.' × '.$line->description
                : $line->description)
            ->implode(', ');

        return $this->notifications->handle(
            organisation: $organisation,
            type: NotificationActionType::InvoiceIssued,
            recipientType: NotificationRecipientType::Member,
            recipientId: $member->id,
            recipientName: $member->name,
            recipientPhone: $member->phone,
            entityType: NotificationEntityType::Invoice,
            entityId: $invoice->id,
            actor: $actor,
            operationId: 'invoice.issued.'.$invoice->id,
            context: [
                'invoiceNumber' => $invoice->number,
                'amount' => Money::ofMinor($invoice->total_minor, $invoice->currency_code)->format($organisation->locale),
                'items' => $items,
                'dueDate' => $invoice->due_date?->format('d M Y') ?? 'on receipt',
                'clubName' => $invoice->club?->name,
                'invoiceLink' => $invoice->publicUrl(),
            ],
        );
    }
}
