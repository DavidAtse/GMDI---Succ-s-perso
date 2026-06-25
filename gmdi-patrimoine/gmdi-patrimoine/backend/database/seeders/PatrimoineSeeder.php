<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Bien;
use App\Models\Vehicule;
use App\Models\Terrain;
use App\Models\Amortissement;
use App\Models\Entretien;
use App\Models\Reparation;
use Illuminate\Support\Facades\Hash;

// ─────────────────────────────────────────────────────────────────────────────
//  RolesPermissionsSeeder
// ─────────────────────────────────────────────────────────────────────────────

class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            'biens.view', 'biens.create', 'biens.update', 'biens.delete',
            'vehicules.view', 'vehicules.create', 'vehicules.update',
            'terrains.view', 'terrains.create', 'terrains.update',
            'mobilier.view', 'mobilier.create',
            'informatique.view', 'informatique.create',
            'equipements.view', 'equipements.create',
            'affectations.view', 'affectations.create',
            'entretiens.view', 'entretiens.create', 'entretiens.validate',
            'reparations.view', 'reparations.create', 'reparations.resolve',
            'amortissements.view',
            'stats.view', 'stats.export',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'sanctum']);
        }

        $chef = Role::firstOrCreate(['name' => 'chef_patrimoine', 'guard_name' => 'sanctum']);
        $chef->syncPermissions($perms);

        $agent = Role::firstOrCreate(['name' => 'agent_patrimoine', 'guard_name' => 'sanctum']);
        $agent->syncPermissions(array_filter($perms, fn($p) => !str_ends_with($p, '.delete') && !str_ends_with($p, '.export')));

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $admin->syncPermissions($perms);

    }
}

// ─────────────────────────────────────────────────────────────────────────────
//  AdminUserSeeder
// ─────────────────────────────────────────────────────────────────────────────

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $chef = User::firstOrCreate(['email' => 'patrimoine@mairie.ci'], [
            'name' => "N'GUESSAN Kouamé", 'password' => Hash::make('Patrimoine@2025!'), 'role' => 'chef_patrimoine',
        ]);
        $chef->assignRole('chef_patrimoine');

        $agent = User::firstOrCreate(['email' => 'agent.pat@mairie.ci'], [
            'name' => 'KONÉ Adama', 'password' => Hash::make('Agent@2025!'), 'role' => 'agent_patrimoine',
        ]);
        $agent->assignRole('agent_patrimoine');

        $admin = User::firstOrCreate(['email' => 'admin@mairie.ci'], [
            'name' => 'Admin GMDI', 'password' => Hash::make('Admin@2025!'), 'role' => 'admin',
        ]);
        $admin->assignRole('admin');

    }
}

// ─────────────────────────────────────────────────────────────────────────────
//  PatrimoineSeeder — données de démonstration fidèles au HTML
// ─────────────────────────────────────────────────────────────────────────────

