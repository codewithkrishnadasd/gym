<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'task_category_id' => TaskCategory::factory(),
            'task_status_id' => null,
            'title' => fake()->sentence(4),
            'description' => null,
            'start_date' => null,
            'due_date' => null,
            'created_by' => OrganisationUser::factory(),
        ];
    }
}
