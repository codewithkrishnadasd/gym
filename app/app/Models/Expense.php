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
     * The categories a *new* expense may be filed under: the organisation's
     * active categories only. A deactivated one is deliberately absent, which
     * is the whole point of deactivating rather than deleting it.
     *
     * @return array<int, string>
     */
    public static function categoriesForEntry(Organisation $organisation): array
    {
        return collect($organisation->expenseCategories())->sort()->values()->all();
    }

    /**
     * The categories to offer when filtering and reporting: everything
     * configured, active or not, plus anything already recorded.
     *
     * Deactivated and free-typed categories have to appear here or their
     * expenses become unreachable — the money was still spent, and a report
     * that silently omits it is worse than one with an extra row.
     *
     * @return array<int, string>
     */
    public static function categoriesForFilter(Organisation $organisation): array
    {
        $used = self::query()->distinct()->orderBy('category')->pluck('category')->all();

        return collect($organisation->allExpenseCategories())
            ->merge($used)
            ->unique()
            ->sort()
            ->values()
            ->all();
    }

    /**
     * How many expenses reference each category, so settings can refuse to
     * delete one that is in use.
     *
     * @return array<string, int>
     */
    public static function categoryUsage(): array
    {
        /** @var array<string, int> $counts */
        $counts = self::query()
            ->selectRaw('category, COUNT(*) AS total')
            ->groupBy('category')
            ->pluck('total', 'category')
            ->all();

        return $counts;
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
