<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string,
     */
    public function definition(): array
    {
        return [
            'body' => $this->faker->sentence(),
            'project_id' => Project::factory(),
            'completed' => false,
        ];
    }
}
