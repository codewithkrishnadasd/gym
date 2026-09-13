<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\AttendanceAction;
use App\Enums\AttendanceSource;
use App\Models\Attendance;
use App\Models\Club;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'club_id' => Club::factory(),
            'subject_type' => 'member',
            'subject_id' => Member::factory(),
            'attendance_date' => now()->toDateString(),
            'action' => AttendanceAction::Present,
            'marked_by' => OrganisationUser::factory(),
            'source' => AttendanceSource::Manual,
        ];
    }
}
