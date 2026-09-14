<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\TaskItem;
use App\Models\TaskSubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskItem>
 */
class TaskItemFactory extends Factory
{
    protected $model = TaskItem::class;

    public function definition(): array
    {
        return [
            'task_id' => Task::factory(),
            'task_sub_category_id' => TaskSubCategory::factory(),
            'task_status_id' => null,
            'position' => 0,
        ];
    }
}
