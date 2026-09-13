<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PlatformAdmin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<PlatformAdmin>
 */
class PlatformAdminFactory extends Factory
{
    protected $model = PlatformAdmin::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => (string) fake()->unique()->numberBetween(910000000000, 919999999999),
            'password' => static::$password ??= Hash::make('password'),
        ];
    }
}
