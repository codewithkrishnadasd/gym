<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Livewire\Concerns\ResolvesMembership;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskItem;
use App\Models\TaskStatus;
use App\Support\Search;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * The team's task list. Open work first; done tasks hidden until asked for.
 */
class Index extends Component
{
    use ResolvesMembership, WithPagination;

    #[Url]
    public string $search = '';

    #[Url]
    public string $category = '';

    #[Url]
    public string $status = '';

    /** 'open' (default), 'done', or 'all'. */
    #[Url]
    public string $show = 'open';

    /** '' (everything visible), 'mine' (assigned to me), 'reported' (raised by me), 'mentioned' (named in a comment). */
    #[Url]
    public string $who = '';

    public function mount(): void
    {
        $this->authorize('viewAny', Task::class);
    }

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->resetPage();
        }

        // A status belongs to one category, so it cannot survive a change of
        // category filter.
        if ($property === 'category') {
            $this->status = '';
        }
    }

    /**
     * @return Builder<Task>
     */
    protected function scope(): Builder
    {
        return $this->scopeWithoutShow()
            ->when($this->show === 'open', fn (Builder $query) => $query->whereNull('completed_at'))
            ->when($this->show === 'done', fn (Builder $query) => $query->whereNotNull('completed_at'));
    }

    /**
     * Every filter except the open/done switch.
     *
     * @return Builder<Task>
     */
    protected function scopeWithoutShow(): Builder
    {
        $membership = $this->currentMembership();

        return Task::query()
            // Staff see what they reported and what was handed to them.
            ->when(! $membership->isAdmin(), fn (Builder $query) => $query->involving($membership))
            ->when($this->who === 'mine', fn (Builder $query) => $query->whereHas('assignees', fn (Builder $assignees) => $assignees->where('organisation_users.id', $membership->id)))
            ->when($this->who === 'reported', fn (Builder $query) => $query->where('created_by', $membership->id))
            ->when($this->who === 'mentioned', fn (Builder $query) => $query->whereHas('mentions', fn (Builder $mentions) => $mentions->where('organisation_user_id', $membership->id)))
            // Title, the task reference (TSK-12), or the member it concerns.
            ->when($this->search !== '', fn (Builder $query) => $query->where(function (Builder $inner): void {
                $id = Search::referenceId($this->organisation(), 'task', $this->search);

                $inner->where('title', 'ilike', '%'.$this->search.'%')
                    ->orWhereHas('member', fn (Builder $member) => $member->where('name', 'ilike', '%'.$this->search.'%'));

                if ($id !== null) {
                    $inner->orWhere('tasks.id', $id);
                }
            }))
            ->when($this->category !== '', fn (Builder $query) => $query->where('task_category_id', $this->category))
            ->when($this->status !== '', fn (Builder $query) => $query->where('task_status_id', $this->status));
    }

    /**
     * @return LengthAwarePaginator<int, Task>
     */
    protected function tasks(): LengthAwarePaginator
    {
        return $this->scope()
            ->with(['category:id,name', 'status', 'items.status', 'member:id,name', 'assignees.user:id,name'])
            // Dated work first, soonest due at the top; undated after.
            ->orderByRaw('due_date ASC NULLS LAST')
            ->orderByDesc('id')
            ->paginate(20);
    }

    /**
     * @return Collection<int, TaskStatus>
     */
    protected function statusesForFilter(): Collection
    {
        if ($this->category === '') {
            return new Collection;
        }

        return TaskStatus::query()->where('task_category_id', $this->category)->orderBy('position')->orderBy('id')->get();
    }

    public function render(): View
    {
        $organisation = $this->organisation();
        $today = Carbon::today($organisation->timezone);

        // The figures at the top describe exactly the tasks the filters have
        // selected — ignoring the open/done switch, so "done" is a share of a
        // whole rather than 0% or 100% of a half.
        $selected = $this->scopeWithoutShow();
        $totalCount = $selected->clone()->count();
        $doneCount = $selected->clone()->whereNotNull('completed_at')->count();
        $openIds = $selected->clone()->whereNull('completed_at');

        $partsQuery = TaskItem::query()
            ->whereIn('task_id', $selected->clone()->select('tasks.id'))
            ->leftJoin('task_statuses', 'task_statuses.id', '=', 'task_items.task_status_id');
        $partsTotal = (int) $partsQuery->clone()->count();
        $partsDone = (int) $partsQuery->clone()->where('task_statuses.completes', true)->count();

        return view('livewire.tasks.index', [
            'organisation' => $organisation,
            'today' => $today,
            'tasks' => $this->tasks(),
            'categories' => TaskCategory::query()->where('status', 'active')->orderBy('position')->orderBy('name')->get(),
            'statuses' => $this->statusesForFilter(),
            'totalCount' => $totalCount,
            'doneCount' => $doneCount,
            'donePercent' => $totalCount === 0 ? 0 : (int) round($doneCount / $totalCount * 100),
            'partsPercent' => $partsTotal === 0 ? null : (int) round($partsDone / $partsTotal * 100),
            'openCount' => $openIds->clone()->count(),
            'overdueCount' => $openIds->clone()->whereDate('due_date', '<', $today->toDateString())->count(),
            'dueTodayCount' => $openIds->clone()->whereDate('due_date', $today->toDateString())->count(),
            'canManageCategories' => auth()->user()?->can('manage', TaskCategory::class) ?? false,
        ])->layout('components.layouts.app', ['heading' => 'Tasks']);
    }
}
