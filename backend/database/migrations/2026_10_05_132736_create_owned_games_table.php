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
        Schema::create('owned_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id')->constrained()->restrictOnDelete();
            $table->foreignId('library_id')->constrained()->restrictOnDelete();// A non-empty library cannot be deleted, must moved games first.
            $table->string('comment')->nullable();
            $table->date('completion_date')->nullable();
            $table->timestamps();

            $table->unique(['library_id', 'game_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owned_games');
    }
};
