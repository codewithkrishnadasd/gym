<?php

declare(strict_types=1);

namespace App\Livewire\Tasks;

use App\Livewire\Concerns\ResolvesMembership;
use App\Models\AuditEvent;
use App\Models\Task;
use App\Models\TaskItem;
use App\Models\TaskStatus;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

/**
 * One task: its details, and the board where each part's status is moved.
 * Every status change is a single click and is saved immediately.
 */
class Show extends Component
{
    use ResolvesMembership;

    public Task $task;

    public function mount(Task $task): void
    {
        $this->authorize('view', $task);

        $this->task = $task;
    }

    /**
     * Moves the task itself to another of its category's statuses.
     */
    public function setStatus(int $statusId): void
    {
        $this->authorize('update', $this->task);

        /** @var TaskStatus $status */
        $status = TaskStatus::query()
            ->where('task_category_id', $this->task->task_category_id)
            ->findOrFail($statusId);

        $before = ['task_status_id' => $this->task->task_status_id];

        $this->task->moveTo($status);

        AuditEvent::record($this->task, 'task.status_changed', $this->currentMembership(), $before, ['task_status_id' => $status->id]);

        $this->task->refresh();
    }

    /**
     * Moves one part of the task to another of its sub-category's statuses.
     */
    public function setItemStatus(int $itemId, int $statusId): void
    {
        $this->authorize('update', $this->task);

        /** @var TaskItem $item */
        $item = $this->task->items()->findOrFail($itemId);

        /** @var TaskStatus $status */
        $status = TaskStatus::query()
            ->where('task_sub_category_id', $item->task_sub_category_id)
            ->findOrFail($statusId);

        $before = ['task_status_id' => $item->task_status_id];

        $item->update(['task_status_id' => $status->id]);

        AuditEvent::record(
            $this->task,
            'task.item_status_changed',
            $this->currentMembership(),
            $before,
            ['task_status_id' => $status->id],
            ['task_item_id' => $item->id, 'task_sub_category_id' => $item->task_sub_category_id],
        );

        $this->task->refresh();
    }

    public function delete(): void
    {
        $this->authorize('delete', $this->task);

        $title = $this->task->title;

        AuditEvent::record($this->task, 'task.deleted', $this->currentMembership(), ['title' => $title], null);

        $this->task->delete();

        session()->flash('status', "\"{$title}\" was deleted.");

        $this->redirect(route('tenant.tasks.index'), navigate: true);
    }

    public function render(): View
    {
        $organisation = $this->organisation();

        $this->task->load([
            'category.statuses',
            'status',
            'items.subCategory.statuses',
            'items.status',
            'createdBy.user:id,name',
        ]);

        $items = $this->task->items;

        return view('livewire.tasks.show', [
            'organisation' => $organisation,
            'today' => Carbon::today($organisation->timezone),
            'items' => $items,
            'doneCount' => $items->filter(fn (TaskItem $item): bool => $item->isDone())->count(),
        ])->layout('components.layouts.app', ['heading' => $this->task->title]);
    }
}
