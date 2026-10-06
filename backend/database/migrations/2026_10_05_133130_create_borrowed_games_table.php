<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrowed_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->nullable()->constrained('users')->restrictOnDelete();
            $table->string('borrower_name')->nullable(); // required only to represent user with no account
            $table->foreignId('owned_game_id')->constrained('owned_games')->restrictOnDelete();
            $table->date('borrowed_date');
            $table->date('due_date')->nullable();
            $table->date('returned_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowed_games');
    }
};
