<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\MembershipRole;
use App\Models\AuditEvent;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AuditEvent>
 */
class AuditEventFactory extends Factory
{
    protected $model = AuditEvent::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'actor_user_id' => OrganisationUser::factory(),
            'actor_role' => MembershipRole::Admin->value,
            'action' => 'member.updated',
            'entity_type' => 'member',
            'entity_id' => fake()->numberBetween(1, 1000),
            'metadata' => [],
        ];
    }
}