class PatrimoineSeeder extends Seeder
{
    public function run(): void
    {
        // ── Rôles, permissions et comptes ─────────────────────────────────────
        (new RolesPermissionsSeeder())->run();
        (new AdminUserSeeder())->run();

        // ── Biens ─────────────────────────────────────────────────────────────
        $biens = [
            ['reference'=>'PAT-IMM-001','designation'=>"Hôtel de Ville",'categorie'=>'immobilier','localisation'=>'Avenue Principale','superficie'=>1200,'valeur_acquisition'=>850000000,'valeur_actuelle'=>920000000,'date_acquisition'=>'1995-01-01','affectation'=>'Administration communale','statut'=>'occupe','taux_amortissement'=>2,'qr_code'=>'QR-PAT-IMM-001'],
            ['reference'=>'PAT-VEH-001','designation'=>'Toyota Land Cruiser 200','categorie'=>'vehicule','localisation'=>'Garage communal','valeur_acquisition'=>45000000,'valeur_actuelle'=>32000000,'date_acquisition'=>'2020-07-15','affectation'=>'Cabinet du Maire','statut'=>'occupe','taux_amortissement'=>20,'qr_code'=>'QR-PAT-VEH-001'],
            ['reference'=>'PAT-EQP-001','designation'=>'Groupe électrogène 250 KVA','categorie'=>'equipement','localisation'=>'HdV — local technique','valeur_acquisition'=>18000000,'valeur_actuelle'=>12000000,'date_acquisition'=>'2019-03-20','affectation'=>'Alimentation secours HdV','statut'=>'en_maintenance','taux_amortissement'=>10,'qr_code'=>'QR-PAT-EQP-001'],
            ['reference'=>'PAT-TER-001','designation'=>'Terrain futur complexe sportif','categorie'=>'terrain','localisation'=>'Quartier Nord-Est','superficie'=>8000,'valeur_acquisition'=>320000000,'valeur_actuelle'=>450000000,'date_acquisition'=>'2002-05-10','affectation'=>'Projet complexe sportif','statut'=>'disponible','taux_amortissement'=>0,'qr_code'=>'QR-PAT-TER-001'],
            ['reference'=>'PAT-IMM-002','designation'=>'Marché Municipal Central','categorie'=>'immobilier','localisation'=>'Quartier Centre-Ville','superficie'=>3500,'valeur_acquisition'=>420000000,'valeur_actuelle'=>510000000,'date_acquisition'=>'1988-01-01','affectation'=>'Activités commerciales','statut'=>'loue','taux_amortissement'=>2,'qr_code'=>'QR-PAT-IMM-002'],
            ['reference'=>'PAT-INF-001','designation'=>'Parc informatique Salle Conseil','categorie'=>'informatique','localisation'=>'HdV — Salle du Conseil','valeur_acquisition'=>8500000,'valeur_actuelle'=>5000000,'date_acquisition'=>'2022-01-10','affectation'=>'Salle du Conseil Municipal','statut'=>'occupe','taux_amortissement'=>33,'qr_code'=>'QR-PAT-INF-001'],
            ['reference'=>'PAT-MOB-001','designation'=>'Mobilier bureau direction générale','categorie'=>'mobilier','localisation'=>'HdV — Direction Générale','valeur_acquisition'=>3200000,'valeur_actuelle'=>2000000,'date_acquisition'=>'2020-03-01','affectation'=>'Direction Générale','statut'=>'occupe','taux_amortissement'=>20,'qr_code'=>'QR-PAT-MOB-001'],
        ];

        foreach ($biens as $b) {
            Bien::firstOrCreate(['reference' => $b['reference']], array_merge($b, ['created_at' => now(), 'updated_at' => now()]));
        }

        // ── Terrains ──────────────────────────────────────────────────────────
        Terrain::firstOrCreate(['localisation' => 'Quartier Nord-Est'], ['superficie'=>8000,'valeur'=>450000000,'usage'=>'Projet complexe sportif','titre_foncier'=>'TF-2002-045','statut'=>'Reserve','created_at'=>now(),'updated_at'=>now()]);
        Terrain::firstOrCreate(['localisation' => 'Zone industrielle Port-Bouët'], ['superficie'=>12000,'valeur'=>380000000,'usage'=>'Projet infrastructure','titre_foncier'=>'TF-1998-012','statut'=>'Reserve','created_at'=>now(),'updated_at'=>now()]);
        Terrain::firstOrCreate(['localisation' => 'Av. Principale — parking mairie'], ['superficie'=>2500,'valeur'=>150000000,'usage'=>'Voirie / stationnement','titre_foncier'=>'TF-2005-088','statut'=>'Occupé','created_at'=>now(),'updated_at'=>now()]);

        // ── Entretiens ────────────────────────────────────────────────────────
        Entretien::firstOrCreate(['bien'=>'Toyota Land Cruiser 200','date_prevue'=>'2025-06-15'], ['type_entretien'=>'Vidange + révision','periodicite'=>'Trimestrielle','cout_estime'=>180000,'statut'=>'programme','created_at'=>now(),'updated_at'=>now()]);
        Entretien::firstOrCreate(['bien'=>'Groupe électrogène 250 KVA','date_prevue'=>'2025-05-27'], ['type_entretien'=>'Inspection générale','periodicite'=>'Mensuelle','cout_estime'=>95000,'statut'=>'urgent','created_at'=>now(),'updated_at'=>now()]);
        Entretien::firstOrCreate(['bien'=>'Camion benne N°1','date_prevue'=>'2025-06-01'], ['type_entretien'=>'Contrôle technique','periodicite'=>'Semestrielle','cout_estime'=>250000,'statut'=>'programme','created_at'=>now(),'updated_at'=>now()]);

        // ── Réparations ───────────────────────────────────────────────────────
        Reparation::firstOrCreate(['bien'=>'Groupe électrogène 250 KVA','description'=>'Panne démarreur'], ['priorite'=>'urgente','prestataire'=>'DIABATÉ Moussa','cout_estime'=>95000,'statut'=>'en_cours','created_at'=>now(),'updated_at'=>now()]);
        Reparation::firstOrCreate(['bien'=>'Camion benne N°2','description'=>'Fuite hydraulique circuit freinage'], ['priorite'=>'haute','prestataire'=>'KOFFI Marcel','cout_estime'=>120000,'statut'=>'en_cours','created_at'=>now(),'updated_at'=>now()]);

        // ── Amortissements ────────────────────────────────────────────────────
        $amorts = [
            ['bien'=>'Toyota Land Cruiser 200','valeur_acquisition'=>45000000,'taux_annuel'=>20,'annee_debut'=>2020,'amortissement_cumule'=>27000000,'valeur_nette_comptable'=>18000000],
            ['bien'=>'Groupe électrogène 250 KVA','valeur_acquisition'=>18000000,'taux_annuel'=>10,'annee_debut'=>2019,'amortissement_cumule'=>10800000,'valeur_nette_comptable'=>7200000],
            ['bien'=>'Parc informatique Conseil','valeur_acquisition'=>8500000,'taux_annuel'=>33,'annee_debut'=>2022,'amortissement_cumule'=>8500000,'valeur_nette_comptable'=>0],
            ['bien'=>'Mobilier bureau DG','valeur_acquisition'=>3200000,'taux_annuel'=>20,'annee_debut'=>2020,'amortissement_cumule'=>1600000,'valeur_nette_comptable'=>1600000],
            ['bien'=>"Hôtel de Ville",'valeur_acquisition'=>850000000,'taux_annuel'=>2,'annee_debut'=>1995,'amortissement_cumule'=>34000000,'valeur_nette_comptable'=>816000000],
            ['bien'=>'Marché Municipal Central','valeur_acquisition'=>420000000,'taux_annuel'=>2,'annee_debut'=>1988,'amortissement_cumule'=>75000000,'valeur_nette_comptable'=>345000000],
        ];

        foreach ($amorts as $a) {
            Amortissement::firstOrCreate(['bien' => $a['bien']], array_merge($a, ['created_at' => now(), 'updated_at' => now()]));
        }

    }
}
