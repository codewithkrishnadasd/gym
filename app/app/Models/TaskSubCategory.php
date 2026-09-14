<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\TaskSubCategoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * One part of a category's tasks, with its own statuses. Every task in the
 * category gets an item for it.
 */
#[Fillable(['organisation_id', 'task_category_id', 'name', 'position'])]
class TaskSubCategory extends Model
{
    /** @use HasFactory<TaskSubCategoryFactory> */
    use BelongsToOrganisation, HasFactory;

    /**
     * @return BelongsTo<TaskCategory, $this>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TaskCategory::class, 'task_category_id');
    }

    /**
     * @return HasMany<TaskStatus, $this>
     */
    public function statuses(): HasMany
    {
        return $this->hasMany(TaskStatus::class)->orderBy('position')->orderBy('id');
    }

    public function defaultStatus(): ?TaskStatus
    {
        return $this->statuses()->first();
    }
}
