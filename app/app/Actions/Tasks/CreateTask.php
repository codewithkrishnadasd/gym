<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\AuditEvent;
use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskCategory;
use Illuminate\Support\Facades\DB;

/**
 * Creates a task in a category, starting it in the category's first status
 * and giving it one item per sub-category, each in that sub-category's first
 * status. The items are created here rather than lazily so the task's
 * checklist is fixed at creation: a sub-category added to the category later
 * does not retroactively appear on old tasks.
 */
final class CreateTask
{
    /**
     * @param  array{title: string, description?: string|null, start_date?: string|null, due_date?: string|null, task_status_id?: int|null, member_id?: int|null, assignee_ids?: array<int, int>}  $attributes
     */
    public function handle(TaskCategory $category, array $attributes, OrganisationUser $actor): Task
    {
        return DB::transaction(function () use ($category, $attributes, $actor): Task {
            $status = isset($attributes['task_status_id'])
                ? $category->statuses()->whereKey($attributes['task_status_id'])->first()
                : null;

            $status ??= $category->defaultStatus();

            $task = Task::create([
                'task_category_id' => $category->id,
                'task_status_id' => $status?->id,
                'title' => $attributes['title'],
                'description' => $attributes['description'] ?? null,
                'start_date' => $attributes['start_date'] ?? null,
                'due_date' => $attributes['due_date'] ?? null,
                'member_id' => $attributes['member_id'] ?? null,
                'created_by' => $actor->id,
                'completed_at' => $status?->completes ? now() : null,
            ]);

            $task->assignees()->sync($attributes['assignee_ids'] ?? []);

            foreach ($category->subCategories()->with('statuses')->get() as $position => $subCategory) {
                $task->items()->create([
                    'task_sub_category_id' => $subCategory->id,
                    'task_status_id' => $subCategory->statuses->first()?->id,
                    'position' => $position,
                ]);
            }

            AuditEvent::record(
                $task,
                'task.created',
                $actor,
                null,
                [
                    'title' => $task->title,
                    'task_category_id' => $category->id,
                    'task_status_id' => $task->task_status_id,
                    'member_id' => $task->member_id,
                    'assignee_ids' => array_values($attributes['assignee_ids'] ?? []),
                ],
            );

            return $task;
        });
    }
}
