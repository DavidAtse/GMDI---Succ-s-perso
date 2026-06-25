<?php

namespace Database\Seeders;

use App\Models\CompteGL;
use App\Models\Depense;
use App\Models\EcritureComptable;
use App\Models\LigneBudget;
use App\Models\MouvementBanque;
use App\Models\MouvementCaisse;
use App\Models\Recette;
use App\Models\RecetteParService;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Utilisateurs ──────────────────────────────────────────────
        User::create([
            'name'     => 'Lassina Bamba',
            'email'    => 'lbamba@gmdi.ci',
            'password' => Hash::make('password123'),
            'role'     => 'Directeur Financier',
        ]);

        User::create([
            'name'     => 'Admin GMDI',
            'email'    => 'admin@gmdi.ci',
            'password' => Hash::make('admin123'),
            'role'     => 'Administrateur',
        ]);

        // ── Recettes ──────────────────────────────────────────────────
        $recettes = [
            ['contribuable' => 'KOUASSI Bernard',  'type_taxe' => 'taxe_fonciere',   'montant' => 85000,    'date_echeance' => '2025-03-31', 'mode_paiement' => 'mobile_money', 'statut' => 'valide',     'date_paiement' => '2025-05-26'],
            ['contribuable' => 'TRAORÉ Bakary',    'type_taxe' => 'taxe_habitation', 'montant' => 45000,    'date_echeance' => '2025-04-30', 'mode_paiement' => 'especes',      'statut' => 'en_attente', 'date_paiement' => null],
            ['contribuable' => "AKA N'Goran Paul", 'type_taxe' => 'patente',         'montant' => 120000,   'date_echeance' => '2025-01-31', 'mode_paiement' => 'mobile_money', 'statut' => 'en_attente', 'date_paiement' => null],
            ['contribuable' => 'SACI SA',          'type_taxe' => 'droit_domaine',   'montant' => 750000,   'date_echeance' => '2025-05-15', 'mode_paiement' => 'virement',     'statut' => 'valide',     'date_paiement' => '2025-05-14'],
            ['contribuable' => 'OUATTARA Seydou',  'type_taxe' => 'taxe_marche',     'montant' => 15000,    'date_echeance' => '2025-05-31', 'mode_paiement' => 'mobile_money', 'statut' => 'valide',     'date_paiement' => '2025-05-25'],
        ];

        foreach ($recettes as $r) {
            Recette::create($r);
        }

        // ── Dépenses ──────────────────────────────────────────────────
        $depenses = [
            ['objet' => 'Achat fournitures bureau',        'fournisseur' => 'BUROTIC CI',     'montant' => 450000,   'chapitre' => 'fonctionnement', 'article' => 'fournitures',       'date_engagement' => '2025-05-10', 'description' => 'Achat de fournitures de bureau pour le 1er semestre 2025',          'statut' => 'valide',     'date_paiement' => '2025-05-20'],
            ['objet' => 'Réparation véhicule service',     'fournisseur' => 'GARAGE CENTRAL', 'montant' => 280000,   'chapitre' => 'fonctionnement', 'article' => 'entretien_vehicules','date_engagement' => '2025-05-05', 'description' => 'Réparation du véhicule de service N° 001 suite à panne moteur', 'statut' => 'en_attente', 'date_paiement' => null],
            ['objet' => 'Construction dalles voirie nord', 'fournisseur' => 'BTP ABIDJAN SA', 'montant' => 12500000, 'chapitre' => 'investissement', 'article' => 'voirie',            'date_engagement' => '2025-04-15', 'description' => 'Construction de dalles béton sur la voirie nord (tronçon de 2km)', 'statut' => 'valide',     'date_paiement' => '2025-05-10'],
        ];

        foreach ($depenses as $d) {
            Depense::create($d);
        }

        // ── Budget ────────────────────────────────────────────────────
        $lignes = [
            ['chapitre' => 'recettes',       'article' => 'taxes_communales', 'designation' => 'Taxes communales 2025',   'montant_previsionnel' => 520000000, 'montant_consomme' => 202500000, 'statut' => 'approuve'],
            ['chapitre' => 'personnel',      'article' => 'salaires',         'designation' => 'Salaires agents 2025',    'montant_previsionnel' => 180000000, 'montant_consomme' => 75000000,  'statut' => 'approuve'],
            ['chapitre' => 'fonctionnement', 'article' => 'fournitures',      'designation' => 'Fournitures et services', 'montant_previsionnel' => 15000000,  'montant_consomme' => 4500000,   'statut' => 'approuve'],
            ['chapitre' => 'investissement', 'article' => 'voirie',           'designation' => 'Travaux voirie 2025',     'montant_previsionnel' => 85000000,  'montant_consomme' => 38000000,  'statut' => 'approuve'],
        ];

        foreach ($lignes as $l) {
            LigneBudget::create($l);
        }

        // ── Écritures comptables ──────────────────────────────────────
        $ecritures = [
            ['numero' => 'JR-001', 'date' => '2025-05-26', 'journal' => 'recettes', 'libelle' => 'Taxe foncière — KOUASSI Bernard', 'compte' => '7111', 'debit' => 85000,  'credit' => 0,      'piece' => 'TX-2025-10289'],
            ['numero' => 'JR-002', 'date' => '2025-05-26', 'journal' => 'caisse',   'libelle' => 'Encaissement taxe foncière',       'compte' => '5111', 'debit' => 85000,  'credit' => 0,      'piece' => 'REC-001'],
            ['numero' => 'JR-003', 'date' => '2025-05-20', 'journal' => 'depenses', 'libelle' => 'Achat fournitures — BUROTIC CI',   'compte' => '6062', 'debit' => 0,      'credit' => 450000, 'piece' => 'DEP-2025-0143'],
            ['numero' => 'JR-004', 'date' => '2025-05-14', 'journal' => 'banque',   'libelle' => 'Virement reçu — SACI SA',          'compte' => '5121', 'debit' => 750000, 'credit' => 0,      'piece' => 'TX-2025-10292'],
        ];

        foreach ($ecritures as $e) {
            EcritureComptable::create($e);
        }

        // ── Comptes GL ────────────────────────────────────────────────
        $comptes = [
            ['compte' => '5111', 'intitule' => 'Caisse principale',         'debit' => 935000,   'credit' => 450000,   'solde' => 485000],
            ['compte' => '5121', 'intitule' => 'Banque — compte principal', 'debit' => 48600000, 'credit' => 12500000, 'solde' => 36100000],
            ['compte' => '6062', 'intitule' => 'Fournitures de bureau',     'debit' => 0,        'credit' => 450000,   'solde' => -450000],
            ['compte' => '7111', 'intitule' => 'Taxes communales',          'debit' => 835000,   'credit' => 0,        'solde' => 835000],
            ['compte' => '7211', 'intitule' => 'Patentes et licences',      'debit' => 120000,   'credit' => 0,        'solde' => 120000],
        ];

        foreach ($comptes as $c) {
            CompteGL::create($c);
        }

        // ── Mouvements Caisse ─────────────────────────────────────────
        $mvtCaisse = [
            ['date' => '2025-05-26', 'libelle' => 'Taxe foncière — KOUASSI Bernard',  'type' => 'encaissement', 'montant' => 85000, 'solde_apres' => 52485000],
            ['date' => '2025-05-26', 'libelle' => 'Droit état civil — acte naissance', 'type' => 'encaissement', 'montant' => 2000,  'solde_apres' => 52487000],
            ['date' => '2025-05-26', 'libelle' => 'Achat fournitures urgence',         'type' => 'decaissement', 'montant' => 45000, 'solde_apres' => 52442000],
            ['date' => '2025-05-25', 'libelle' => 'Taxe de marché — OUATTARA S.',      'type' => 'encaissement', 'montant' => 15000, 'solde_apres' => 52400000],
        ];

        foreach ($mvtCaisse as $m) {
            MouvementCaisse::create($m);
        }

        // ── Mouvements Banque ─────────────────────────────────────────
        $mvtBanque = [
            ['date' => '2025-05-14', 'libelle' => 'Virement reçu — SACI SA',       'debit' => 0,         'credit' => 750000,   'solde' => 36100000],
            ['date' => '2025-05-10', 'libelle' => 'Règlement BTP ABIDJAN SA',       'debit' => 12500000, 'credit' => 0,         'solde' => 35350000],
            ['date' => '2025-05-01', 'libelle' => 'Dotation DGD — Budget communal', 'debit' => 0,         'credit' => 15000000, 'solde' => 47850000],
        ];

        foreach ($mvtBanque as $m) {
            MouvementBanque::create($m);
        }

        // ── Recettes par service ──────────────────────────────────────
        $rps = [
            ['service' => 'Finances',   'type' => 'Taxes foncières, patentes', 'montant' => 85000000, 'pct' => 42],
            ['service' => 'Urbanisme',  'type' => 'Droits de permis',          'montant' => 18000000, 'pct' => 9],
            ['service' => 'État Civil', 'type' => 'Droits administratifs',     'montant' => 12500000, 'pct' => 6],
            ['service' => 'Domaine',    'type' => 'Droits domaniaux',          'montant' => 65000000, 'pct' => 32],
            ['service' => 'Marchés',    'type' => 'Taxes de marché',           'montant' => 22000000, 'pct' => 11],
        ];

        foreach ($rps as $r) {
            RecetteParService::create($r);
        }
    }
}
