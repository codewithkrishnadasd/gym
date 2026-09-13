<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ClubAssignmentStatus;
use App\Models\Club;
use App\Models\ClubUserAssignment;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClubUserAssignment>
 *
 * The `organisation_id` here is only a NOT NULL safety net for ad hoc use —
 * callers wiring up a realistic scenario should pass a `club` and
 * `organisationUser` that already belong to the same organisation (see
 * DatabaseSeeder) since no database constraint cross-checks this.
 */
class ClubUserAssignmentFactory extends Factory
{
    protected $model = ClubUserAssignment::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'club_id' => Club::factory(),
            'organisation_user_id' => OrganisationUser::factory(),
            'status' => ClubAssignmentStatus::Active,
            'assigned_at' => now(),
        ];
    }
}
