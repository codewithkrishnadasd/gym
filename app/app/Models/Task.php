<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * A piece of work for the team. Its status comes from its category; its
 * items mirror the category's sub-categories, each with a status of their
 * own.
 */
#[Fillable([
    'organisation_id', 'task_category_id', 'task_status_id', 'title', 'description',
    'start_date', 'due_date', 'created_by', 'completed_at',
])]
class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<TaskCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    /**
     * @return BelongsTo<TaskStatus, $this>
     */
    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class, 'task_status_id');
    }

    /**
     * @return HasMany<TaskItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(TaskItem::class)->orderBy('position')->orderBy('id');
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'created_by');
    }

    public function isDone(): bool
    {
        return $this->completed_at !== null;
    }

    /**
     * Past due and not yet done. Compared on the bare date in the
     * organisation's day, so a task due today is not overdue at breakfast.
     */
    public function isOverdue(Carbon $today): bool
    {
        return ! $this->isDone()
            && $this->due_date !== null
            && $this->due_date->toDateString() < $today->toDateString();
    }

    /**
     * Moves the task to one of its category's statuses, keeping the
     * completed-at timestamp in step with whether that status completes.
     */
    public function moveTo(TaskStatus $status): void
    {
        $this->forceFill([
            'task_status_id' => $status->id,
            'completed_at' => $status->completes ? ($this->completed_at ?? now()) : null,
        ])->save();
    }
}
