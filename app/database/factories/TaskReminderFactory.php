<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskReminder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskReminder>
 */
class TaskReminderFactory extends Factory
{
    protected $model = TaskReminder::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'label' => fake()->sentence(3),
            'remind_on' => fake()->dateTimeBetween('-3 days', '+10 days')->format('Y-m-d'),
            'created_by' => OrganisationUser::factory(),
        ];
    }
}
