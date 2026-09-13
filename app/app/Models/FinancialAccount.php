<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\FinancialAccountStatus;
use App\Enums\FinancialAccountType;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\FinancialAccountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'organisation_id', 'name', 'account_type', 'bank_name', 'account_number_last4',
    'upi_id', 'qr_payload', 'qr_image_path', 'status',
])]
class FinancialAccount extends Model
{
    /** @use HasFactory<FinancialAccountFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'account_type' => FinancialAccountType::class,
            'status' => FinancialAccountStatus::class,
        ];
    }

    /**
     * @return HasMany<FeePayment, $this>
     */
    public function feePayments(): HasMany
    {
        return $this->hasMany(FeePayment::class);
    }

    /**
     * @return HasMany<Expense, $this>
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class, 'paid_from_financial_account_id');
    }
}
