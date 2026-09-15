<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\TaskReminderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * A dated note on a task: "chase the supplier" on the 20th. Once the date
 * arrives it is shown on opening the app to everyone involved in the task,
 * and keeps showing until the task is done. Not tenant-scoped itself: only
 * ever reached through its task, which is.
 */
#[Fillable(['task_id', 'label', 'remind_on', 'created_by'])]
class TaskReminder extends Model
{
    /** @use HasFactory<TaskReminderFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'remind_on' => 'date',
        ];
    }

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
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'created_by');
    }

    public function isDue(Carbon $today): bool
    {
        return $this->remind_on->toDateString() <= $today->toDateString();
    }
}
