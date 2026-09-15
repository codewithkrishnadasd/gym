<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A colleague named with "@" in a task comment. Being mentioned makes the
 * task visible to them (Task::scopeInvolving).
 */
#[Fillable(['task_id', 'task_comment_id', 'organisation_user_id'])]
class TaskMention extends Model
{
    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function person(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'organisation_user_id');
    }
}
