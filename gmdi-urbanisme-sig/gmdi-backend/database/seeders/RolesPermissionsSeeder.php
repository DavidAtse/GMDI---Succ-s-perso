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
            'foncier.view','foncier.create','foncier.update',
            'permis.view','permis.create','permis.update','permis.decider',
            'sig.view','sig.create','sig.update',
            'projets.view','projets.create','projets.update',
            'equipements.view','equipements.create','equipements.update',
            'stats.view','stats.export',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'sanctum']);
        }

        $resp = Role::firstOrCreate(['name' => 'responsable_urbanisme', 'guard_name' => 'sanctum']);
        $resp->syncPermissions($perms);

        $agent = Role::firstOrCreate(['name' => 'agent_urbanisme', 'guard_name' => 'sanctum']);
        $agent->syncPermissions(array_filter($perms, fn($p) => !str_contains($p, 'export') && !str_contains($p, 'decider')));

        $instr = Role::firstOrCreate(['name' => 'instructeur', 'guard_name' => 'sanctum']);
        $instr->syncPermissions(['permis.view','permis.update','permis.decider','stats.view']);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $admin->syncPermissions($perms);

        $this->command->info('✓ Rôles et permissions créés.');
    }
}
