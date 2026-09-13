<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\FinancialAccountStatus;
use App\Enums\FinancialAccountType;
use App\Models\FinancialAccount;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FinancialAccount>
 */
class FinancialAccountFactory extends Factory
{
    protected $model = FinancialAccount::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'name' => fake()->words(2, true).' Account',
            'account_type' => FinancialAccountType::Bank,
            'bank_name' => fake()->company(),
            'account_number_last4' => fake()->numerify('####'),
            'status' => FinancialAccountStatus::Active,
        ];
    }
}
