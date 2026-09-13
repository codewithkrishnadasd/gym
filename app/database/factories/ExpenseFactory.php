<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ExpenseStatus;
use App\Models\Expense;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Expense>
 */
class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'category' => fake()->randomElement(['equipment', 'utilities', 'maintenance', 'supplies']),
            'amount_minor' => fake()->numberBetween(500, 50000),
            'currency_code' => 'USD',
            'expense_date' => now()->toDateString(),
            'payee' => fake()->company(),
            'description' => fake()->sentence(),
            'created_by' => OrganisationUser::factory(),
            'status' => ExpenseStatus::Completed,
        ];
    }
}
