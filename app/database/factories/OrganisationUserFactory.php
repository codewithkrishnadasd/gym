<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MembershipRole;
use App\Enums\MembershipStatus;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganisationUser>
 */
class OrganisationUserFactory extends Factory
{
    protected $model = OrganisationUser::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'user_id' => User::factory(),
            'role' => MembershipRole::User,
            'status' => MembershipStatus::Active,
            'permissions' => [],
            'club_ids' => [],
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => MembershipRole::Admin,
        ]);
    }

    public function invited(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => MembershipStatus::Invited,
        ]);
    }
}
