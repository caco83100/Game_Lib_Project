<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable (['game_id', 'library_id', 'comment', 'completion_date'])]
class OwnedGame extends Model
{
    use HasFactory;

    public function game(): BelongsTo {
        return $this->belongsTo(Game::class);
    }

    public function library(): BelongsTo {
        return $this->belongsTo(Library::class);
    }

    public function borrowedGames(): HasMany {
        return $this->hasMany(BorrowedGame::class);
    }

    public function owner(): HasOneThrough {
        return $this->hasOneThrough(User::class, Library::class, 'id', 'id', 'library_id', 'user_id');
    }
}
