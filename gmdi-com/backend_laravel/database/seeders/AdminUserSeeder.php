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
            ['email' => 'communication@mairie.ci', 'name' => 'DIALLO Fatoumata',  'password' => 'Communication@2025!', 'role' => 'chef_communication'],
            ['email' => 'agent.com@mairie.ci',     'name' => 'KOUAMÉ Brice',       'password' => 'AgentCom@2025!',     'role' => 'agent_communication'],
            ['email' => 'rs@mairie.ci',            'name' => 'YAO Prisca',         'password' => 'ReseauxSociaux@2025!','role' => 'responsable_rs'],
            ['email' => 'admin@mairie.ci',         'name' => 'Admin GMDI',         'password' => 'Admin@2025!',        'role' => 'admin'],
        ];

        foreach ($comptes as $c) {
            $user = User::firstOrCreate(['email' => $c['email']], [
                'name'     => $c['name'],
                'password' => Hash::make($c['password']),
                'role'     => $c['role'],
            ]);
            $user->assignRole($c['role']);
        }

        $this->command->table(
            ['Email', 'Mot de passe', 'Rôle'],
            [
                ['communication@mairie.ci', 'Communication@2025!',  'chef_communication'],
                ['agent.com@mairie.ci',     'AgentCom@2025!',       'agent_communication'],
                ['rs@mairie.ci',            'ReseauxSociaux@2025!', 'responsable_rs'],
                ['admin@mairie.ci',         'Admin@2025!',          'admin'],
            ]
        );
    }
}
