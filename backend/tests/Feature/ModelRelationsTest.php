<?php

use App\Models\BorrowedGame;
use App\Models\Game;
use App\Models\Library;
use App\Models\OwnedGame;
use App\Models\User;

it('lists the libraries of a user', function () {
    $user = User::factory()->create();
    Library::factory()->count(2)->create(['user_id' => $user->id]);

    expect($user->libraries)->toHaveCount(2);
});

it('gives a library its owner and its copies', function () {
    $library = Library::factory()->create();
    OwnedGame::factory()->count(2)->create(['library_id' => $library->id]);

    expect($library->ownedGames)->toHaveCount(2)
        ->and($library->user)->toBeInstanceOf(User::class);
});

it('links a copy to its game and its library', function () {
    $copy = OwnedGame::factory()->create();

    expect($copy->game)->toBeInstanceOf(Game::class)
        ->and($copy->library)->toBeInstanceOf(Library::class)
        ->and($copy->game->ownedGames->contains($copy))->toBeTrue();
});

it('finds the owner of a copy through its library', function () {
    $copy = OwnedGame::factory()->create();

    expect($copy->owner->is($copy->library->user))->toBeTrue();
});

it('lists the loans of a copy and of a borrower', function () {
    $loan = BorrowedGame::factory()->create();

    expect($loan->ownedGame->borrowedGames->contains($loan))->toBeTrue()
        ->and($loan->borrower->borrowedGames->contains($loan))->toBeTrue();
});

it('has no borrower user for a loan to a friend without account', function () {
    $loan = BorrowedGame::factory()->toGuest()->create();

    expect($loan->borrower)->toBeNull()
        ->and($loan->borrower_name)->not->toBeNull();
});

it('only returns active loans with the active scope', function () {
    $active = BorrowedGame::factory()->create();
    $returned = BorrowedGame::factory()->returned()->create();

    expect(BorrowedGame::active()->pluck('id')->all())->toBe([$active->id])
        ->and($active->isActive())->toBeTrue()
        ->and($returned->isActive())->toBeFalse();
});

it('does not let a client set the source of a game', function () {
    $game = Game::create(['title' => 'Zelda', 'platform' => 'Switch', 'source' => 'igdb']);

    expect($game->fresh()->source)->toBe('manual');
});
