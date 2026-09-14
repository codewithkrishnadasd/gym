<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\TaskCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * A kind of task. Owns the statuses a task of this kind moves through and
 * the sub-categories every such task is broken into.
 */
#[Fillable(['organisation_id', 'name', 'status', 'position'])]
class TaskCategory extends Model
{
    /** @use HasFactory<TaskCategoryFactory> */
    use BelongsToOrganisation, HasFactory;

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * @return HasMany<TaskStatus, $this>
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(TaskStatus::class)->orderBy('position')->orderBy('id');
    }

    /**
     * @return HasMany<TaskSubCategory, $this>
     */
    public function subCategories(): HasMany
    {
        return $this->hasMany(TaskSubCategory::class)->orderBy('position')->orderBy('id');
    }

    /**
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    /**
     * The status a new task of this kind starts in: the first one listed.
     */
    public function defaultStatus(): ?TaskStatus
    {
        return $this->statuses()->first();
    }
}
