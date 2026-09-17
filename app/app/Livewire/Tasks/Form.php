<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Actions\Tasks\CreateTask;
use App\Enums\MembershipStatus;
use App\Enums\MemberStatus;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Club;
use App\Models\Member;
use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Livewire\Component;

/**
 * Creating or editing a task. The category is chosen once, at creation: the
 * task's statuses and its checklist of sub-categories come from it.
 */
class Form extends Component
{
    use ResolvesMembership;

    public ?Task $task = null;

    public ?int $categoryId = null;

    public ?int $statusId = null;

    public string $title = '';

    public string $description = '';

    public string $startDate = '';

    public string $dueDate = '';

    /** @var array<int, int> */
    public array $assigneeIds = [];

    public string $assigneeSearch = '';

    public ?int $memberId = null;

    /** The club the task belongs to, when the organisation has clubs. */
    public ?int $clubId = null;

    public string $memberSearch = '';

    public function mount(?Task $task = null): void
    {
        $this->task = $task;

        $this->authorize($task ? 'update' : 'create', $task ?? Task::class);

        if ($task) {
            $this->categoryId = $task->task_category_id;
            $this->statusId = $task->task_status_id;
            $this->title = $task->title;
            $this->description = (string) $task->description;
            $this->startDate = $task->start_date?->toDateString() ?? '';
            $this->dueDate = $task->due_date?->toDateString() ?? '';
            $this->assigneeIds = $task->assignees()->pluck('organisation_users.id')->all();
            $this->memberId = $task->member_id;
            $this->clubId = $task->club_id;

            return;
        }

        // Opened from a member's page: the task is about them, at their club.
        $member = request()->integer('member') ?: null;

        if ($member !== null) {
            $this->selectMember($member);
        }

        // Or from a club, or where the user only has the one.
        $club = request()->integer('club') ?: null;
        $clubs = $this->selectableClubs();

        if ($this->clubId === null) {
            $this->clubId = $club !== null && $clubs->contains('id', $club)
                ? $club
                : ($clubs->count() === 1 ? $clubs->first()?->id : null);
        }

        $requested = request()->integer('category') ?: null;
        $categories = $this->categories();

        // Preselect when opened from a category, or when there is only one.
        $this->categoryId = $requested !== null && $categories->contains('id', $requested)
            ? $requested
            : ($categories->count() === 1 ? $categories->first()?->id : null);

        $this->statusId = $this->category()?->defaultStatus()?->id;
    }

    public function updatedCategoryId(): void
    {
        $this->statusId = $this->category()?->defaultStatus()?->id;
    }

    public function save(): void
    {
        $this->authorize($this->task ? 'update' : 'create', $this->task ?? Task::class);

        $validated = $this->validate([
            'title' => ['required', 'string', 'max:200'],
            'categoryId' => [
                'required',
                Rule::exists('task_categories', 'id')->where('organisation_id', app('tenant')->id)->where('status', 'active'),
            ],
            'statusId' => [
                'nullable',
                Rule::exists('task_statuses', 'id')->where('task_category_id', $this->categoryId),
            ],
            'description' => ['nullable', 'string', 'max:5000'],
            'startDate' => ['nullable', 'date'],
            'dueDate' => ['nullable', 'date', 'after_or_equal:startDate'],
            'assigneeIds' => ['array'],
            'assigneeIds.*' => [
                'integer',
                Rule::exists('organisation_users', 'id')->where('organisation_id', app('tenant')->id)->where('status', 'active'),
            ],
            'memberId' => ['nullable', Rule::exists('members', 'id')->where('organisation_id', app('tenant')->id)],
            'clubId' => $this->organisation()->usesClubs()
                ? ['nullable', Rule::in($this->selectableClubs()->pluck('id')->all())]
                : ['nullable', 'prohibited'],
        ], [
            'dueDate.after_or_equal' => 'The due date cannot be before the start date.',
            'statusId.exists' => 'Pick a status that belongs to the chosen category.',
        ], ['categoryId' => 'category', 'statusId' => 'status', 'clubId' => strtolower($this->organisation()->term('club_singular'))]);

        $actor = $this->currentMembership();

        if ($this->task) {
            $before = [
                ...$this->task->only(['title', 'description', 'start_date', 'due_date', 'task_status_id', 'member_id', 'club_id']),
                'assignee_ids' => $this->task->assignees()->pluck('organisation_users.id')->all(),
            ];

            $this->task->fill([
                'title' => $validated['title'],
                'description' => $validated['description'] ?: null,
                'start_date' => $validated['startDate'] ?: null,
                'due_date' => $validated['dueDate'] ?: null,
                'member_id' => $validated['memberId'] !== null ? (int) $validated['memberId'] : null,
                'club_id' => $validated['clubId'] !== null ? (int) $validated['clubId'] : null,
            ])->save();

            $this->task->assignees()->sync(array_map('intval', $validated['assigneeIds']));

            if ($validated['statusId'] !== null && (int) $validated['statusId'] !== $this->task->task_status_id) {
                /** @var TaskStatus $status */
                $status = TaskStatus::query()->findOrFail($validated['statusId']);
                $this->task->moveTo($status);
            }

            AuditEvent::record($this->task, 'task.updated', $actor, $before, [
                ...$this->task->only(['title', 'description', 'start_date', 'due_date', 'task_status_id', 'member_id', 'club_id']),
                'assignee_ids' => array_values(array_map('intval', $validated['assigneeIds'])),
            ]);

            $task = $this->task;
        } else {
            /** @var TaskCategory $category */
            $category = TaskCategory::query()->findOrFail($validated['categoryId']);

            $task = app(CreateTask::class)->handle($category, [
                'title' => $validated['title'],
                'description' => $validated['description'] ?: null,
                'start_date' => $validated['startDate'] ?: null,
                'due_date' => $validated['dueDate'] ?: null,
                'task_status_id' => $validated['statusId'] !== null ? (int) $validated['statusId'] : null,
                'member_id' => $validated['memberId'] !== null ? (int) $validated['memberId'] : null,
                'club_id' => $validated['clubId'] !== null ? (int) $validated['clubId'] : null,
                'assignee_ids' => array_map('intval', $validated['assigneeIds']),
            ], $actor);
        }

        session()->flash('status', "\"{$task->title}\" was saved.");

        $this->redirect(route('tenant.tasks.show', $task), navigate: true);
    }

