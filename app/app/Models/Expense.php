<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ExpenseStatus;
use App\Enums\ExpenseTargetType;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\ExpenseFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'organisation_id', 'club_id', 'category', 'amount_minor', 'currency_code',
    'expense_date', 'paid_from_financial_account_id', 'payee', 'description',
    'receipt_path', 'created_by', 'target_type', 'target_id',
])]
class Expense extends Model
{
    /** @use HasFactory<ExpenseFactory> */
    use BelongsToOrganisation, HasFactory;

    /**
     * A fixed starter set so category filtering and reporting group reliably
     * instead of fragmenting across free-typed spellings. Operators can still
     * enter their own category, which appears in the list once used.
     *
     * @return array<int, string>
     */
    public static function categories(): array
    {
        $defaults = [
            'Rent', 'Utilities', 'Salaries', 'Equipment', 'Maintenance',
            'Marketing', 'Supplies', 'Insurance', 'Software', 'Other',
        ];

        $used = self::query()->distinct()->orderBy('category')->pluck('category')->all();

        return collect($defaults)->merge($used)->unique()->sort()->values()->all();
    }

    protected function casts(): array
    {
        return [
            'status' => ExpenseStatus::class,
            'target_type' => ExpenseTargetType::class,
            'amount_minor' => 'integer',
            'expense_date' => 'date',
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
     * @return BelongsTo<FinancialAccount, $this>
     */
    public function paidFromFinancialAccount(): BelongsTo
    {
        return $this->belongsTo(FinancialAccount::class, 'paid_from_financial_account_id');
    }

    /**
     * Shorter alias used by list views and eager loads.
     *
     * @return BelongsTo<FinancialAccount, $this>
     */
    public function fundingAccount(): BelongsTo
    {
        return $this->paidFromFinancialAccount();
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'created_by');
    }
}
