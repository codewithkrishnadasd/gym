<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\PlanStatus;
use App\Models\Organisation;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'name' => fake()->randomElement(['Monthly', 'Quarterly', 'Annual']).' Plan',
            'description' => fake()->sentence(),
            'price_minor' => fake()->numberBetween(2000, 20000),
            'currency_code' => 'USD',
            'duration_days' => fake()->randomElement([30, 90, 365]),
            'status' => PlanStatus::Active,
        ];
    }
}
