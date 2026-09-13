<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\DomainStatus;
use App\Models\Domain;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Domain>
 */
class DomainFactory extends Factory
{
    protected $model = Domain::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'hostname' => Str::lower(fake()->unique()->domainWord()).'.test',
            'status' => DomainStatus::Active,
            'is_primary' => true,
        ];
    }
}
