<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'category_id' => fake()->numberBetween(1,10),
            'performer' => fake()->name(),
            'title' => fake()->sentence(),
            'file' => 'https://www.techsmith.com/blog/wp-content/uploads/2022/03/resize-image.png',
            'period' => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),

        ];
    }
}
