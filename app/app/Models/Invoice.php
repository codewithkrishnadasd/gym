<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ConfirmationStatus;
use App\Enums\InvoiceStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\InvoiceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A bill raised against a member for one or more priced lines (MEP.md 6.8).
 *
 * `paid_minor` is the sum of *confirmed* payments carrying this invoice's id,
 * maintained by ConfirmFeePayment and ReverseFeePayment inside their locked
 * transactions — the same place the subscription balance is maintained. A
 * pending payment therefore counts for nothing here until an admin confirms
 * it, which is what keeps "partly paid" honest.
 */
#[Fillable([
    'organisation_id', 'member_id', 'club_id', 'sequence', 'number', 'status',
    'issue_date', 'due_date', 'currency_code', 'total_minor', 'paid_minor', 'notes', 'created_by',
])]
class Invoice extends Model
{
    /** @use HasFactory<InvoiceFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'status' => InvoiceStatus::class,
            'issue_date' => 'date',
            'due_date' => 'date',
            'total_minor' => 'integer',
            'paid_minor' => 'integer',
            'sequence' => 'integer',
            'voided_at' => 'datetime',
        ];
    }

    public function outstandingMinor(): int
    {
        return max(0, $this->total_minor - $this->paid_minor);
    }

    public function isOpen(): bool
    {
        return $this->status->isOpen();
    }

    public function isOverdue(): bool
    {
        return $this->isOpen() && $this->due_date !== null && $this->due_date->isPast();
    }

    /**
     * Credits a confirmed payment and re-derives the status. Called inside the
     * confirming transaction, on a row already locked by it.
     */
    public function applyPayment(int $amountMinor): void
    {
        $this->forceFill([
            'paid_minor' => $this->paid_minor + $amountMinor,
        ])->save();

        $this->refreshStatus();
    }

    /**
     * Withdraws a reversed payment. Floors at zero rather than trusting the
     * arithmetic: the invoice could have been voided and reissued between the
     * two events.
     */
    public function withdrawPayment(int $amountMinor): void
    {
        $this->forceFill([
            'paid_minor' => max(0, $this->paid_minor - $amountMinor),
        ])->save();

        $this->refreshStatus();
    }

    /**
     * A void invoice stays void no matter what its balance says.
     */
    public function refreshStatus(): void
    {
        if ($this->status === InvoiceStatus::Void) {
            return;
        }

        $this->forceFill([
            'status' => InvoiceStatus::forBalance($this->paid_minor, $this->total_minor),
        ])->save();
    }

    /**
     * Whether anything is still moving through the payment lifecycle for this
     * invoice — a void is refused while there is, so nothing ends up confirmed
     * against a document that no longer stands.
     */
    public function hasPendingPayments(): bool
    {
        return $this->payments()
            ->where('confirmation_status', ConfirmationStatus::PendingAdminConfirmation)
            ->exists();
    }

    /**
     * @return HasMany<InvoiceLine, $this>
     */
    public function lines(): HasMany
    {
        return $this->hasMany(InvoiceLine::class)->orderBy('position');
    }

    /**
     * @return HasMany<FeePayment, $this>
     */
    public function payments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
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
    public function voidedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'voided_by');
    }
}