    /**
     * @return Collection<int, TaskCategory>
     */
    private function categories(): Collection
    {
        return TaskCategory::query()->where('status', 'active')->orderBy('position')->orderBy('name')->get();
    }

    /**
     * Adds a colleague to the assignees; picking the same person twice is a
     * no-op rather than a duplicate.
     */
    public function addAssignee(int $organisationUserId): void
    {
        if ($this->assigneeCandidates(true)->contains('id', $organisationUserId) && ! in_array($organisationUserId, $this->assigneeIds, true)) {
            $this->assigneeIds[] = $organisationUserId;
        }

        $this->assigneeSearch = '';
    }

    public function removeAssignee(int $organisationUserId): void
    {
        $this->assigneeIds = array_values(array_filter($this->assigneeIds, fn (int $id): bool => $id !== $organisationUserId));
    }

    /**
     * Active people matching the search, minus those already assigned.
     *
     * @return Collection<int, OrganisationUser>
     */
    private function assigneeCandidates(bool $ignoreSearchLength = false): Collection
    {
        if (! $ignoreSearchLength && mb_strlen($this->assigneeSearch) < 1) {
            return new Collection;
        }

        return OrganisationUser::query()
            ->with('user:id,name,phone')
            ->where('status', MembershipStatus::Active)
            ->when(! $ignoreSearchLength, fn ($query) => $query->whereNotIn('id', $this->assigneeIds))
            ->when($this->assigneeSearch !== '', fn ($query) => $query->whereHas('user', fn ($user) => $user
                ->where('name', 'ilike', "%{$this->assigneeSearch}%")
                ->orWhere('phone', 'ilike', '%'.preg_replace('/\D+/', '', $this->assigneeSearch).'%')))
            ->get()
            ->sortBy(fn (OrganisationUser $person): string => (string) $person->user?->name)
            ->values()
            ->take(8);
    }

    public function selectMember(int $memberId): void
    {
        $member = $this->selectableMembers()->find($memberId);

        if ($member) {
            $this->memberId = $member->id;

            // Their name is the title until something more specific is typed.
            if (trim($this->title) === '') {
                $this->title = $member->name;
            }

            // The task is most likely about their club, unless one was chosen.
            if ($this->clubId === null && $member->primary_club_id !== null && $this->selectableClubs()->contains('id', $member->primary_club_id)) {
                $this->clubId = $member->primary_club_id;
            }
            $this->memberSearch = '';
        }
    }

    public function clearMember(): void
    {
        $this->memberId = null;
    }

    /**
     * Members the acting user may tag: those in their clubs.
     *
     * @return Collection<int, Member>
     */
    /**
     * The clubs a task can be filed under: none without the Clubs module.
     *
     * @return Collection<int, Club>
     */
    private function selectableClubs(): Collection
    {
        return $this->organisation()->usesClubs() ? $this->accessibleClubs() : new Collection;
    }

    /**
     * @return Builder<Member>
     */
    private function selectableMembers(): Builder
    {
        return $this->restrictToClubs(Member::query(), 'primary_club_id')
            ->with('primaryClub:id,name')
            ->where('status', '!=', MemberStatus::Archived);
    }

    /**
     * @return Collection<int, Member>
     */
    private function memberCandidates(bool $ignoreSearchLength = false): Collection
    {
        if (! $ignoreSearchLength && mb_strlen($this->memberSearch) < 2) {
            return new Collection;
        }

        return $this->selectableMembers()
            ->when($this->memberSearch !== '', fn ($query) => $query->where(
                fn ($inner) => $inner->where('name', 'ilike', "%{$this->memberSearch}%")
                    ->orWhere('phone', 'ilike', "%{$this->memberSearch}%")
            ))
            ->when($this->memberId !== null && $this->memberSearch === '', fn ($query) => $query->orWhere('id', $this->memberId))
            ->orderBy('name')
            ->limit(8)
            ->get();
    }

    private function category(): ?TaskCategory
    {
        return $this->categoryId ? TaskCategory::query()->with(['statuses', 'subCategories.statuses'])->find($this->categoryId) : null;
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        return view('livewire.tasks.form', [
            'organisation' => $organisation,
            'categories' => $this->categories(),
            'category' => $this->category(),
            'assignees' => OrganisationUser::query()->with('user:id,name,phone')->whereIn('id', $this->assigneeIds)->get()
                ->sortBy(fn (OrganisationUser $person): int => (int) array_search($person->id, $this->assigneeIds, true))->values(),
            'assigneeResults' => $this->assigneeCandidates(),
            'hasPeople' => OrganisationUser::query()->where('status', MembershipStatus::Active)->exists(),
            'selectedMember' => $this->memberId ? Member::query()->with('primaryClub:id,name')->find($this->memberId) : null,
            'clubs' => $this->selectableClubs(),
            'memberResults' => $this->memberId === null ? $this->memberCandidates() : new Collection,
            'today' => Carbon::today($organisation->timezone)->toDateString(),
        ])->layout('components.layouts.app', ['heading' => $this->task ? 'Edit task' : 'New task']);
    }
}
