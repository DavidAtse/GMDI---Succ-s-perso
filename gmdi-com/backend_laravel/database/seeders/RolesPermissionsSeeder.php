<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'actualites.view','actualites.create','actualites.update','actualites.delete',
            'reseaux.view','reseaux.create','reseaux.publier',
            'relations.view','relations.create',
            'documents.view','documents.create',
            'reclamations.view','reclamations.create','reclamations.traiter',
            'suggestions.view','suggestions.create','suggestions.transmettre',
            'consultations.view','consultations.create',
            'sms.view','sms.envoyer','sms.export',
            'stats.view',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'sanctum']);
        }

        $chef = Role::firstOrCreate(['name' => 'chef_communication', 'guard_name' => 'sanctum']);
        $chef->syncPermissions($perms);

        $agent = Role::firstOrCreate(['name' => 'agent_communication', 'guard_name' => 'sanctum']);
        $agent->syncPermissions(array_filter($perms, fn($p) => !str_ends_with($p, '.delete') && !str_ends_with($p, '.export')));

        $rs = Role::firstOrCreate(['name' => 'responsable_rs', 'guard_name' => 'sanctum']);
        $rs->syncPermissions(['reseaux.view','reseaux.create','reseaux.publier','actualites.view','stats.view']);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $admin->syncPermissions($perms);

        $this->command->info('Rôles et permissions créés.');
    }
}
