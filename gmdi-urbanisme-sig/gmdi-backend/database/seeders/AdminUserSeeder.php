<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $comptes = [
            ['email' => 'urb@mairie.ci',       'name' => 'KONAN Rodrigue',   'password' => 'Urbanisme@2025!', 'role' => 'responsable_urbanisme'],
            ['email' => 'agent.urb@mairie.ci',  'name' => "BAMBA N'Guessan", 'password' => 'AgentUrb@2025!',  'role' => 'agent_urbanisme'],
            ['email' => 'instr.urb@mairie.ci',  'name' => 'YAO Marcelline',  'password' => 'Instr@2025!',     'role' => 'instructeur'],
            ['email' => 'admin@mairie.ci',       'name' => 'Admin GMDI',      'password' => 'Admin@2025!',     'role' => 'admin'],
        ];

        foreach ($comptes as $c) {
            $user = User::firstOrCreate(
                ['email' => $c['email']],
                ['name' => $c['name'], 'password' => Hash::make($c['password']), 'role' => $c['role']]
            );
            $user->syncRoles([$c['role']]);
        }

        $this->command->table(
            ['Email', 'Mot de passe', 'Rôle'],
            [
                ['urb@mairie.ci',       'Urbanisme@2025!', 'responsable_urbanisme'],
                ['agent.urb@mairie.ci', 'AgentUrb@2025!',  'agent_urbanisme'],
                ['instr.urb@mairie.ci', 'Instr@2025!',     'instructeur'],
                ['admin@mairie.ci',     'Admin@2025!',     'admin'],
            ]
        );
    }
}
