<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\RouteVoirie;
use App\Models\Lampadaire;
use App\Models\Caniveau;
use App\Models\BatimentCommunal;
use App\Models\DemandeIntervention;
use App\Models\BonTravail;
use App\Models\Equipe;
use App\Models\PlanningMaintenance;
use App\Models\MaintenanceCorrective;
use App\Models\PanneEclairage;
use App\Models\ReparationVoirie;
use Illuminate\Support\Facades\Hash;

// ─────────────────────────────────────────────────────────────────────────────
//  RolesPermissionsSeeder
// ─────────────────────────────────────────────────────────────────────────────
class RolesPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $perms = [
            // Voirie
            'voirie.view','voirie.create','voirie.update',
            // Éclairage
            'eclairage.view','eclairage.create','eclairage.update',
            // Eau
            'eau.view','eau.create','eau.update',
            // Bâtiments
            'batiments.view','batiments.create','batiments.update',
            // Interventions
            'interventions.view','interventions.create',
            'interventions.assigner','interventions.cloturer',
            // Maintenance
            'maintenance.view','maintenance.create','maintenance.valider',
            // Stats
            'stats.view','stats.export',
        ];

        foreach ($perms as $p) {
            Permission::firstOrCreate(['name' => $p, 'guard_name' => 'sanctum']);
        }

        // Directeur technique : tout
        $dir = Role::firstOrCreate(['name' => 'directeur_technique', 'guard_name' => 'sanctum']);
        $dir->syncPermissions($perms);

        // Chef de service : pas export
        $chef = Role::firstOrCreate(['name' => 'chef_service', 'guard_name' => 'sanctum']);
        $chef->syncPermissions(array_filter($perms, fn($p) => !str_ends_with($p, '.export')));

        // Technicien : view + create sur son domaine
        $tech = Role::firstOrCreate(['name' => 'technicien', 'guard_name' => 'sanctum']);
        $tech->syncPermissions(array_filter($perms, fn($p) => str_ends_with($p, '.view') || str_ends_with($p, '.create')));

        // Admin : tout
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'sanctum']);
        $admin->syncPermissions($perms);

        $this->command->info('Rôles et permissions Services Techniques créés.');
    }
}

// ─────────────────────────────────────────────────────────────────────────────
//  AdminUserSeeder
// ─────────────────────────────────────────────────────────────────────────────
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $comptes = [
            ['email'=>'dst@mairie.ci',     'name'=>"COULIBALY Moussa",  'password'=>'DirecteurST@2025!', 'role'=>'directeur_technique'],
            ['email'=>'chef.st@mairie.ci', 'name'=>"N'GORAN Koffi",     'password'=>'ChefST@2025!',      'role'=>'chef_service'],
            ['email'=>'tech.st@mairie.ci', 'name'=>'DIABY Sékou',       'password'=>'TechST@2025!',      'role'=>'technicien'],
            ['email'=>'admin@mairie.ci',   'name'=>'Admin GMDI',        'password'=>'Admin@2025!',       'role'=>'admin'],
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
                ['dst@mairie.ci',     'DirecteurST@2025!', 'directeur_technique'],
                ['chef.st@mairie.ci', 'ChefST@2025!',      'chef_service'],
                ['tech.st@mairie.ci', 'TechST@2025!',      'technicien'],
                ['admin@mairie.ci',   'Admin@2025!',        'admin'],
            ]
        );
    }
}

