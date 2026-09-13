<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ConfirmationStatus;
use App\Enums\PaymentMethod;
use App\Enums\WhatsappStatus;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\FeePaymentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * `confirmation_status` is the single canonical payment lifecycle field.
 * Only `pending_admin_confirmation -> confirmed|rejected` and
 * `confirmed -> reversed` are legal transitions — enforced by the
 * ConfirmFeePaymentAction/RejectFeePaymentAction/ReverseFeePaymentAction
 * classes, never by a direct model update. See MEP.md 5.11.
 */
#[Fillable([
    'organisation_id', 'club_id', 'member_id', 'subscription_id', 'payer_name',
    'amount_minor', 'currency_code', 'payment_method', 'financial_account_id',
    'transaction_reference', 'payment_date', 'collected_by', 'notes',
])]
class FeePayment extends Model
{
    /** @use HasFactory<FeePaymentFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'payment_method' => PaymentMethod::class,
            'confirmation_status' => ConfirmationStatus::class,
            'whatsapp_status' => WhatsappStatus::class,
            'amount_minor' => 'integer',
            'payment_date' => 'date',
            'confirmed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * @return BelongsTo<MemberSubscription, $this>
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(MemberSubscription::class, 'subscription_id');
    }

    /**
     * @return BelongsTo<FinancialAccount, $this>
     */
    public function financialAccount(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class);
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function collectedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'collected_by');
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'confirmed_by');
    }

    public function isConfirmed(): bool
    {
        return $this->confirmation_status === ConfirmationStatus::Confirmed;
    }
}
