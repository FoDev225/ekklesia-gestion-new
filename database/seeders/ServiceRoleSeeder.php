<?php

namespace Database\Seeders;

use App\Models\ServiceRole;
use Illuminate\Database\Seeder;

class ServiceRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'Prédicateur',  'slug' => 'predicateur'],
            ['name' => 'Suppléant prédicateur', 'slug' => 'suppleant_predicateur'],
            ['name' => 'Président',    'slug' => 'president'],
            ['name' => 'Suppléant président', 'slug' => 'suppleant_president'],
            ['name' => 'Louange',      'slug' => 'louange'],
            ['name' => 'Annonceur',    'slug' => 'annonceur'],
        ];

        foreach ($roles as $role) {
            ServiceRole::firstOrCreate(['slug' => $role['slug']], $role);
        }

        $this->command->info('✅ Rôles de culte créés.');
    }
}