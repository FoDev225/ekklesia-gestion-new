<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['username' => 'admin.local'],  // ← chercher par username, pas email
            [
                'name'                 => 'Local Admin',
                'email'                => 'admin@eglise.local',
                'username'             => 'admin.local',
                'password'             => Hash::make('Admin@1234'),
                'is_active'            => true,
                'must_change_password' => false,  // ← l'admin n'a pas besoin de changer
            ]
        );

        $admin->assignRole('admin');

        $this->command->info('✅ Compte admin créé : admin@eglise.local/ admin.local / Admin@1234');
        $this->command->warn('⚠️  Pensez à changer le mot de passe en production !');
    }
}