<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\OrganisationDailyMetric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrganisationDailyMetric>
 */
class OrganisationDailyMetricFactory extends Factory
{
    protected $model = OrganisationDailyMetric::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'metric_date' => now()->toDateString(),
            'total_members' => fake()->numberBetween(10, 500),
            'new_members' => fake()->numberBetween(0, 20),
            'active_members' => fake()->numberBetween(10, 500),
            'attendance_present' => fake()->numberBetween(0, 500),
            'revenue_collected_minor' => fake()->numberBetween(0, 1000000),
            'expenses_recorded_minor' => fake()->numberBetween(0, 500000),
            'net_movement_minor' => fake()->numberBetween(-100000, 500000),
            'by_club' => [],
            'updated_at' => now(),
        ];
    }
}
