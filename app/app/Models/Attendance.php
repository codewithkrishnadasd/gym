<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\AttendanceAction;
use App\Enums\AttendanceSource;
use App\Models\Concerns\BelongsToOrganisation;
use Database\Factories\AttendanceFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * `subject` is polymorphic across `members` and `organisation_users`, using
 * the 'member'/'user' morph map aliases registered in AppServiceProvider so
 * `subject_type` stores the short alias from MEP.md rather than a class
 * name. The unique index on
 * (organisation_id, club_id, subject_type, subject_id, attendance_date) is
 * the actual "one record per day" guarantee — see MEP.md 5.9.
 */
#[Fillable(['organisation_id', 'club_id', 'subject_type', 'subject_id', 'attendance_date', 'check_in_at', 'action', 'marked_by', 'source', 'notes'])]
class Attendance extends Model
{
    /** @use HasFactory<AttendanceFactory> */
    use BelongsToOrganisation, HasFactory;

    protected function casts(): array
    {
        return [
            'action' => AttendanceAction::class,
            'source' => AttendanceSource::class,
            'attendance_date' => 'date',
            'check_in_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo<Club, $this>
     */
    public function club(): BelongsTo
    {
        return $this->belongsTo(Club::class);
    }

    /**
     * @return MorphTo<Model, $this>
     */
    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return BelongsTo<OrganisationUser, $this>
     */
    public function markedBy(): BelongsTo
    {
        return $this->belongsTo(OrganisationUser::class, 'marked_by');
    }
}
