<?php

namespace Database\Factories;

use App\Models\Library;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Library>
 */
class LibraryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'    => User::factory(),
            'title'      => fake()->unique()->words(2, true),
            'is_default' => false,
        ];
    }
}
