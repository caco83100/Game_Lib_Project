<?php

namespace App\GraphQL\Types\OwnedGame;

use App\Models\Game;
use App\Models\OwnedGame;

final class GameOfCopy
{
    public function __invoke(OwnedGame $root): Game
    {
        // $root is the OwnedGame returned by the parent field (Library.owned_games)
        return Game::findOrFail($root->game_id);
    }
}
