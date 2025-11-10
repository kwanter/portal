<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Application>
 */
class ApplicationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->words(3, true),
            "url" => fake()->url(),
            "description" => fake()->sentence(20),
            "category" => fake()->randomElement([
                "kesekretariatan",
                "kepaniteraan",
            ]),
            "created_by" => User::factory(),
        ];
    }
}
