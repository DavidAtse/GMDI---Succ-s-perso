<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'voirie.read','voirie.write',
            'eclairage.read','eclairage.write',
            'eau.read','eau.write',
            'batiments.read','batiments.write',
            'interventions.read','interventions.write','interventions.assign','interventions.close',
            'maintenance.read','maintenance.write',
            'stats.read','stats.export',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'sanctum']);
        }

        $roles = [
            'directeur_technique' => $permissions,
            'chef_service'        => $permissions,
            'technicien'          => ['voirie.read','eclairage.read','eau.read','batiments.read','interventions.read','maintenance.read'],
            'admin'               => $permissions,
        ];

        foreach ($roles as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'sanctum']);
            $role->syncPermissions($perms);
        }
    }
}
