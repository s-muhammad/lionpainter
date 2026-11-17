<?php

namespace Database\Factories;

use App\Enums\TaskCategory;
use App\Enums\TaskStatus;
use App\Models\SeoTask;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SeoTask>
 */
class SeoTaskFactory extends Factory
{
    protected $model = SeoTask::class;

    public function definition(): array
    {
        return [
            'title' => fake()->unique()->sentence(4),
            'category' => fake()->randomElement(TaskCategory::cases()),
            'priority' => 2,
            'status' => TaskStatus::Todo,
            'phase' => fake()->randomElement(['هفته ۱', 'هفته ۲', 'ماه ۱', 'ماه ۲', 'ماهانه']),
            'due_date' => null,
            'keyword_id' => null,
            'notes' => null,
            'sort_order' => fake()->numberBetween(0, 20),
            'completed_at' => null,
        ];
    }

    public function done(): static
    {
        return $this->state(fn () => [
            'status' => TaskStatus::Done,
            'completed_at' => now(),
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn () => [
            'status' => TaskStatus::Todo,
            'due_date' => now()->subWeek()->toDateString(),
        ]);
    }
}
