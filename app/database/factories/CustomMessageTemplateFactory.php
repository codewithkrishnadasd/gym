<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\CustomMessageTemplate;
use App\Models\Organisation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CustomMessageTemplate>
 */
class CustomMessageTemplateFactory extends Factory
{
    protected $model = CustomMessageTemplate::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'audience' => 'member',
            'name' => fake()->unique()->words(2, true),
            'body' => 'Hi {memberName}, a note from {organisationName}.',
        ];
    }
}
