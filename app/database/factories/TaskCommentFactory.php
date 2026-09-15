<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\OrganisationUser;
use App\Models\Task;
use App\Models\TaskComment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskComment>
 */
class TaskCommentFactory extends Factory
{
    protected $model = TaskComment::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'organisation_user_id' => OrganisationUser::factory(),
            'body' => fake()->sentence(),
        ];
    }
}
