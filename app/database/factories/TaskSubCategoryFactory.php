<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Organisation;
use App\Models\TaskCategory;
use App\Models\TaskSubCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TaskSubCategory>
 */
class TaskSubCategoryFactory extends Factory
{
    protected $model = TaskSubCategory::class;

    public function definition(): array
    {
        return [
            'organisation_id' => Organisation::factory(),
            'task_category_id' => TaskCategory::factory(),
            'name' => fake()->randomElement(['Equipment check', 'Cleaning', 'Paperwork', 'Follow-up call']),
            'position' => 0,
        ];
    }
}
