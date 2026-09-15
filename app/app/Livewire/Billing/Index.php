<?php

declare(strict_types=1);

namespace App\Livewire\Billing;

use App\Enums\InvoiceStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Invoice;
use App\Support\Search;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * Every invoice the viewer is allowed to see, outstanding first.
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

    public function mount(): void
    {
        $this->authorize('viewAny', Invoice::class);
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    /**
     * @return Builder<Invoice>
     */
    protected function scope(): Builder
    {
        $membership = $this->currentMembership();

        return Invoice::query()
            ->when(! $membership->isAdmin(), fn (Builder $query) => $query->whereIn('club_id', $this->accessibleClubIds()));
    }

    /**
     * @return LengthAwarePaginator<int, Invoice>
     */
    protected function invoices(): LengthAwarePaginator
    {
        return $this->scope()
            ->with(['member:id,name,phone', 'club:id,name'])
            // Invoice number, or the member's name or phone.
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $inner): void {
                $digits = Search::phoneDigits($this->search, $this->organisation(), 'invoice');

                $inner->where('number', 'ilike', "%{$this->search}%")
                    ->orWhereHas('member', fn (Builder $member) => $member
                        ->where('name', 'ilike', "%{$this->search}%")
                        ->when($digits !== null, fn (Builder $q) => $q->orWhere('phone', 'ilike', '%'.$digits.'%')));
            }))
            // Void invoices are out of the way unless asked for, the same rule
            // removed records follow everywhere else.
            ->when(
                $this->status !== '',
                fn (Builder $query) => $query->where('status', $this->status),
                fn (Builder $query) => $query->where('status', '!=', InvoiceStatus::Void),
            )
            ->when($this->club !== '', fn (Builder $query) => $query->where('club_id', $this->club))
            // Money still owed first, oldest due date first within that.
            ->orderByRaw('CASE WHEN status IN (?, ?) THEN 0 ELSE 1 END', [
                InvoiceStatus::Issued->value,
                InvoiceStatus::PartiallyPaid->value,
            ])
            ->orderBy('due_date')
            ->orderByDesc('id')
            ->paginate(15);
    }

    public function render(): View
    {
        $organisation = $this->organisation();
        $today = Carbon::today($organisation->timezone);

        $open = $this->scope()->clone()->whereIn('status', [InvoiceStatus::Issued, InvoiceStatus::PartiallyPaid]);

        return view('livewire.billing.index', [
            'organisation' => $organisation,
            'invoices' => $this->invoices(),
            'statuses' => InvoiceStatus::cases(),
            'clubs' => $this->accessibleClubs(true),
            'today' => $today,
            'outstandingTotal' => (int) $open->clone()->selectRaw('COALESCE(SUM(total_minor - paid_minor), 0) AS due')->value('due'),
            'openCount' => $open->clone()->count(),
            'overdueCount' => $open->clone()->whereDate('due_date', '<', $today->toDateString())->count(),
        ])->layout('components.layouts.app', ['heading' => 'Invoices']);
    }
}
