<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SubscriptionStatus;
use App\Models\Club;
use App\Models\Member;
use App\Models\MemberSubscription;
use App\Models\Organisation;
use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MemberSubscription>
 */
class MemberSubscriptionFactory extends Factory
{
    protected $model = MemberSubscription::class;

    public function definition(): array
    {
        $start = fake()->dateTimeBetween('-1 month');

        return [
            'organisation_id' => Organisation::factory(),
            'member_id' => Member::factory(),
            'club_id' => Club::factory(),
            'plan_id' => Plan::factory(),
            'start_date' => $start->format('Y-m-d'),
            'end_date' => (clone $start)->modify('+30 days')->format('Y-m-d'),
            'amount_due_minor' => fake()->numberBetween(2000, 20000),
            'amount_paid_minor' => 0,
            'status' => SubscriptionStatus::Active,
        ];
    }
}
