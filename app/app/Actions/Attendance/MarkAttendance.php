<?php

declare(strict_types=1);

namespace App\Actions\Attendance;

use App\Actions\Notifications\CreateActionNotification;
use App\Enums\AttendanceAction;
use App\Enums\AttendanceSource;
use App\Enums\AttendanceSubjectType;
use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\WhatsappActionNotification;
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
    public function __construct(private readonly CreateActionNotification $notifications) {}

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

        $this->notify($organisation, $attendance, $club, $actor);

        return $attendance;
    }

    /**
     * The attendance message is opt-in (off by default — daily marking is a
     * lot of messages), so this usually returns null. One mark, one message:
     * re-marking the same day replaces rather than repeats it.
     */
    public function notify(Organisation $organisation, Attendance $attendance, ?Club $club, OrganisationUser $actor): ?WhatsappActionNotification
    {
        $isMember = $attendance->subject_type === AttendanceSubjectType::Member->value;
        $type = $isMember ? NotificationActionType::MemberAttendanceMarked : NotificationActionType::UserAttendanceMarked;

        if (! $organisation->notificationsEnabled($type)) {
            return null;
        }

        if ($isMember) {
            $member = Member::query()->find($attendance->subject_id);
            $name = $member?->name;
            $phone = $member?->phone;
        } else {
            $staff = OrganisationUser::query()->with('user:id,name,phone')->find($attendance->subject_id);
            $name = $staff?->user?->name;
            $phone = $staff?->user?->phone;
        }

        if ($name === null) {
            return null;
        }

        return $this->notifications->handle(
            organisation: $organisation,
            type: $type,
            recipientType: $isMember ? NotificationRecipientType::Member : NotificationRecipientType::User,
            recipientId: $attendance->subject_id,
            recipientName: $name,
            recipientPhone: $phone,
            entityType: NotificationEntityType::Attendance,
            entityId: $attendance->id,
            actor: $actor,
            operationId: $type->value.'.'.$attendance->id.'.'.$attendance->action->value,
            context: [
                'clubName' => $club?->name,
                'effectiveDate' => $attendance->attendance_date->format('d M Y'),
                'changedItem' => $attendance->action->label(),
            ],
        );
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
