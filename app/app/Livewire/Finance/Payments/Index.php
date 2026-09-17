<?php

declare(strict_types=1);

namespace App\Livewire\Finance\Payments;

use App\Enums\ConfirmationStatus;
use App\Enums\PaymentMethod;
use App\Livewire\Concerns\LazyPage;
use App\Livewire\Concerns\LoadsMore;
use App\Livewire\Concerns\RemembersFilters;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\OrganisationUser;
use App\Support\Listing\Slice;
use App\Support\Search;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Defer;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * The organisation-wide payment ledger (MEP.md 6.8).
 *
 * Only confirmed payments count towards revenue; pending, rejected, and
 * reversed totals are reported separately and never folded into it
 * (MEP.md 8.4).
 */
#[Defer]
class Index extends Component
{
    use LazyPage, LoadsMore, RemembersFilters, ResolvesMembership;

    protected function pageSize(): int
    {
        return 20;
    }

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
            // Member name or phone, the payment reference (PMT-17), or a
            // transaction reference.
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $inner): void {
                $digits = Search::phoneDigits($this->search, $this->organisation(), 'payment');
                $id = Search::referenceId($this->organisation(), 'payment', $this->search);

                $inner->where('payer_name', 'ilike', "%{$this->search}%")
                    ->when($digits !== null, fn (Builder $q) => $q->orWhere('payer_phone', 'ilike', '%'.$digits.'%'))
                    ->orWhere('transaction_reference', 'ilike', "%{$this->search}%")
                    ->orWhereHas('member', fn (Builder $member) => $member
                        ->where('name', 'ilike', "%{$this->search}%")
                        ->when($digits !== null, fn (Builder $q) => $q->orWhere('phone', 'ilike', '%'.$digits.'%')));

                if ($id !== null) {
                    $inner->orWhere('fee_payments.id', $id);
                }
            }));
    }

    /**
     * @return Slice<FeePayment>
     */
    protected function payments(): Slice
    {
        return $this->slice($this->baseQuery()
            ->with(['member:id,name,phone', 'club:id,name', 'collectedBy.user:id,name', 'financialAccount:id,name', 'invoice:id,number'])
            ->orderByDesc('payment_date')
            ->orderByDesc('id')
        );
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
