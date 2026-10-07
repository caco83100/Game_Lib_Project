<?php

namespace Database\Seeders;

use App\Models\Library;
use App\Models\OwnedGame;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'pseudo' => 'demo_user',
        ]);
        $library = Library::factory()->create([
            'user_id' => $user->id,
            'title' => 'Mes jeux',
            'is_default' => true,
        ]);
        OwnedGame::factory()->count(5)->create([
            'library_id' => $library->id,
        ]);
    }
}
