<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsToOrganisation;
use App\Support\Theme\AccentPalette;
use Database\Factories\TaskStatusFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A named, coloured state for either a category (the task as a whole) or a
 * sub-category (one item on the task). The first status in a set is where
 * new records start; a status flagged `completes` marks the work as done.
 */
#[Fillable(['organisation_id', 'task_category_id', 'task_sub_category_id', 'name', 'color', 'completes', 'position'])]
class TaskStatus extends Model
{
    /** @use HasFactory<TaskStatusFactory> */
    use BelongsToOrganisation, HasFactory;

    /** Offered in the editor so a new status set looks considered from the start. */
    public const PALETTE = ['#64748b', '#0e7490', '#4338ca', '#7c3aed', '#b45309', '#047857', '#be123c', '#0f172a'];

    protected function casts(): array
    {
        return [
            'completes' => 'boolean',
            'position' => 'integer',
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
     * @return BelongsTo<TaskSubCategory, $this>
     */
    public function subCategory(): BelongsTo
    {
        return $this->belongsTo(TaskSubCategory::class, 'task_sub_category_id');
    }

    /**
     * Text colour that stays readable on this status's colour — chosen by
     * luminance, so a pale yellow gets dark text and a navy gets white.
     */
    public function textColor(): string
    {
        return AccentPalette::readableOn($this->color);
    }

    /** Inline style for a solid chip in this status's colours. */
    public function style(): string
    {
        return 'background-color:'.$this->color.';color:'.$this->textColor().';';
    }
}
