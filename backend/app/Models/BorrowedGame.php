<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['borrower_id', 'borrower_name', 'owned_game_id', 'borrowed_date', 'due_date', 'returned_date'])]
class BorrowedGame extends Model
{
    use HasFactory;

    //not yet returned: BorrowedGame::active()->get()
    #[Scope]
    protected function active(Builder $query): void {
        $query->whereNull('returned_date');
    }

    public function borrower(): BelongsTo {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function ownedGame(): BelongsTo {
        return $this->belongsTo(OwnedGame::class);
    }

    public function isActive(): bool {
        return $this->returned_date === null;
    }
}
