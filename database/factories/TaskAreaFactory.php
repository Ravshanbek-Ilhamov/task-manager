<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Nette\Utils\Arrays;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TaskArea>
 */
class TaskAreaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'area_id' =>fake()->numberBetween(1,10),
            'task_id' =>fake()->numberBetween(1,20),
            'status' => Arr::random(['sent', 'opened', 'done', 'rejected', 'approved']),
        ];
    }
}
