<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset du cache Spatie
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // -------------------------------------------------------
        // Permissions par module
        // -------------------------------------------------------
        $permissions = [
            // Fidèles
            'believers.view', 'believers.create', 'believers.edit', 'believers.delete',

            // Nouvelles personnes
            'newcomers.view', 'newcomers.create', 'newcomers.edit', 'newcomers.delete',

            // Cultes
            'services.view', 'services.create', 'services.edit', 'services.delete',

            // Groupes de louange
            'groups.view', 'groups.create', 'groups.edit', 'groups.delete',

            // Équipes
            'teams.view', 'teams.create', 'teams.edit', 'teams.delete',

            // Activités
            'activities.view', 'activities.create', 'activities.edit', 'activities.delete',

            // Présences
            'attendances.view', 'attendances.create', 'attendances.edit',

            // Finances
            'finances.view', 'finances.create', 'finances.edit', 'finances.delete',

            // Budgets
            'budgets.view', 'budgets.create', 'budgets.edit',

            // Rapports
            'reports.view',

            // Utilisateurs
            'users.view', 'users.create', 'users.edit', 'users.delete',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // -------------------------------------------------------
        // Rôles + attribution des permissions
        // -------------------------------------------------------

        // ADMIN — accès total
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // PASTEUR — vue globale, lecture sur tout, pas de suppression
        $pasteur = Role::firstOrCreate(['name' => 'pasteur']);
        $pasteur->givePermissionTo([
            'believers.view',
            'newcomers.view',
            'services.view',
            'groups.view',
            'teams.view',
            'activities.view',
            'attendances.view',
            'finances.view',
            'budgets.view',
            'reports.view',
        ]);

        // SECRÉTARIAT — gestion des fidèles et nouvelles personnes
        $secretariat = Role::firstOrCreate(['name' => 'secretariat']);
        $secretariat->givePermissionTo([
            'believers.view', 'believers.create', 'believers.edit',
            'newcomers.view', 'newcomers.create', 'newcomers.edit',
            'services.view',
            'attendances.view', 'attendances.create', 'attendances.edit',
            'reports.view',
        ]);

        // RESPONSABLE CULTE — gestion des cultes et groupes
        $respCulte = Role::firstOrCreate(['name' => 'dir_culte']);
        $respCulte->givePermissionTo([
            'believers.view',
            'services.view', 'services.create', 'services.edit',
            'groups.view', 'groups.create', 'groups.edit',
            'attendances.view', 'attendances.create', 'attendances.edit',
        ]);

        // RESPONSABLE JEUNES (J-AEBECI)
        $respJeunes = Role::firstOrCreate(['name' => 'jaebeci']);
        $respJeunes->givePermissionTo([
            'believers.view',
            'activities.view', 'activities.create', 'activities.edit',
            'attendances.view', 'attendances.create', 'attendances.edit',
            'budgets.view',
            'reports.view',
        ]);

        // RESPONSABLE FEMMES (AFEBECI)
        $respFemmes = Role::firstOrCreate(['name' => 'afebeci']);
        $respFemmes->givePermissionTo([
            'believers.view',
            'activities.view', 'activities.create', 'activities.edit',
            'attendances.view', 'attendances.create', 'attendances.edit',
            'budgets.view',
            'reports.view',
        ]);

        // RESPONSABLE ECODIM
        $respEcodim = Role::firstOrCreate(['name' => 'dir_ecodim']);
        $respEcodim->givePermissionTo([
            'believers.view',
            'activities.view', 'activities.create', 'activities.edit',
            'attendances.view', 'attendances.create', 'attendances.edit',
            'budgets.view',
            'reports.view',
        ]);

        $this->command->info('✅ Rôles et permissions créés avec succès.');
    }
}