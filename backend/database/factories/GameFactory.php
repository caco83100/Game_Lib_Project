<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Game>
 */
class GameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'    => fake()->words(3, true),
            'platform' => fake()->randomElement(['Switch', 'PS5', 'Xbox Series']),
            'format'   => 'physical',
        ];
    }
}
