<?php

namespace Database\Factories;

use App\Models\Game;
use App\Models\Library;
use App\Models\OwnedGame;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OwnedGame>
 */
class OwnedGameFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'game_id'    => Game::factory(),
            'library_id' => Library::factory(),
        ];
    }
}
