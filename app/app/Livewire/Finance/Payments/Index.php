<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Payments;

use App\Enums\ConfirmationStatus;
use App\Enums\PaymentMethod;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\OrganisationUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * The organisation-wide payment ledger (MEP.md 6.8).
 *
 * Only confirmed payments count towards revenue; pending, rejected, and
 * reversed totals are reported separately and never folded into it
 * (MEP.md 8.4).
 */
class Index extends Component
{
    use ResolvesMembership, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    #[Url]
    public string $club = '';

    #[Url]
    public string $method = '';

    #[Url]
    public string $collector = '';

    #[Url]
    public string $account = '';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public function mount(): void
    {
        $this->authorize('viewAny', FeePayment::class);

        if ($this->from === '') {
            $this->from = Carbon::today($this->organisation()->timezone)->startOfMonth()->toDateString();
        }

        if ($this->to === '') {
            $this->to = Carbon::today($this->organisation()->timezone)->toDateString();
        }
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    public function clearFilters(): void
    {
        $this->reset(['search', 'status', 'club', 'method', 'collector', 'account']);
        $this->resetPage();
    }

    /**
     * @return Builder<FeePayment>
     */
    protected function baseQuery(): Builder
    {
        $membership = $this->currentMembership();

        return FeePayment::query()
            // A staff user only ever sees their own collections (MEP.md 4.2).
            ->when(! $membership->isAdmin(), fn (Builder $query) => $query->where('collected_by', $membership->id))
            ->when($this->from !== '', fn (Builder $query) => $query->whereDate('payment_date', '>=', $this->from))
            ->when($this->to !== '', fn (Builder $query) => $query->whereDate('payment_date', '<=', $this->to))
            ->when($this->status !== '', fn (Builder $query) => $query->where('confirmation_status', $this->status))
            ->when($this->club !== '', fn (Builder $query) => $query->where('club_id', $this->club))
            ->when($this->method !== '', fn (Builder $query) => $query->where('payment_method', $this->method))
            ->when($this->collector !== '', fn (Builder $query) => $query->where('collected_by', $this->collector))
            ->when($this->account !== '', fn (Builder $query) => $query->where('financial_account_id', $this->account))
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $inner): void {
                $inner->where('payer_name', 'ilike', "%{$this->search}%")
                    ->orWhere('transaction_reference', 'ilike', "%{$this->search}%")
                    ->orWhereHas('member', fn (Builder $member) => $member
                        ->where('name', 'ilike', "%{$this->search}%")
                        ->orWhere('phone', 'ilike', "%{$this->search}%"));
            }));
    }

    /**
     * @return LengthAwarePaginator<int, FeePayment>
     */
    protected function payments(): LengthAwarePaginator
    {
        return $this->baseQuery()
            ->with(['member:id,name,phone', 'club:id,name', 'collectedBy.user:id,name', 'financialAccount:id,name', 'invoice:id,number'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
            ->paginate(20);
    }

    /**
     * @return array<string, int>
     */
    protected function totals(): array
    {
        $rows = $this->baseQuery()
            ->selectRaw('confirmation_status, COALESCE(SUM(amount_minor), 0) AS total, COUNT(*) AS entries')
            ->groupBy('confirmation_status')
            ->pluck('total', 'confirmation_status');

        return [
            'confirmed' => (int) ($rows[ConfirmationStatus::Confirmed->value] ?? 0),
            'pending' => (int) ($rows[ConfirmationStatus::PendingAdminConfirmation->value] ?? 0),
            'rejected' => (int) ($rows[ConfirmationStatus::Rejected->value] ?? 0),
            'reversed' => (int) ($rows[ConfirmationStatus::Reversed->value] ?? 0),
        ];
    }

    public function render(): View
    {
        $membership = $this->currentMembership();

        return view('livewire.finance.payments.index', [
            'organisation' => $this->organisation(),
            'payments' => $this->payments(),
            'totals' => $this->totals(),
            'clubs' => $this->accessibleClubs(true),
            'statuses' => ConfirmationStatus::cases(),
            'methods' => PaymentMethod::cases(),
            'collectors' => $membership->isAdmin()
                ? OrganisationUser::query()->with('user:id,name')->get()
                : collect(),
            'accounts' => $membership->isAdmin() ? FinancialAccount::query()->orderBy('name')->get() : collect(),
            'isAdmin' => $membership->isAdmin(),
        ])->layout('components.layouts.app', ['heading' => 'Payments']);
    }
}
