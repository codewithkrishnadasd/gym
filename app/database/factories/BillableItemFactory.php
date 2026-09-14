<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\BillableItemStatus;
use App\Models\BillableItem;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BillableItem>
 */
class BillableItemFactory extends Factory
{
    protected $model = BillableItem::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'name' => fake()->randomElement(['Personal training', 'Locker rental', 'Protein shake', 'Towel service', 'Late fee']),
            'description' => null,
            'unit_price_minor' => fake()->numberBetween(5000, 200000),
            'status' => BillableItemStatus::Active,
        ];
    }
}
