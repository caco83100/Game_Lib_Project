<?php

namespace Database\Factories;

use App\Models\BorrowedGame;
use App\Models\OwnedGame;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<BorrowedGame>
 */
class BorrowedGameFactory extends Factory
{
    /**
     * Define the model's default state: an active loan to a registered user.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owned_game_id' => OwnedGame::factory(),
            'borrower_id'   => User::factory(),
            'borrowed_date' => now()->subDays(5)->toDateString(),
        ];
    }

    /**
     * A loan that has been given back.
     */
    public function returned(): static
    {
        return $this->state(fn () => ['returned_date' => now()->toDateString()]);
    }

    /**
     * A loan to a friend without account (free-text name instead of a user).
     */
    public function toGuest(): static
    {
        return $this->state(fn () => [
            'borrower_id'   => null,
            'borrower_name' => fake()->firstName(),
        ]);
    }
}
