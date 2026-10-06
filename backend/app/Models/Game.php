<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

// `source` is deliberately not fillable: it is set by the app (manual / igdb), never by a client.
#[Fillable(['igdb_id', 'title', 'platform', 'format', 'genre', 'publisher', 'release_date', 'cover_url', 'summary'])]
class Game extends Model
{
    use HasFactory;

    public function ownedGames(): HasMany {
        return $this->hasMany(OwnedGame::class);
    }
}
