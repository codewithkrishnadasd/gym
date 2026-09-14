<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\TaskCategory;
use App\Models\TaskStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskStatus>
 */
class TaskStatusFactory extends Factory
{
    protected $model = TaskStatus::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'task_category_id' => TaskCategory::factory(),
            'task_sub_category_id' => null,
            'name' => fake()->randomElement(['To do', 'In progress', 'Blocked', 'Done']),
            'color' => fake()->randomElement(TaskStatus::PALETTE),
            'completes' => false,
            'position' => 0,
        ];
    }

    public function completes(): static
    {
        return $this->state(['completes' => true, 'name' => 'Done', 'color' => '#047857']);
    }
}
