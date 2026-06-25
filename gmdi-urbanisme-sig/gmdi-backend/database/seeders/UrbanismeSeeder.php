<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Parcelle;
use App\Models\Lot;
use App\Models\TitreFoncier;
use App\Models\ReserveAdministrative;
use App\Models\Permis;
use App\Models\Quartier;
use App\Models\CoucheVoirie;
use App\Models\ReseauElectrique;
use App\Models\ReseauHydraulique;
use App\Models\Lotissement;
use App\Models\AmenagementUrbain;
use App\Models\SuiviChantier;
use App\Models\EquipementPublic;

// ─────────────────────────────────────────────────────────────────────────────
//  UrbanismeSeeder — données de démonstration
// ─────────────────────────────────────────────────────────────────────────────
class UrbanismeSeeder extends Seeder
{
    public function run(): void
    {
        // ── Quartiers ─────────────────────────────────────────────────────────
        $quartiers = [
            ['nom'=>'Plateau','code'=>'PLT-01','superficie'=>320,'population'=>45000,'chef'=>'DIALLO Moussa','lat'=>5.3544,'lng'=>-4.0047,'nombre_parcelles'=>1850],
            ['nom'=>'Cocody','code'=>'COC-02','superficie'=>1850,'population'=>135000,'chef'=>"N'GORAN Siméon",'lat'=>5.3711,'lng'=>-3.9793,'nombre_parcelles'=>4200],
            ['nom'=>'Adjamé','code'=>'ADJ-03','superficie'=>580,'population'=>325000,'chef'=>'KONÉ Aboubacar','lat'=>5.3667,'lng'=>-4.0167,'nombre_parcelles'=>3100],
            ['nom'=>'Yopougon','code'=>'YOP-04','superficie'=>2340,'population'=>1060000,'chef'=>'BAMBA Souleymane','lat'=>5.3333,'lng'=>-4.0667,'nombre_parcelles'=>8500],
            ['nom'=>'Treichville','code'=>'TRE-05','superficie'=>280,'population'=>150000,'chef'=>'AHOUSSOU Paul','lat'=>5.3000,'lng'=>-4.0167,'nombre_parcelles'=>2300],
        ];
        foreach ($quartiers as $q) {
            Quartier::firstOrCreate(['code'=>$q['code']], array_merge($q,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Parcelles ─────────────────────────────────────────────────────────
        $parcelles = [
            ['reference'=>'URB-PAR-2025-0001','proprietaire'=>'KONAN Rodrigue','localisation'=>'Lot 15 Ilot A, Cocody Riviera','quartier'=>'Cocody','superficie'=>400,'usage'=>'Résidentiel','titre_foncier'=>'TF-CI-ABJ-2024-01234','statut'=>'occupe','lat'=>5.3711,'lng'=>-3.9793],
            ['reference'=>'URB-PAR-2025-0002','proprietaire'=>'SOCIÉTÉ IMMOBILIÈRE ABIDJANAISE','localisation'=>'Carré 7-B, Zone Industrielle Yopougon','quartier'=>'Yopougon','superficie'=>2500,'usage'=>'Commercial','statut'=>'occupe','lat'=>5.3333,'lng'=>-4.0667],
            ['reference'=>'URB-PAR-2025-0003','proprietaire'=>'État de Côte d\'Ivoire','localisation'=>'Parcelle administrative secteur 3, Plateau','quartier'=>'Plateau','superficie'=>1200,'usage'=>'Administratif','statut'=>'reserve','lat'=>5.3544,'lng'=>-4.0047],
            ['reference'=>'URB-PAR-2025-0004','proprietaire'=>'YAO Marcelline','localisation'=>'Résidence Bonoumin Lot 22','quartier'=>'Cocody','superficie'=>350,'usage'=>'Résidentiel','statut'=>'libre','lat'=>5.3720,'lng'=>-3.9780],
            ['reference'=>'URB-PAR-2025-0005','proprietaire'=>'KOUAMÉ Fernand','localisation'=>'Avenue 13 Treichville, Lot 8','quartier'=>'Treichville','superficie'=>280,'usage'=>'Commercial','statut'=>'litige','lat'=>5.3000,'lng'=>-4.0167],
        ];
        foreach ($parcelles as $p) {
            Parcelle::firstOrCreate(['reference'=>$p['reference']], array_merge($p,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Titres fonciers ────────────────────────────────────────────────────
        $titres = [
            ['numero'=>'TF-CI-ABJ-2024-01234','type'=>'TF','proprietaire'=>'KONAN Rodrigue','superficie'=>400,'localisation'=>'Lot 15 Ilot A, Cocody','date_delivrance'=>'2024-03-15','statut'=>'valide'],
            ['numero'=>'ACD-CI-ABJ-2023-00456','type'=>'ACD','proprietaire'=>'SOCIÉTÉ IMMOBILIÈRE ABIDJANAISE','superficie'=>2500,'localisation'=>'Zone Industrielle Yopougon','date_delivrance'=>'2023-07-20','statut'=>'valide'],
            ['numero'=>'TF-CI-ABJ-2020-00089','type'=>'TF','proprietaire'=>'HÉRITIERS KOUAMÉ','superficie'=>280,'localisation'=>'Av. 13 Treichville','date_delivrance'=>'2020-01-10','statut'=>'litige'],
        ];
        foreach ($titres as $t) {
            TitreFoncier::firstOrCreate(['numero'=>$t['numero']], array_merge($t,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Réserves administratives ───────────────────────────────────────────
        $reserves = [
            ['denomination'=>'Site École Primaire Nouveau Quartier','usage'=>'École','superficie'=>3000,'localisation'=>'Secteur 12, Yopougon Nord','statut'=>'reserve','administration'=>'Ministère de l\'Éducation'],
            ['denomination'=>'Espace Vert Boulevard Lagunaire','usage'=>'Espace vert','superficie'=>8500,'localisation'=>'Bord de lagune, Plateau','statut'=>'affecte','administration'=>'Mairie d\'Abidjan'],
            ['denomination'=>'Site Marché de Proximité Adjamé Nord','usage'=>'Marché','superficie'=>5000,'localisation'=>'Quartier Williamsville','statut'=>'reserve','administration'=>'Mairie d\'Abidjan'],
        ];
        foreach ($reserves as $r) {
            ReserveAdministrative::firstOrCreate(['denomination'=>$r['denomination']], array_merge($r,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Permis ─────────────────────────────────────────────────────────────
        $permis = [
            ['reference'=>'PC-2025-000024','type'=>'construire','demandeur'=>'KONAN Rodrigue','telephone'=>'07 11 22 33','localisation'=>'Lot 15 Ilot A, Cocody Riviera','quartier'=>'Cocody','surface_plancher'=>180,'date_depot'=>'2025-05-10','statut'=>'instruction','agent'=>'YAO Marcelline'],
            ['reference'=>'PC-2025-000023','type'=>'construire','demandeur'=>'DIALLO Moussa','localisation'=>'Résidence Les Palmiers, Yopougon','quartier'=>'Yopougon','surface_plancher'=>220,'date_depot'=>'2025-05-08','statut'=>'accorde','date_decision'=>'2025-05-20','date_expiration'=>'2027-05-20','agent'=>'YAO Marcelline'],
            ['reference'=>'PD-2025-000005','type'=>'demolir','demandeur'=>'BÊTE Investissements','localisation'=>'Av. Général de Gaulle, Plateau','quartier'=>'Plateau','date_depot'=>'2025-04-22','statut'=>'depose'],
            ['reference'=>'CU-2025-000041','type'=>'certificat','demandeur'=>'HOLDING IMMOBILIÈRE CI','localisation'=>'Zone Extension Cocody Angré','quartier'=>'Cocody','date_depot'=>'2025-05-15','statut'=>'accorde','date_decision'=>'2025-05-25','date_expiration'=>'2025-11-25'],
            ['reference'=>'AO-2025-000012','type'=>'occupation','demandeur'=>'TÉLÉCOMS IVOIRE SA','localisation'=>'Carrefour Deux Plateaux Vallons','quartier'=>'Cocody','date_depot'=>'2025-03-12','statut'=>'refuse','date_decision'=>'2025-04-05','observations'=>'Zone résidentielle protégée — installation antenne refusée'],
        ];
        foreach ($permis as $p) {
            Permis::firstOrCreate(['reference'=>$p['reference']], array_merge($p,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Lots ──────────────────────────────────────────────────────────────
        Lot::firstOrCreate(['reference'=>'LOT-A-001','lotissement'=>'Résidence Les Cocotiers'], ['lotissement'=>'Résidence Les Cocotiers','superficie'=>320,'statut'=>'attribue','attributaire'=>'COULIBALY Aminata','date_attribution'=>'2025-02-15','created_at'=>now(),'updated_at'=>now()]);
        Lot::firstOrCreate(['reference'=>'LOT-A-002','lotissement'=>'Résidence Les Cocotiers'], ['lotissement'=>'Résidence Les Cocotiers','superficie'=>280,'statut'=>'disponible','created_at'=>now(),'updated_at'=>now()]);
        Lot::firstOrCreate(['reference'=>'LOT-B-001','lotissement'=>'Cité Verte Yopougon'],     ['lotissement'=>'Cité Verte Yopougon','superficie'=>350,'statut'=>'construit','attributaire'=>'TRAORÉ Saliou','created_at'=>now(),'updated_at'=>now()]);

        // ── Voiries SIG ────────────────────────────────────────────────────────
        $voiries = [
            ['nom'=>'Boulevard Valéry Giscard d\'Estaing','type'=>'principale','longueur'=>8.4,'etat'=>'bon','quartier'=>'Cocody'],
            ['nom'=>'Avenue Général de Gaulle','type'=>'principale','longueur'=>3.2,'etat'=>'bon','quartier'=>'Plateau'],
            ['nom'=>'Rue du Commerce','type'=>'secondaire','longueur'=>1.8,'etat'=>'moyen','quartier'=>'Plateau'],
            ['nom'=>'Boulevard de Marseille','type'=>'principale','longueur'=>5.6,'etat'=>'moyen','quartier'=>'Treichville'],
            ['nom'=>'Route de l\'Aéroport','type'=>'principale','longueur'=>12.0,'etat'=>'bon','quartier'=>'Yopougon'],
            ['nom'=>'Voie de desserte Yopougon Selmer','type'=>'secondaire','longueur'=>2.3,'etat'=>'degrade','quartier'=>'Yopougon'],
        ];
        foreach ($voiries as $v) {
            CoucheVoirie::firstOrCreate(['nom'=>$v['nom']], array_merge($v,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Réseaux électriques ────────────────────────────────────────────────
        $reseauxElec = [
            ['zone'=>'Plateau & Cocody','type'=>'HT','longueur'=>45.2,'taux_couverture'=>98,'operateur'=>'CIE'],
            ['zone'=>'Adjamé & Treichville','type'=>'MT','longueur'=>38.5,'taux_couverture'=>94,'operateur'=>'CIE'],
            ['zone'=>'Yopougon Centre','type'=>'MT','longueur'=>62.8,'taux_couverture'=>88,'operateur'=>'CIE'],
            ['zone'=>'Yopougon Périphérie','type'=>'BT','longueur'=>28.4,'taux_couverture'=>71,'operateur'=>'CIE'],
        ];
        foreach ($reseauxElec as $r) {
            ReseauElectrique::firstOrCreate(['zone'=>$r['zone']], array_merge($r,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Réseaux hydrauliques ───────────────────────────────────────────────
        $reseauxHydro = [
            ['zone'=>'Plateau & Cocody','type'=>'adduction','longueur'=>38.2,'taux_couverture'=>96,'statut'=>'operationnel'],
            ['zone'=>'Adjamé & Treichville','type'=>'assainissement','longueur'=>42.1,'taux_couverture'=>88,'statut'=>'operationnel'],
            ['zone'=>'Yopougon Centre','type'=>'adduction','longueur'=>55.6,'taux_couverture'=>79,'statut'=>'operationnel'],
            ['zone'=>'Yopougon Secteur 9','type'=>'adduction','longueur'=>15.3,'taux_couverture'=>52,'statut'=>'en_travaux'],
        ];
        foreach ($reseauxHydro as $r) {
            ReseauHydraulique::firstOrCreate(['zone'=>$r['zone']], array_merge($r,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Lotissements ───────────────────────────────────────────────────────
        $lotissements = [
            ['reference'=>'LOT-2024-001','denomination'=>'Résidence Les Cocotiers','promoteur'=>'SIMCI SA','localisation'=>'Cocody Angré Prolongement','superficie'=>12.5,'nombre_lots'=>85,'lots_disponibles'=>12,'statut'=>'en_cours','date_approb'=>'2024-02-10'],
            ['reference'=>'LOT-2025-002','denomination'=>'Cité Verte Yopougon','promoteur'=>'PROMO-INVEST CI','localisation'=>'Yopougon Attié Nord','superficie'=>8.2,'nombre_lots'=>60,'lots_disponibles'=>28,'statut'=>'approuve','date_approb'=>'2025-01-20'],
            ['reference'=>'LOT-2025-003','denomination'=>'Villa Park Abobo','promoteur'=>'GROUPE HABITAT AFRIQUE','localisation'=>'Abobo Sud Extension','superficie'=>15.0,'nombre_lots'=>120,'lots_disponibles'=>120,'statut'=>'etude'],
        ];
        foreach ($lotissements as $l) {
            Lotissement::firstOrCreate(['reference'=>$l['reference']], array_merge($l,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Aménagements urbains ───────────────────────────────────────────────
        $amenagements = [
            ['intitule'=>'Réaménagement Marché d\'Adjamé','type'=>'Marché','localisation'=>'Adjamé Centre','budget'=>450000000,'financeur'=>'État + Mairie','date_debut'=>'2025-03-01','date_fin'=>'2026-06-30','taux_avancement'=>22,'statut'=>'en_cours'],
            ['intitule'=>'Jardin Municipal Deux Plateaux','type'=>'Espace vert','localisation'=>'Cocody Deux Plateaux','budget'=>120000000,'financeur'=>'Mairie d\'Abidjan','date_debut'=>'2025-06-01','taux_avancement'=>0,'statut'=>'approuve'],
            ['intitule'=>'Extension Voirie Yopougon Secteur 9','type'=>'Voirie','localisation'=>'Yopougon Secteur 9','budget'=>280000000,'financeur'=>'Fonds BID','taux_avancement'=>0,'statut'=>'etude'],
        ];
        foreach ($amenagements as $a) {
            AmenagementUrbain::firstOrCreate(['intitule'=>$a['intitule']], array_merge($a,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Suivi chantiers ───────────────────────────────────────────────────
        SuiviChantier::firstOrCreate(['projet'=>'Réaménagement Marché d\'Adjamé'], ['entrepreneur'=>'ENTREPRISE GÉNÉRALE CI (EGCI)','date_ouverture'=>'2025-03-15','date_prevue_fin'=>'2026-06-30','taux_avancement'=>22,'derniere_visite'=>'2025-05-28','statut'=>'actif','observations'=>'Fondations terminées, gros œuvre débuté','created_at'=>now(),'updated_at'=>now()]);
        SuiviChantier::firstOrCreate(['projet'=>'Réhabilitation Voie CKB Yopougon'], ['entrepreneur'=>'ROUTES & TRAVAUX IVOIRE','date_ouverture'=>'2025-01-10','date_prevue_fin'=>'2025-04-30','taux_avancement'=>68,'derniere_visite'=>'2025-05-15','statut'=>'retard','observations'=>'Retard de 3 semaines dû aux pluies','created_at'=>now(),'updated_at'=>now()]);

        // ── Équipements publics (géolocalisés) ────────────────────────────────
        $equipements = [
            ['nom'=>'École Primaire Publique Cocody 12','type'=>'ecole','adresse'=>'Rue des Pins, Cocody','quartier'=>'Cocody','lat'=>5.3720,'lng'=>-3.9780,'capacite'=>600,'etat'=>'bon','responsable'=>'Directeur KOUASSI'],
            ['nom'=>'École Primaire Publique Adjamé Centre','type'=>'ecole','adresse'=>'Av. 21, Adjamé','quartier'=>'Adjamé','lat'=>5.3650,'lng'=>-4.0180,'capacite'=>450,'etat'=>'moyen'],
            ['nom'=>'Lycée Municipal de Treichville','type'=>'ecole','adresse'=>'Avenue 13, Treichville','quartier'=>'Treichville','lat'=>5.2980,'lng'=>-4.0150,'capacite'=>1200,'etat'=>'bon','responsable'=>'Proviseur BÉTÉ'],
            ['nom'=>'Centre de Santé Urbain Yopougon','type'=>'sante','adresse'=>'Boulevard de la Paix, Yopougon','quartier'=>'Yopougon','lat'=>5.3320,'lng'=>-4.0660,'capacite'=>150,'etat'=>'bon','responsable'=>'Dr. DIALLO'],
            ['nom'=>'Polyclinique Plateau Nord','type'=>'sante','adresse'=>'Rue du Docteur Crozet, Plateau','quartier'=>'Plateau','lat'=>5.3555,'lng'=>-4.0038,'capacite'=>200,'etat'=>'bon'],
            ['nom'=>'Marché Municipal d\'Adjamé','type'=>'marche','adresse'=>'Carrefour Williamsville','quartier'=>'Adjamé','lat'=>5.3660,'lng'=>-4.0200,'etat'=>'moyen','responsable'=>'KONÉ Directeur'],
            ['nom'=>'Marché de Treichville','type'=>'marche','adresse'=>'Avenue 13, Treichville','quartier'=>'Treichville','lat'=>5.2995,'lng'=>-4.0165,'etat'=>'degrade'],
            ['nom'=>'Jardin du Plateau','type'=>'espace_vert','adresse'=>'Boulevard de la République, Plateau','quartier'=>'Plateau','lat'=>5.3540,'lng'=>-4.0055,'capacite'=>5000,'etat'=>'bon'],
            ['nom'=>'Espace vert Riviera Golf','type'=>'espace_vert','adresse'=>'Riviera Golf, Cocody','quartier'=>'Cocody','lat'=>5.3800,'lng'=>-3.9700,'etat'=>'bon'],
            ['nom'=>'Stade Municipal Yopougon','type'=>'sport','adresse'=>'Quartier Yopougon SIDECI','quartier'=>'Yopougon','lat'=>5.3350,'lng'=>-4.0700,'capacite'=>8000,'etat'=>'moyen'],
        ];
        foreach ($equipements as $e) {
            EquipementPublic::firstOrCreate(['nom'=>$e['nom']], array_merge($e,['created_at'=>now(),'updated_at'=>now()]));
        }

        $this->command->info('Données démo Urbanisme & SIG insérées (parcelles, permis, quartiers, équipements, lotissements…)');
    }
}