// ─────────────────────────────────────────────────────────────────────────────
//  ServicesTechniquesSeeder — données de démonstration
// ─────────────────────────────────────────────────────────────────────────────
class ServicesTechniquesSeeder extends Seeder
{
    public function run(): void
    {
        // ── Routes voirie ─────────────────────────────────────────────────────
        $routes = [
            ['nom'=>'Avenue de la Paix','quartier'=>'Centre','longueur'=>2800,'type'=>'bitumee','etat'=>'bon','date_dernier_entretien'=>'2025-03-15'],
            ['nom'=>'Rue du Commerce','quartier'=>'Plateau','longueur'=>1200,'type'=>'bitumee','etat'=>'moyen','date_dernier_entretien'=>'2024-11-10'],
            ['nom'=>"Boulevard de l'Indépendance",'quartier'=>'Adjamé','longueur'=>4500,'type'=>'bitumee','etat'=>'bon','date_dernier_entretien'=>'2025-01-20'],
            ['nom'=>'Rue du Marché','quartier'=>'Treichville','longueur'=>800,'type'=>'bitumee','etat'=>'degrade','date_dernier_entretien'=>'2024-06-05'],
            ['nom'=>'Voie de desserte lotissement 15','quartier'=>'Yopougon','longueur'=>650,'type'=>'laterite','etat'=>'critique'],
            ['nom'=>'Piste rurale secteur Nord','quartier'=>'Abobo','longueur'=>3200,'type'=>'piste','etat'=>'moyen'],
        ];
        foreach ($routes as $r) {
            RouteVoirie::firstOrCreate(['nom' => $r['nom']], array_merge($r, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Lampadaires ───────────────────────────────────────────────────────
        $lampadaires = [
            ['reference'=>'ECL-001','localisation'=>'Av. de la Paix — Carrefour Mairie','quartier'=>'Centre','type_lampe'=>'LED','puissance'=>150,'statut'=>'fonctionnel','date_posee'=>'2022-06-01'],
            ['reference'=>'ECL-002','localisation'=>'Rue du Commerce N°45','quartier'=>'Plateau','type_lampe'=>'Sodium','puissance'=>250,'statut'=>'en_panne','date_posee'=>'2019-03-15'],
            ['reference'=>'ECL-003','localisation'=>'Bd Indépendance — Point km 2','quartier'=>'Adjamé','type_lampe'=>'LED','puissance'=>150,'statut'=>'fonctionnel','date_posee'=>'2023-01-10'],
            ['reference'=>'ECL-004','localisation'=>'Marché Central — Entrée Nord','quartier'=>'Treichville','type_lampe'=>'LED','puissance'=>200,'statut'=>'fonctionnel','date_posee'=>'2023-08-20'],
            ['reference'=>'ECL-005','localisation'=>'Carrefour Yopougon Selmer','quartier'=>'Yopougon','type_lampe'=>'Sodium','puissance'=>250,'statut'=>'en_maintenance'],
        ];
        foreach ($lampadaires as $l) {
            Lampadaire::firstOrCreate(['reference' => $l['reference']], array_merge($l, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Pannes éclairage ──────────────────────────────────────────────────
        PanneEclairage::firstOrCreate(['reference'=>'PAN-2025-0012'], ['localisation'=>'Rue du Commerce N°45','description'=>'Lampadaire éteint depuis 3 jours — câble arraché','date_signalement'=>'2025-05-18','statut'=>'signalee','created_at'=>now(),'updated_at'=>now()]);
        PanneEclairage::firstOrCreate(['reference'=>'PAN-2025-0011'], ['localisation'=>'Carrefour Yopougon Selmer','description'=>'Clignotement intermittent — ballast défectueux','date_signalement'=>'2025-05-15','technicien'=>'DIABY Sékou','statut'=>'en_intervention','created_at'=>now(),'updated_at'=>now()]);

        // ── Caniveaux ─────────────────────────────────────────────────────────
        $caniveaux = [
            ['localisation'=>'Av. de la Paix côté gauche','quartier'=>'Centre','longueur'=>1400,'etat'=>'bon','date_dernier_nettoyage'=>'2025-04-10'],
            ['localisation'=>'Rue du Marché — section Treichville','quartier'=>'Treichville','longueur'=>400,'etat'=>'colmate'],
            ['localisation'=>'Lotissement 15 côté Nord','quartier'=>'Yopougon','longueur'=>650,'etat'=>'degrade'],
            ['localisation'=>'Bd Indépendance km 3','quartier'=>'Adjamé','longueur'=>900,'etat'=>'bon','date_dernier_nettoyage'=>'2025-03-20'],
        ];
        foreach ($caniveaux as $c) {
            Caniveau::firstOrCreate(['localisation' => $c['localisation']], array_merge($c, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Bâtiments communaux ───────────────────────────────────────────────
        $batiments = [
            ['nom'=>'Hôtel de Ville','type'=>'mairie','adresse'=>'Avenue Principale — Centre','superficie'=>1200,'annee_construction'=>1995,'etat'=>'bon','responsable'=>"N'GORAN Koffi"],
            ['nom'=>'Annexe Mairie Yopougon','type'=>'mairie','adresse'=>'Quartier Selmer, Yopougon','superficie'=>450,'annee_construction'=>2008,'etat'=>'moyen','responsable'=>'KONE Adama'],
            ['nom'=>'École Primaire Cocody 12','type'=>'ecole','adresse'=>'Rue des Palmiers, Cocody','superficie'=>800,'annee_construction'=>1985,'etat'=>'moyen'],
            ['nom'=>'École Primaire Abobo Centre','type'=>'ecole','adresse'=>'Av. Félix Houphouët, Abobo','superficie'=>650,'annee_construction'=>1978,'etat'=>'degrade'],
            ['nom'=>'Centre Social Adjamé','type'=>'centre_social','adresse'=>'Quartier Saint-Michel, Adjamé','superficie'=>350,'annee_construction'=>2001,'etat'=>'bon'],
            ['nom'=>'Marché Municipal Central','type'=>'marche','adresse'=>'Quartier Centre-Ville','superficie'=>3500,'annee_construction'=>1988,'etat'=>'moyen','responsable'=>'YAPI Marcel'],
            ['nom'=>'Marché de Treichville','type'=>'marche','adresse'=>'Avenue 13, Treichville','superficie'=>2100,'annee_construction'=>1975,'etat'=>'degrade'],
        ];
        foreach ($batiments as $b) {
            BatimentCommunal::firstOrCreate(['nom' => $b['nom']], array_merge($b, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Demandes citoyennes ───────────────────────────────────────────────
        $demandes = [
            ['reference'=>'DI-2025-000024','type_service'=>'voirie','description'=>'Nid de poule dangereux à hauteur du carrefour principal','localisation'=>'Rue du Marché — Carrefour Central','demandeur'=>'KOUA Amédée','telephone'=>'07 11 22 33','date_depot'=>'2025-05-20','priorite'=>'urgente','statut'=>'assignee','assigne_a'=>"N'GORAN Koffi"],
            ['reference'=>'DI-2025-000023','type_service'=>'eclairage','description'=>'Éclairage public absent depuis une semaine sur 300m','localisation'=>'Rue du Commerce — section Est','demandeur'=>'Association Riverains Plateau','date_depot'=>'2025-05-18','priorite'=>'haute','statut'=>'en_cours'],
            ['reference'=>'DI-2025-000022','type_service'=>'eau','description'=>'Caniveau colmaté causing inondations récurrentes','localisation'=>'Lotissement 15 — entrée','demandeur'=>'BAMBA Salif','date_depot'=>'2025-05-15','priorite'=>'haute','statut'=>'ouverte'],
            ['reference'=>'DI-2025-000021','type_service'=>'batiment','description'=>"Toiture école endommagée — infiltrations d'eau",'localisation'=>'École Primaire Abobo Centre','demandeur'=>'Directeur Établissement','date_depot'=>'2025-05-10','priorite'=>'urgente','statut'=>'cloturee','date_resolution'=>'2025-05-22'],
        ];
        foreach ($demandes as $d) {
            DemandeIntervention::firstOrCreate(['reference' => $d['reference']], array_merge($d, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Équipes terrain ───────────────────────────────────────────────────
        $equipes = [
            ['nom'=>'Brigade Voirie Nord','chef'=>"N'GORAN Koffi",'membres'=>6,'statut'=>'en_intervention','bon_en_cours'=>'BT-2025-000003','localisation'=>'Rue du Marché'],
            ['nom'=>'Brigade Éclairage','chef'=>'DIABY Sékou','membres'=>4,'statut'=>'en_intervention','bon_en_cours'=>'BT-2025-000004','localisation'=>'Carrefour Yopougon'],
            ['nom'=>'Brigade Assainissement','chef'=>'DOSSO Abdoulaye','membres'=>5,'statut'=>'disponible'],
            ['nom'=>'Brigade Bâtiments','chef'=>'KOUASSI Jean','membres'=>7,'statut'=>'repos'],
        ];
        foreach ($equipes as $e) {
            Equipe::firstOrCreate(['nom' => $e['nom']], array_merge($e, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Bons de travaux ───────────────────────────────────────────────────
        BonTravail::firstOrCreate(['reference'=>'BT-2025-000003'], ['demande_ref'=>'DI-2025-000024','description'=>'Rebouchage nid de poule carrefour Marché','service'=>'Voirie','equipe'=>'Brigade Voirie Nord','chef'=>"N'GORAN Koffi",'date_debut'=>'2025-05-21','materiaux'=>'Bitume 300kg, Sable fin 2m³','statut'=>'en_cours','created_at'=>now(),'updated_at'=>now()]);
        BonTravail::firstOrCreate(['reference'=>'BT-2025-000004'], ['demande_ref'=>'DI-2025-000023','description'=>'Remplacement câbles et lampes ECL-002','service'=>'Éclairage public','equipe'=>'Brigade Éclairage','chef'=>'DIABY Sékou','date_debut'=>'2025-05-22','materiaux'=>'Câble électrique 50m, LED 250W x3','statut'=>'en_cours','created_at'=>now(),'updated_at'=>now()]);

        // ── Planning maintenance ───────────────────────────────────────────────
        $plannings = [
            ['equipement'=>'Groupe électrogène HdV 250KVA','service'=>'Bâtiments','type_maintenance'=>'Révision générale','date_prevue'=>'2025-06-15','periodicite'=>'Trimestrielle','responsable'=>'DIABY Sékou','cout_estime'=>95000,'statut'=>'programme'],
            ['equipement'=>'Camion benne OM-347','service'=>'Voirie','type_maintenance'=>'Vidange + révision','date_prevue'=>'2025-06-01','periodicite'=>'Trimestrielle','responsable'=>"N'GORAN Koffi",'cout_estime'=>180000,'statut'=>'en_retard'],
            ['equipement'=>'Réseau éclairage Plateau','service'=>'Éclairage public','type_maintenance'=>'Vérification câblage','date_prevue'=>'2025-07-10','periodicite'=>'Semestrielle','responsable'=>'DIABY Sékou','cout_estime'=>250000,'statut'=>'programme'],
            ['equipement'=>'Pompe curage caniveaux','service'=>'Eau / Assainissement','type_maintenance'=>'Nettoyage filtres','date_prevue'=>'2025-05-20','periodicite'=>'Mensuelle','responsable'=>'DOSSO Abdoulaye','cout_estime'=>35000,'statut'=>'effectue'],
        ];
        foreach ($plannings as $p) {
            PlanningMaintenance::firstOrCreate(['equipement'=>$p['equipement'],'date_prevue'=>$p['date_prevue']], array_merge($p, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Maintenance corrective ─────────────────────────────────────────────
        MaintenanceCorrective::firstOrCreate(['equipement'=>'Camion benne OM-347','date_signalement'=>'2025-05-10'], ['service'=>'Voirie','panne'=>'Fuite hydraulique circuit freinage — camion immobilisé','priorite'=>'urgente','statut'=>'en_cours','created_at'=>now(),'updated_at'=>now()]);
        MaintenanceCorrective::firstOrCreate(['equipement'=>'Groupe électrogène HdV','date_signalement'=>'2025-05-08'], ['service'=>'Bâtiments','panne'=>'Panne démarreur — groupe ne démarre plus','priorite'=>'haute','technicien'=>'KOUASSI Jean','statut'=>'resolu','date_resolution'=>'2025-05-12','cout_reel'=>85000,'created_at'=>now(),'updated_at'=>now()]);

        // ── Réparations voirie ─────────────────────────────────────────────────
        ReparationVoirie::firstOrCreate(['route'=>'Rue du Marché','date_signalement'=>'2025-05-20'], ['description'=>'Nid de poule 1m² profond — danger pour véhicules','priorite'=>'urgente','signale_par'=>'KOUA Amédée','statut'=>'en_intervention','date_intervention'=>'2025-05-21','created_at'=>now(),'updated_at'=>now()]);
        ReparationVoirie::firstOrCreate(['route'=>'Voie desserte lotissement 15','date_signalement'=>'2025-05-12'], ['description'=>'Ravinement important suite aux pluies — voie impraticable','priorite'=>'haute','signale_par'=>'Chef quartier','statut'=>'signalee','created_at'=>now(),'updated_at'=>now()]);

        $this->command->info('Données démo Services Techniques insérées (routes, lampadaires, bâtiments, demandes, équipes, maintenances).');
    }
}
