<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Actions\Tasks\CreateTask;
use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskStatus;
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

            return;
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
        ], [
            'dueDate.after_or_equal' => 'The due date cannot be before the start date.',
            'statusId.exists' => 'Pick a status that belongs to the chosen category.',
        ], ['categoryId' => 'category', 'statusId' => 'status']);

        $actor = $this->currentMembership();

        if ($this->task) {
            $before = $this->task->only(['title', 'description', 'start_date', 'due_date', 'task_status_id']);

            $this->task->fill([
                'title' => $validated['title'],
                'description' => $validated['description'] ?: null,
                'start_date' => $validated['startDate'] ?: null,
                'due_date' => $validated['dueDate'] ?: null,
            ])->save();

            if ($validated['statusId'] !== null && (int) $validated['statusId'] !== $this->task->task_status_id) {
                /** @var TaskStatus $status */
                $status = TaskStatus::query()->findOrFail($validated['statusId']);
                $this->task->moveTo($status);
            }

            AuditEvent::record($this->task, 'task.updated', $actor, $before, $this->task->only(array_keys($before)));

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
            'today' => Carbon::today($organisation->timezone)->toDateString(),
        ])->layout('components.layouts.app', ['heading' => $this->task ? 'Edit task' : 'New task']);
    }
}
