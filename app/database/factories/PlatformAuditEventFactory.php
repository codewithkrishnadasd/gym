<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\PlatformAdmin;
use App\Models\PlatformAuditEvent;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PlatformAuditEvent>
 */
class PlatformAuditEventFactory extends Factory
{
    protected $model = PlatformAuditEvent::class;

    public function definition(): array
    {
        return [
            'actor_platform_admin_id' => PlatformAdmin::factory(),
            'action' => 'organisation.created',
            'entity_type' => 'organisation',
            'entity_id' => fake()->numberBetween(1, 1000),
            'metadata' => [],
        ];
    }
}
