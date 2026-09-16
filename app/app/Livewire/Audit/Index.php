<?php

declare(strict_types=1);

namespace App\Livewire\Audit;

use App\Livewire\Concerns\LoadsMore;
use App\Livewire\Concerns\RemembersFilters;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\OrganisationUser;
use App\Support\Listing\Slice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;

/**
 * The append-only audit trail (MEP.md 5.13).
 *
 * Read-only by construction: there is no update or delete action anywhere in
 * this component, and the policy grants nothing but `viewAny`.
 */
class Index extends Component
{
    use LoadsMore, RemembersFilters, ResolvesMembership;

    protected function pageSize(): int
    {
        return 25;
    }

    #[Url]
    public string $search = '';

    #[Url]
    public string $entityType = '';

    #[Url]
    public string $actor = '';

    #[Url]
    public string $from = '';

    #[Url]
    public string $to = '';

    public function mount(): void
    {
        $this->authorize('viewAny', AuditEvent::class);
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }
    }

    /**
     * @return Slice<AuditEvent>
     */
    protected function events(): Slice
    {
        return $this->slice(AuditEvent::query()
            ->with('actor.user:id,name')
            ->when($this->entityType !== '', fn (Builder $query) => $query->where('entity_type', $this->entityType))
            ->when($this->actor !== '', fn (Builder $query) => $query->where('actor_user_id', $this->actor))
            ->when($this->from !== '', fn (Builder $query) => $query->whereDate('created_at', '>=', $this->from))
            ->when($this->to !== '', fn (Builder $query) => $query->whereDate('created_at', '<=', $this->to))
            ->when($this->search !== '', fn (Builder $query) => $query->where('action', 'ilike', "%{$this->search}%"))
            ->orderByDesc('id')
        );
    }

    public function render(): View
    {
        return view('livewire.audit.index', [
            'organisation' => $this->organisation(),
            'events' => $this->events(),
            'entityTypes' => AuditEvent::query()->distinct()->orderBy('entity_type')->pluck('entity_type'),
            'actors' => OrganisationUser::query()->with('user:id,name')->get(),
        ])->layout('components.layouts.app', ['heading' => 'Audit log']);
    }
}
