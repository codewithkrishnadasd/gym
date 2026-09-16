<?php

declare(strict_types=1);

namespace App\Livewire\Plans;

use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Livewire\Concerns\LoadsMore;
use App\Livewire\Concerns\RemembersFilters;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Plan;
use App\Support\Listing\Slice;
use App\Support\Search;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

class Index extends Component
{
    use LoadsMore, RemembersFilters, ResolvesMembership;

    protected function pageSize(): int
    {
        return 15;
    }

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
     * @return Slice<Plan>
     */
    protected function plans(): Slice
    {
        return $this->slice(Plan::query()
            ->withCount(['subscriptions as active_subscriptions_count' => fn ($query) => $query->where('status', SubscriptionStatus::Active)])
            ->when($this->search !== '', fn ($query) => Search::apply($query, $this->organisation(), 'plan', $this->search, ['name'], null))
            // Removed rows only appear when explicitly filtered for.
            ->when(
                $this->status !== '',
                fn ($query) => $query->where('status', $this->status),
                fn ($query) => $query->where('status', '!=', PlanStatus::Archived),
            )
            ->orderBy('status')
            ->orderBy('price_minor')
        );
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
