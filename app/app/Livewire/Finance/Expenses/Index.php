<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Expenses;

use App\Actions\Expenses\ReverseExpense;
use App\Enums\ExpenseStatus;
use App\Exceptions\LifecycleViolation;
use App\Livewire\Concerns\LoadsMore;
use App\Livewire\Concerns\RemembersFilters;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Expense;
use App\Models\FinancialAccount;
use App\Support\Listing\Slice;
use App\Support\Search;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use LoadsMore, RemembersFilters, ResolvesMembership;

    protected function pageSize(): int
    {
        return 20;
    }

    #[Url]
    public string $search = '';

    #[Url]
    public string $club = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $account = '';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public ?int $reversingId = null;

    public string $reversalReason = '';

    public ?string $lifecycleError = null;

    public function mount(): void
    {
        $this->authorize('viewAny', Expense::class);

        if ($this->from === '') {
            $this->from = Carbon::today($this->organisation()->timezone)->startOfMonth()->toDateString();
        }

        if ($this->to === '') {
            $this->to = Carbon::today($this->organisation()->timezone)->toDateString();
        }
    }

    public function updated(string $property): void
    {
        if (! in_array($property, ['page', 'reversalReason', 'reversingId'], true)) {
            $this->resetPage();
        }
    }

    public function startReverse(int $expenseId): void
    {
        $this->reversingId = $expenseId;
        $this->reversalReason = '';
        $this->resetErrorBag();

        $this->dispatch('open-modal', 'reverse-expense');
    }

    public function reverse(): void
    {
        /** @var Expense $expense */
        $expense = Expense::query()->findOrFail($this->reversingId);

        $this->authorize('reverse', $expense);

        $this->validate(
            ['reversalReason' => ['required', 'string', 'min:3', 'max:255']],
            ['reversalReason.required' => 'A reason is required to reverse a completed expense.'],
        );

        try {
            app(ReverseExpense::class)->handle($expense, $this->currentMembership(), $this->reversalReason);
        } catch (LifecycleViolation $exception) {
            $this->lifecycleError = $exception->getMessage();

            return;
        }

        $this->reset(['reversingId', 'reversalReason']);
        $this->dispatch('close-modal');

        session()->flash('status', 'Expense reversed. The original record is preserved.');
    }

    /**
     * @return Builder<Expense>
     */
    protected function baseQuery(): Builder
    {
        return Expense::query()
            ->when($this->from !== '', fn (Builder $query) => $query->whereDate('expense_date', '>=', $this->from))
            ->when($this->to !== '', fn (Builder $query) => $query->whereDate('expense_date', '<=', $this->to))
            ->when($this->club !== '', fn (Builder $query) => $query->where('club_id', $this->club))
            ->when($this->category !== '', fn (Builder $query) => $query->where('category', $this->category))
            ->when($this->status !== '', fn (Builder $query) => $query->where('status', $this->status))
            ->when($this->account !== '', fn (Builder $query) => $query->where('paid_from_financial_account_id', $this->account))
            ->when($this->search !== '', fn (Builder $query) => Search::apply($query, $this->organisation(), 'expense', $this->search, ['description', 'payee', 'category'], null));
    }

    /**
     * @return Slice<Expense>
     */
    protected function expenses(): Slice
    {
        return $this->slice($this->baseQuery()
            ->with(['club:id,name', 'fundingAccount:id,name', 'createdBy.user:id,name'])
            ->orderByDesc('expense_date')
            ->orderByDesc('id')
        );
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        $completed = (int) $this->baseQuery()->clone()->where('status', ExpenseStatus::Completed)->sum('amount_minor');

        return view('livewire.finance.expenses.index', [
            'organisation' => $organisation,
            'expenses' => $this->expenses(),
            'clubs' => $this->accessibleClubs(true),
            'accounts' => FinancialAccount::query()->orderBy('name')->get(),
            'categories' => Expense::categoriesForFilter($organisation),
            'statuses' => ExpenseStatus::cases(),
            'totalCompleted' => $completed,
            'reversedTotal' => (int) $this->baseQuery()->clone()->where('status', ExpenseStatus::Reversed)->sum('amount_minor'),
        ])->layout('components.layouts.app', ['heading' => 'Expenses']);
    }
}
