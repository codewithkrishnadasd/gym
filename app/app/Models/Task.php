<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * A piece of work for the team. Its status comes from its category; its
 * items mirror the category's sub-categories, each with a status of their
 * own.
 */
#[Fillable([
    'organisation_id', 'task_category_id', 'task_status_id', 'member_id', 'title', 'description',
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

    /**
     * @return BelongsToMany<OrganisationUser, $this>
     */
    public function assignees(): BelongsToMany
    {
        return $this->belongsToMany(OrganisationUser::class, 'task_assignees')->withTimestamps();
    }

    /**
     * The member this task is about, if any.
     *
     * @return BelongsTo<Member, $this>
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * Tasks the person reported or was handed. Admins are never scoped here;
     * callers decide whether to apply it.
     *
     * @param  Builder<Task>  $query
     * @return Builder<Task>
     */
    public function scopeInvolving(Builder $query, OrganisationUser $membership): Builder
    {
        return $query->where(fn (Builder $inner) => $inner
            ->where('created_by', $membership->id)
            ->orWhereHas('assignees', fn (Builder $assignees) => $assignees->where('organisation_users.id', $membership->id)));
    }

    public function involves(OrganisationUser $membership): bool
    {
        return $this->created_by === $membership->id
            || $this->assignees()->where('organisation_users.id', $membership->id)->exists();
    }

    /**
     * The description as safe HTML. Raw HTML in the markdown is stripped, so
     * a task body can never carry a script into another person's browser.
     */
    public function descriptionHtml(): string
    {
        if ($this->description === null || trim($this->description) === '') {
            return '';
        }

        return (string) Str::markdown($this->description, [
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);
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
