<?php

use App\Models\Game;
use App\Models\Library;
use App\Models\OwnedGame;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

uses(MakesGraphQLRequests::class);

const LIBRARY_QUERY = '
    query ($id: ID!) {
        library(id: $id) {
            title
            owned_games { comment game { title platform } }
        }
    }
';

it('returns a library with its games', function () {
    $library = Library::factory()->create(['title' => 'Mes jeux']);
    $game = Game::factory()->create(['title' => 'Zelda', 'platform' => 'Switch']);
    OwnedGame::factory()->create(['library_id' => $library->id, 'game_id' => $game->id, 'comment' => 'Edition collector']);

    $this->graphQL(LIBRARY_QUERY, ['id' => $library->id])
        ->assertJsonPath('data.library.title', 'Mes jeux')
        ->assertJsonCount(1, 'data.library.owned_games')
        ->assertJsonPath('data.library.owned_games.0.comment', 'Edition collector')
        ->assertJsonPath('data.library.owned_games.0.game.title', 'Zelda')
        ->assertJsonPath('data.library.owned_games.0.game.platform', 'Switch');
});

it('returns only the games of the requested library', function () {
    $library = Library::factory()->create();
    $other = Library::factory()->create();
    OwnedGame::factory()->count(2)->create(['library_id' => $library->id]);
    OwnedGame::factory()->create(['library_id' => $other->id]);

    $this->graphQL(LIBRARY_QUERY, ['id' => $library->id])
        ->assertJsonCount(2, 'data.library.owned_games');
});

it('returns an empty list for a library without games', function () {
    $library = Library::factory()->create();

    $this->graphQL(LIBRARY_QUERY, ['id' => $library->id])
        ->assertJsonPath('data.library.owned_games', []);
});

it('returns null for an unknown library', function () {
    $this->graphQL(LIBRARY_QUERY, ['id' => 999999])
        ->assertJsonPath('data.library', null);
});
