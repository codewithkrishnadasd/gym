<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MemberStatus;
use App\Models\Member;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'name' => fake()->name(),
            'phone' => (string) fake()->unique()->numberBetween(910000000000, 919999999999),
            'date_of_birth' => fake()->date(),
            'gender' => fake()->randomElement(['male', 'female', 'other']),
            'joined_at' => fake()->dateTimeBetween('-2 years')->format('Y-m-d'),
            'status' => MemberStatus::Active,
        ];
    }
}
