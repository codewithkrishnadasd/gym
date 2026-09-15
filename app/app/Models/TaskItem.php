<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TaskItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * One sub-category on one task, with its own status. Not tenant-scoped
 * itself: only ever reached through its task, which is.
 */
#[Fillable(['task_id', 'task_sub_category_id', 'task_status_id', 'assignee_id', 'position'])]
class TaskItem extends Model
{
    /** @use HasFactory<TaskItemFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Task, $this>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @return BelongsTo<TaskSubCategory, $this>
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(TaskSubCategory::class, 'task_sub_category_id');
    }

    /**
     * @return BelongsTo<TaskStatus, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function assignee(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'assignee_id');
    }

    public function isDone(): bool
    {
        return (bool) $this->status?->completes;
    }
}
