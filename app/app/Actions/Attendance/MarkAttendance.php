<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Enums\AttendanceAction;
use App\Enums\AttendanceSource;
use App\Enums\AttendanceSubjectType;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Marks attendance for one subject on one day (MEP.md 5.9).
 *
 * The composite unique index on
 * (organisation_id, club_id, subject_type, subject_id, attendance_date)
 * is the real "one record per day" guarantee, so this uses an upsert against
 * that index rather than a read-then-write, which would race whenever two
 * staff members mark the same roster at once.
 */
final class MarkAttendance
{
    /**
     * `$club` is null for staff: their attendance is a day for the
     * organisation, not a day at a club.
     */
    public function handle(
        Organisation $organisation,
        ?Club $club,
        AttendanceSubjectType $subjectType,
        int $subjectId,
        Carbon $date,
        AttendanceAction $action,
        OrganisationUser $actor,
        ?string $notes = null,
    ): Attendance {
        Attendance::query()->upsert(
            [[
                'organisation_id' => $organisation->id,
                'club_id' => $club?->id,
                'subject_type' => $subjectType->value,
                'subject_id' => $subjectId,
                'attendance_date' => $date->toDateString(),
                'check_in_at' => $action === AttendanceAction::Absent ? null : now(),
                'action' => $action->value,
                'marked_by' => $actor->id,
                'source' => AttendanceSource::Manual->value,
                'notes' => $notes,
                'created_at' => now(),
                'updated_at' => now(),
            ]],
            ['organisation_id', 'club_id', 'subject_type', 'subject_id', 'attendance_date'],
            ['action', 'check_in_at', 'marked_by', 'notes', 'updated_at'],
        );

        /** @var Attendance $attendance */
        $attendance = Attendance::query()
            ->where('organisation_id', $organisation->id)
            ->where('club_id', $club?->id)
            ->where('subject_type', $subjectType->value)
            ->where('subject_id', $subjectId)
            ->whereDate('attendance_date', $date->toDateString())
            ->firstOrFail();

        return $attendance;
    }

    /**
     * Marks a whole roster in one statement. Used by "mark all present" and
     * bulk save, where issuing one query per member would be both slow and
     * partially applicable on failure.
     *
     * @param  array<int, int>  $subjectIds
     */
    public function handleMany(
        Organisation $organisation,
        ?Club $club,
        AttendanceSubjectType $subjectType,
        array $subjectIds,
        Carbon $date,
        AttendanceAction $action,
        OrganisationUser $actor,
    ): int {
        if ($subjectIds === []) {
            return 0;
        }

        $now = now();

        $rows = array_map(fn (int $subjectId): array => [
            'organisation_id' => $organisation->id,
            'club_id' => $club?->id,
            'subject_type' => $subjectType->value,
            'subject_id' => $subjectId,
            'attendance_date' => $date->toDateString(),
            'check_in_at' => $action === AttendanceAction::Absent ? null : $now,
            'action' => $action->value,
            'marked_by' => $actor->id,
            'source' => AttendanceSource::Manual->value,
            'notes' => null,
            'created_at' => $now,
            'updated_at' => $now,
        ], array_values($subjectIds));

        DB::transaction(function () use ($rows): void {
            foreach (array_chunk($rows, 500) as $chunk) {
                Attendance::query()->upsert(
                    $chunk,
                    ['organisation_id', 'club_id', 'subject_type', 'subject_id', 'attendance_date'],
                    ['action', 'check_in_at', 'marked_by', 'updated_at'],
                );
            }
        });

        return count($rows);
    }
}
