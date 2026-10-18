<?php

namespace App\GraphQL\Types\Library;

use App\Models\Library;
use App\Models\OwnedGame;
use Illuminate\Database\Eloquent\Collection;

final class OwnedGames
{
    /** @return Collection<int, OwnedGame> */
    public function __invoke(Library $root): Collection
    {
        // $root is the Library returned by the parent field (Query.library)
        return OwnedGame::where('library_id', $root->id)->get();
    }
}
