<?php

declare(strict_types=1);

namespace App\Livewire\Plans;

use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Plan;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use ResolvesMembership, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $status = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Plan::class);
    }

    public function updated(string $property): void
    {
        if (in_array($property, ['search', 'status'], true)) {
            $this->resetPage();
        }
    }

    public function archive(Plan $plan): void
    {
        $this->authorize('archive', Plan::class);

        $plan->update(['status' => PlanStatus::Archived]);

        session()->flash('status', "\"{$plan->name}\" was removed. Existing subscriptions are unaffected.");
    }

    public function restore(Plan $plan): void
    {
        $this->authorize('archive', Plan::class);

        $plan->update(['status' => PlanStatus::Active]);
    }

    /**
     * @return LengthAwarePaginator<int, Plan>
     */
    protected function plans(): LengthAwarePaginator
    {
        return Plan::query()
            ->withCount(['subscriptions as active_subscriptions_count' => fn ($query) => $query->where('status', SubscriptionStatus::Active)])
            ->when($this->search !== '', fn ($query) => $query->where('name', 'ilike', "%{$this->search}%"))
            // Removed rows only appear when explicitly filtered for.
            ->when(
                $this->status !== '',
                fn ($query) => $query->where('status', $this->status),
                fn ($query) => $query->where('status', '!=', PlanStatus::Archived),
            )
            ->orderBy('status')
            ->orderBy('price_minor')
            ->paginate(15);
    }

    public function render(): View
    {
        return view('livewire.plans.index', [
            'plans' => $this->plans(),
            'organisation' => $this->organisation(),
            'statuses' => PlanStatus::cases(),
        ])->layout('components.layouts.app', ['heading' => 'Plans']);
    }
}
