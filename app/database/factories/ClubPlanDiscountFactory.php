<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Club;
use App\Models\ClubPlanDiscount;
use App\Models\Organisation;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClubPlanDiscount>
 */
class ClubPlanDiscountFactory extends Factory
{
    protected $model = ClubPlanDiscount::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'club_id' => Club::factory(),
            'plan_id' => Plan::factory(),
            'discount_minor' => fake()->numberBetween(1, 50) * 10000,
        ];
    }
}
