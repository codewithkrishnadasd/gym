<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\NotificationActionType;
use App\Enums\NotificationEntityType;
use App\Enums\NotificationRecipientType;
use App\Enums\NotificationStatus;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\WhatsappActionNotification;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<WhatsappActionNotification>
 */
class WhatsappActionNotificationFactory extends Factory
{
    protected $model = WhatsappActionNotification::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'recipient_type' => NotificationRecipientType::Member,
            'recipient_id' => fake()->numberBetween(1, 1000),
            'recipient_name' => fake()->name(),
            'recipient_phone' => fake()->numerify('91##########'),
            'entity_type' => NotificationEntityType::Member,
            'entity_id' => fake()->numberBetween(1, 1000),
            'action_type' => NotificationActionType::MemberCreated,
            'message_snapshot' => 'Hi there, your profile has been created.',
            'status' => NotificationStatus::Ready,
            'created_by' => OrganisationUser::factory(),
            'operation_id' => (string) Str::uuid(),
        ];
    }
}
