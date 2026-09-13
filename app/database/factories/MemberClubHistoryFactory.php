<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Club;
use App\Models\Member;
use App\Models\MemberClubHistory;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberClubHistory>
 */
class MemberClubHistoryFactory extends Factory
{
    protected $model = MemberClubHistory::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'member_id' => Member::factory(),
            'to_club_id' => Club::factory(),
            'changed_at' => now(),
            'changed_by' => OrganisationUser::factory(),
        ];
    }
}
