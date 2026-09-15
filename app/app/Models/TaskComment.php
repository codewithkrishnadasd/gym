<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TaskCommentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * One remark on a task. Not tenant-scoped itself: only ever reached through
 * its task, which is.
 */
#[Fillable(['task_id', 'organisation_user_id', 'body'])]
class TaskComment extends Model
{
    /** @use HasFactory<TaskCommentFactory> */
    use HasFactory;

    /**
     * @return BelongsTo<Task, $this>
     */
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'organisation_user_id');
    }

    /**
     * @return HasMany<TaskMention, $this>
     */
    public function mentions(): HasMany
    {
        return $this->hasMany(TaskMention::class);
    }
}
