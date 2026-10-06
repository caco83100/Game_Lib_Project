<?php

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

function createUser(): int
{
    return DB::table('users')->insertGetId([
        'pseudo'   => uniqid('user_'),
        'email'    => uniqid().'@example.test',
        'password' => 'secret',
    ]);
}

function createGame(): int
{
    return DB::table('games')->insertGetId([
        'title'     => 'game test',
        'platform'  => 'Super Game Console',
        'format'    => 'physical',
        'genre'     => 'RPG',
    ]);
}

function createLibrary(int $userId): int
{
    return DB::table('libraries')->insertGetId([
        'user_id'       => $userId,
        'title'         => 'lib test',
        'is_default'    => true,
    ]);
}

function createOwnedGame(): int
{
    $userId = createUser();
    $gameId = createGame();
    $libraryId = createLibrary($userId);

    return DB::table('owned_games')->insertGetId([
        'game_id' => $gameId,
        'library_id'=> $libraryId,
    ]);
}

it('allows the borrow of a free copy', function () {
    $copyId = createOwnedGame();
    $borrowerId = createUser();

    DB::table('borrowed_games')->insert([
        'owned_game_id' => $copyId,
        'borrower_id'   => $borrowerId,
        'borrowed_date' => '2026-10-01',
    ]);

    expect(DB::table('borrowed_games')->count())->toBe(1);
});

it('rejects two simultaneous borrow of the same copy', function () {
    $copyId = createOwnedGame();
    $firstBorrowerId = createUser();

    DB::table('borrowed_games')->insert([
        'owned_game_id' => $copyId,
        'borrower_id'   => $firstBorrowerId,
        'borrowed_date' => '2026-10-01',
    ]);

    $secondBorrowerId = createUser();

    expect(fn () => DB::table('borrowed_games')->insert([
        'owned_game_id' => $copyId,
        'borrower_id'   => $secondBorrowerId,
        'borrowed_date' => '2026-10-01',
    ]))->toThrow(QueryException::class);
});

it('allows a new borrow when the first one is returned', function () {
    $copyId = createOwnedGame();

    DB::table('borrowed_games')->insert([
        'owned_game_id' => $copyId,
        'borrower_id'   => createUser(),
        'borrowed_date' => '2026-10-01',
        'returned_date' => '2026-10-10',
    ]);

    DB::table('borrowed_games')->insert([
        'owned_game_id' => $copyId,
        'borrower_id'   => createUser(),
        'borrowed_date' => '2026-10-15',
    ]);

    expect(DB::table('borrowed_games')->where('owned_game_id', $copyId)->count())->toBe(2);
});

it('allows active borrows of two different copies', function () {
    $borrowerId = createUser();

    foreach ([createOwnedGame(), createOwnedGame()] as $copyId) {
        DB::table('borrowed_games')->insert([
            'owned_game_id' => $copyId,
            'borrower_id'   => $borrowerId,
            'borrowed_date' => '2026-10-01',
        ]);
    }

    expect(DB::table('borrowed_games')->whereNull('returned_date')->count())->toBe(2);
});
it('allows a borrow by a friend without account', function () {
    DB::table('borrowed_games')->insert([
        'owned_game_id' => createOwnedGame(),
        'borrower_name' => 'Paul',
        'borrowed_date' => '2026-10-01',
    ]);

    expect(DB::table('borrowed_games')->whereNull('borrower_id')->count())->toBe(1);
});

it('rejects a borrow with neither a borrower nor a borrower name', function () {
    $copyId = createOwnedGame();

    expect(fn () => DB::table('borrowed_games')->insert([
        'owned_game_id' => $copyId,
        'borrowed_date' => '2026-10-01',
    ]))->toThrow(QueryException::class);
});

it('rejects a return date before the borrow date', function () {
    $copyId = createOwnedGame();
    $borrowerId = createUser();

    expect(fn () => DB::table('borrowed_games')->insert([
        'owned_game_id' => $copyId,
        'borrower_id'   => $borrowerId,
        'borrowed_date' => '2026-10-10',
        'returned_date' => '2026-10-01',
    ]))->toThrow(QueryException::class);
});
