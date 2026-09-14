<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\InvoiceStatus;
use App\Models\Club;
use App\Models\Invoice;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $sequence = fake()->unique()->numberBetween(1, 999999);

        return [
            'organisation_id' => Organisation::factory(),
            'member_id' => Member::factory(),
            'club_id' => Club::factory(),
            'sequence' => $sequence,
            'number' => sprintf('INV-%s-%04d', now()->format('Y'), $sequence),
            'status' => InvoiceStatus::Issued,
            'issue_date' => now()->toDateString(),
            'due_date' => now()->addDays(7)->toDateString(),
            'currency_code' => 'INR',
            'total_minor' => 100000,
            'paid_minor' => 0,
            'created_by' => OrganisationUser::factory(),
        ];
    }
}
