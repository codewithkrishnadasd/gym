<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\ClubStatus;
use App\Models\Club;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Club>
 */
class ClubFactory extends Factory
{
    protected $model = Club::class;

    public function definition(): array
    {
        $name = fake()->city().' Club';

        return [
            'organisation_id' => Organisation::factory(),
            'name' => $name,
            'code' => Str::upper(Str::random(4)),
            'status' => ClubStatus::Active,
            'phone' => fake()->numerify('+1##########'),
            'email' => fake()->companyEmail(),
            'address' => ['line1' => fake()->streetAddress()],
            'timezone' => 'UTC',
            'opening_hours' => ['mon_fri' => '06:00-22:00', 'sat_sun' => '08:00-20:00'],
        ];
    }
}
