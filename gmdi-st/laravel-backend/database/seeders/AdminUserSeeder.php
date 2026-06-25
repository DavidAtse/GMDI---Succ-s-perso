<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Directeur ST',  'email' => 'dst@mairie.ci',        'password' => 'DirecteurST@2025!', 'role' => 'directeur_technique'],
            ['name' => 'Chef Service',  'email' => 'chef.st@mairie.ci',    'password' => 'ChefST@2025!',      'role' => 'chef_service'],
            ['name' => 'Technicien ST', 'email' => 'tech.st@mairie.ci',    'password' => 'TechST@2025!',      'role' => 'technicien'],
            ['name' => 'Administrateur','email' => 'admin@mairie.ci',       'password' => 'Admin@2025!',       'role' => 'admin'],
        ];

        foreach ($users as $u) {
            $user = User::firstOrCreate(
                ['email' => $u['email']],
                ['name' => $u['name'], 'password' => Hash::make($u['password']), 'role' => $u['role']]
            );
            $user->syncRoles([$u['role']]);
        }
    }
}
