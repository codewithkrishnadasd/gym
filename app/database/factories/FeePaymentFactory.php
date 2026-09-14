<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ConfirmationStatus;
use App\Enums\PaymentMethod;
use App\Enums\WhatsappStatus;
use App\Models\Club;
use App\Models\FeePayment;
use App\Models\FinancialAccount;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FeePayment>
 */
class FeePaymentFactory extends Factory
{
    protected $model = FeePayment::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'club_id' => Club::factory(),
            'member_id' => Member::factory(),
            'payer_name' => fake()->name(),
            'amount_minor' => fake()->numberBetween(1000, 10000),
            'currency_code' => 'USD',
            'payment_method' => PaymentMethod::Cash,
            // Required on every payment. Derived from the organisation already
            // resolved above so the account never belongs to a different one.
            'financial_account_id' => fn (array $attributes): int => FinancialAccount::factory()
                ->create(['organisation_id' => $attributes['organisation_id']])
                ->id,
            'payment_date' => now()->toDateString(),
            'collected_by' => OrganisationUser::factory(),
            'confirmation_status' => ConfirmationStatus::PendingAdminConfirmation,
            'whatsapp_status' => WhatsappStatus::NotSent,
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn (array $attributes) => [
            'confirmation_status' => ConfirmationStatus::Confirmed,
            'confirmed_by' => OrganisationUser::factory(),
            'confirmed_at' => now(),
        ]);
    }
}
