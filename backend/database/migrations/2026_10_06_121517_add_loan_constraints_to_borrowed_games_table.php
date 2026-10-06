<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Business rules the Schema Builder cannot express: enforced by PostgreSQL itself,
     * so they hold even for concurrent requests.
     */
    public function up(): void
    {
        // A copy can have only one active (not yet returned) loan at a time.
        DB::statement(
            'CREATE UNIQUE INDEX borrowed_games_one_active_loan_per_copy
             ON borrowed_games (owned_game_id)
             WHERE returned_date IS NULL'
        );

        // The borrower is either a registered user or a free-text name (friend without account).
        DB::statement(
            'ALTER TABLE borrowed_games
             ADD CONSTRAINT borrowed_games_borrower_identified
             CHECK (borrower_id IS NOT NULL OR borrower_name IS NOT NULL)'
        );

        // A game cannot be returned before it was borrowed.
        DB::statement(
            'ALTER TABLE borrowed_games
             ADD CONSTRAINT borrowed_games_return_after_borrow
             CHECK (returned_date IS NULL OR returned_date >= borrowed_date)'
        );
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE borrowed_games DROP CONSTRAINT borrowed_games_return_after_borrow');
        DB::statement('ALTER TABLE borrowed_games DROP CONSTRAINT borrowed_games_borrower_identified');
        DB::statement('DROP INDEX borrowed_games_one_active_loan_per_copy');
    }
};
