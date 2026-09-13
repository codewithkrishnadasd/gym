<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Accounts;

use App\Enums\ConfirmationStatus;
use App\Enums\ExpenseStatus;
use App\Enums\FinancialAccountStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Expense;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use ResolvesMembership;

    #[Url]
    public string $status = '';

    public function mount(): void
    {
        $this->authorize('viewAny', FinancialAccount::class);
    }

    public function archive(FinancialAccount $account): void
    {
        $this->authorize('archive', FinancialAccount::class);

        $account->update(['status' => FinancialAccountStatus::Archived]);

        session()->flash('status', "\"{$account->name}\" was archived. Historical transactions are unchanged.");
    }

    public function restore(FinancialAccount $account): void
    {
        $this->authorize('archive', FinancialAccount::class);

        $account->update(['status' => FinancialAccountStatus::Active]);
    }

    /**
     * Money in and out per account. Only confirmed payments count as received
     * and only completed expenses count as spent (MEP.md 8.4).
     *
     * @return array<int, array{in: int, out: int}>
     */
    protected function balances(): array
    {
        $received = FeePayment::query()
            ->where('confirmation_status', ConfirmationStatus::Confirmed)
            ->whereNotNull('financial_account_id')
            ->selectRaw('financial_account_id, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('financial_account_id')
            ->pluck('total', 'financial_account_id');

        $spent = Expense::query()
            ->where('status', ExpenseStatus::Completed)
            ->whereNotNull('paid_from_financial_account_id')
            ->selectRaw('paid_from_financial_account_id, COALESCE(SUM(amount_minor), 0) AS total')
            ->groupBy('paid_from_financial_account_id')
            ->pluck('total', 'paid_from_financial_account_id');

        $balances = [];

        foreach ($this->accounts() as $account) {
            $balances[$account->id] = [
                'in' => (int) ($received[$account->id] ?? 0),
                'out' => (int) ($spent[$account->id] ?? 0),
            ];
        }

        return $balances;
    }

    /**
     * @return Collection<int, FinancialAccount>
     */
    protected function accounts(): Collection
    {
        return FinancialAccount::query()
            ->when($this->status !== '', fn ($query) => $query->where('status', $this->status))
            ->orderBy('status')
            ->orderBy('name')
            ->get();
    }

    public function render(): View
    {
        return view('livewire.finance.accounts.index', [
            'organisation' => $this->organisation(),
            'accounts' => $this->accounts(),
            'balances' => $this->balances(),
            'statuses' => FinancialAccountStatus::cases(),
        ])->layout('components.layouts.app', ['heading' => 'Accounts']);
    }
}
