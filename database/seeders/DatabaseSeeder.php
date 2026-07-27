<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,      // 1. Créer rôles et permissions
            AdminUserSeeder::class, // 2. Créer le compte admin par défaut
            BelieverSeeder::class,   // 3. Créer les croyants et leurs relations
            ChurchSeeder::class,    // 4. Créer l'église (singleton)
        ]);
    }
}
