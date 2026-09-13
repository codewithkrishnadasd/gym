<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\OrganisationStatus;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Organisation>
 */
class OrganisationFactory extends Factory
{
    protected $model = Organisation::class;

    public function definition(): array
    {
        $name = fake()->unique()->company();

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.fake()->unique()->numerify('###'),
            'status' => OrganisationStatus::Active,
            'timezone' => 'UTC',
            'default_country_code' => 'IN',
            'currency_code' => 'USD',
            'locale' => 'en',
            'contact_email' => fake()->companyEmail(),
            'contact_phone' => fake()->numerify('+1##########'),
            'address' => ['line1' => fake()->streetAddress(), 'city' => fake()->city()],
            'terminology_member_singular' => 'Member',
            'terminology_member_plural' => 'Members',
            'terminology_user_singular' => 'Staff',
            'terminology_user_plural' => 'Staff',
            'terminology_club_singular' => 'Club',
            'terminology_club_plural' => 'Clubs',
        ];
    }
}
