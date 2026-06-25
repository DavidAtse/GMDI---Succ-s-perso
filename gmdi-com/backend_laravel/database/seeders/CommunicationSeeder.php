<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Actualite;
use App\Models\CompteReseau;
use App\Models\PostProgramme;
use App\Models\Partenaire;
use App\Models\ArticlePresse;
use App\Models\Document;
use App\Models\Reclamation;
use App\Models\Suggestion;
use App\Models\ConsultationPublique;
use App\Models\CampagneSms;

class CommunicationSeeder extends Seeder
{
    public function run(): void
    {
        // ── Actualités ────────────────────────────────────────────────────────
        $actus = [
            ['type'=>'evenement',  'titre'=>'Ouverture du nouveau marché municipal',        'contenu'=>"Le Maire a inauguré le nouveau marché ce samedi 24 mai en présence des élus locaux et des commerçants.", 'auteur'=>'Service Communication','date'=>'2025-05-24','statut'=>'publie','categorie'=>'evenement'],
            ['type'=>'annonce',    'titre'=>'Campagne de vaccination — Saison des pluies',  'contenu'=>'La mairie organise en partenariat avec le Ministère de la Santé une campagne de vaccination gratuite les 10 et 11 juin 2025.','auteur'=>'Service Communication','date'=>'2025-05-20','statut'=>'publie','categorie'=>'sante'],
            ['type'=>'communique', 'titre'=>'Budget rectificatif 2025 adopté',              'contenu'=>"Le Conseil Municipal a adopté lors de sa séance du 15 mai le budget rectificatif 2025 à l'unanimité.",'auteur'=>'Service Communication','date'=>'2025-05-16','statut'=>'publie','categorie'=>'institution'],
            ['type'=>'annonce',    'titre'=>'Travaux voirie secteur nord — Perturbations',  'contenu'=>'Des travaux de réfection sont prévus du 1er au 30 juin dans le secteur nord.','auteur'=>'Services Techniques','date'=>'2025-05-28','statut'=>'brouillon','categorie'=>'travaux'],
        ];
        foreach ($actus as $a) {
            Actualite::firstOrCreate(['titre' => $a['titre']], array_merge($a, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Comptes réseaux sociaux ───────────────────────────────────────────
        $comptes = [
            ['plateforme'=>'facebook',  'nom'=>'Mairie de la Commune','handle'=>'@mairie.commune.ci',        'abonnes'=>4820,'publications'=>248,'taux_engagement'=>7.2,'porte_mois'=>12400,'dernier_post'=>"Inauguration marché — 24/05/2025 — 312 J'aime"],
            ['plateforme'=>'twitter',   'nom'=>'Mairie de la Commune','handle'=>'@MairieCommuneCI',          'abonnes'=>2350,'publications'=>420,'taux_engagement'=>4.1,'porte_mois'=>5800, 'dernier_post'=>null],
            ['plateforme'=>'instagram', 'nom'=>'Mairie de la Commune','handle'=>'@mairie_commune_ci',        'abonnes'=>3940,'publications'=>186,'taux_engagement'=>9.4,'porte_mois'=>8200, 'dernier_post'=>null],
            ['plateforme'=>'whatsapp',  'nom'=>'Info Mairie — Groupe officiel','handle'=>'+225 07 00 00 00 60','abonnes'=>1690,'publications'=>0, 'taux_engagement'=>92, 'porte_mois'=>0,    'dernier_post'=>null],
        ];
        foreach ($comptes as $c) {
            CompteReseau::firstOrCreate(['plateforme' => $c['plateforme']], array_merge($c, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Calendrier éditorial ──────────────────────────────────────────────
        $posts = [
            ['date'=>'2025-06-02','contenu'=>'Campagne vaccination — Sensibilisation',      'plateformes'=>'facebook,whatsapp',              'responsable'=>'DIALLO F.','statut'=>'programme'],
            ['date'=>'2025-06-05','contenu'=>'Journée mondiale environnement',              'plateformes'=>'instagram,facebook',             'responsable'=>'DIALLO F.','statut'=>'programme'],
            ['date'=>'2025-06-10','contenu'=>'Formation GMDI — Agents communaux',           'plateformes'=>'twitter',                        'responsable'=>'Comm.',    'statut'=>'programme'],
            ['date'=>'2025-06-15','contenu'=>'Bilan mensuel services municipaux',           'plateformes'=>'facebook,twitter,instagram,whatsapp','responsable'=>'DIALLO F.','statut'=>'a_rediger'],
            ['date'=>'2025-06-20','contenu'=>'Inauguration parc de proximité',              'plateformes'=>'facebook,twitter,instagram,whatsapp','responsable'=>'Comm.','statut'=>'a_confirmer'],
        ];
        foreach ($posts as $p) {
            PostProgramme::firstOrCreate(['date'=>$p['date'],'contenu'=>substr($p['contenu'],0,30)], array_merge($p,['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Partenaires ───────────────────────────────────────────────────────
        $partenaires = [
            ['nom'=>'BNETD',             'type'=>'Institution publique',        'domaine'=>'Infrastructure et aménagement', 'contact'=>'Dir. Projets — 27 20 11 22','date_debut'=>'2020-01-01','statut'=>'actif'],
            ['nom'=>'UNICEF CI',         'type'=>'Organisation internationale', 'domaine'=>'Éducation, protection enfance', 'contact'=>'Rep. UNICEF — 27 20 33 44',  'date_debut'=>'2019-06-01','statut'=>'actif'],
            ['nom'=>'ONG Eau Pour Tous', 'type'=>'ONG / Association',           'domaine'=>'Eau potable zones rurales',     'contact'=>'Directrice — 07 88 99 00',   'date_debut'=>'2023-03-15','statut'=>'actif'],
        ];
        foreach ($partenaires as $p) {
            Partenaire::firstOrCreate(['nom' => $p['nom']], array_merge($p, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Revue de presse ───────────────────────────────────────────────────
        $presse = [
            ['date'=>'2025-05-24','media'=>'RTI 1',           'titre'=>'La commune inaugure son nouveau marché municipal',   'type'=>'TV',           'tonalite'=>'Positive'],
            ['date'=>'2025-05-23','media'=>'Fraternité Matin', 'titre'=>'Budget rectificatif 2025 adopté par le Conseil',    'type'=>'Presse écrite','tonalite'=>'Neutre'],
            ['date'=>'2025-05-21','media'=>'NCI',              'titre'=>"Collecte des ordures : les points noirs identifiés", 'type'=>'TV',           'tonalite'=>'Mitigée'],
            ['date'=>'2025-05-18','media'=>'Radio Nationale',  'titre'=>'Interview du Maire — Bilan travaux voirie',          'type'=>'Radio',        'tonalite'=>'Positive'],
        ];
        foreach ($presse as $a) {
            ArticlePresse::firstOrCreate(['titre' => $a['titre']], array_merge($a, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Documents ─────────────────────────────────────────────────────────
        $docs = [
            ['titre'=>'Inauguration marché municipal',          'type'=>'photo',        'categorie'=>'Événements',       'date'=>'2025-05-24','auteur'=>'Service Comm.'],
            ['titre'=>'Réunion Conseil Municipal mai',          'type'=>'photo',        'categorie'=>'Vie municipale',   'date'=>'2025-05-15','auteur'=>'Service Comm.'],
            ['titre'=>'Travaux voirie secteur nord',            'type'=>'photo',        'categorie'=>'Travaux',          'date'=>'2025-05-10','auteur'=>'Services Techniques'],
            ['titre'=>'Séance Conseil Municipal — 15 mai 2025','type'=>'video',        'categorie'=>'Séance du Conseil','date'=>'2025-05-15','auteur'=>null],
            ['titre'=>'Inauguration marché — Reportage',       'type'=>'video',        'categorie'=>'Inauguration',     'date'=>'2025-05-24','auteur'=>null],
            ['titre'=>'Délibération Conseil mai 2025',          'type'=>'deliberation', 'categorie'=>'Officiel',         'date'=>'2025-05-15','auteur'=>null],
            ['titre'=>'Budget rectificatif 2025',               'type'=>'pdf',          'categorie'=>'Financier',        'date'=>'2025-05-10','auteur'=>null],
            ['titre'=>'Arrêté n° ARR-2025-018',                 'type'=>'arrete',       'categorie'=>'Juridique',        'date'=>'2025-05-22','auteur'=>null],
        ];
        foreach ($docs as $d) {
            Document::firstOrCreate(['titre' => $d['titre']], array_merge($d, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Réclamations ──────────────────────────────────────────────────────
        $recs = [
            ['reference'=>'RCL-2025-042','objet'=>'Bruit excessif chantier nocturne',     'demandeur'=>'KOUAMÉ Sylvie','service'=>'Services Techniques','canal'=>'guichet','date'=>'2025-05-23','statut'=>'en_traitement'],
            ['reference'=>'RCL-2025-041','objet'=>"Facture d'eau incorrecte",              'demandeur'=>'AKA Martin',   'service'=>'Finances',           'canal'=>'email',  'date'=>'2025-05-20','statut'=>'repondu'],
            ['reference'=>'RCL-2025-040','objet'=>"Non délivrance d'acte de naissance",   'demandeur'=>'TRAORÉ Bakary','service'=>'État Civil',          'canal'=>'guichet','date'=>'2025-05-18','statut'=>'repondu'],
        ];
        foreach ($recs as $r) {
            Reclamation::firstOrCreate(['reference' => $r['reference']], array_merge($r, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Suggestions ───────────────────────────────────────────────────────
        $sugs = [
            ['reference'=>'SUG-2025-015','objet'=>'Installer des bancs publics Place République','citoyen'=>'AKA Martin', 'description'=>"Il n'y a aucun endroit pour s'asseoir",'date'=>'2025-05-20','statut'=>'transmis'],
            ['reference'=>'SUG-2025-014','objet'=>'Ouvrir un espace lecture à la médiathèque',  'citoyen'=>'BROU Akissi','description'=>'Accès gratuit aux livres pour tous',     'date'=>'2025-05-18','statut'=>'en_etude'],
        ];
        foreach ($sugs as $s) {
            Suggestion::firstOrCreate(['reference' => $s['reference']], array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Consultations publiques ───────────────────────────────────────────
        $cons = [
            ['titre'=>'Avis sur le nouveau PLU', 'theme'=>'Urbanisme / Aménagement','date_ouverture'=>'2025-05-15','date_cloture'=>'2025-06-15','participants'=>124,'statut'=>'actif',    'canaux'=>'En ligne + Physique'],
            ['titre'=>'Budget participatif 2026', 'theme'=>'Budget / Finances',      'date_ouverture'=>'2025-06-01','date_cloture'=>'2025-07-31','participants'=>0,  'statut'=>'programme','canaux'=>'En ligne uniquement'],
        ];
        foreach ($cons as $c) {
            ConsultationPublique::firstOrCreate(['titre' => $c['titre']], array_merge($c, ['created_at'=>now(),'updated_at'=>now()]));
        }

        // ── Historique SMS ────────────────────────────────────────────────────
        $sms = [
            ['nom'=>'Collecte ordures — Rappel', 'type'=>'info',   'message'=>'MAIRIE — Rappel : sortez vos poubelles le lundi avant 7h.','destinataires'=>'tous',         'nb_destinataires'=>12500,'date_envoi'=>'2025-05-25','statut'=>'envoye','taux_livraison'=>94],
            ['nom'=>'Taxe résidence — Relance',  'type'=>'fiscal', 'message'=>'MAIRIE — Taxe résidence 2025 due avant le 30 juin. Paiement : Orange Money 1234 ou mairie.','destinataires'=>'contribuables','nb_destinataires'=>8200,'date_envoi'=>'2025-05-20','statut'=>'envoye','taux_livraison'=>91],
            ['nom'=>'Alerte travaux Av. H-B',    'type'=>'travaux','message'=>'MAIRIE — Fermeture Av. Houphouët-Boigny du 28 au 30 mai. Emprunter itinéraires de déviation.','destinataires'=>'quartier','nb_destinataires'=>3800,'date_envoi'=>'2025-05-18','statut'=>'envoye','taux_livraison'=>97],
            ['nom'=>'Campagne vaccination juin', 'type'=>'sante',  'message'=>'MAIRIE — Campagne vaccination gratuite 10-11 juin 2025. Centres de santé municipaux.','destinataires'=>'tous','nb_destinataires'=>12500,'date_envoi'=>'2025-06-01','statut'=>'programme','taux_livraison'=>0],
        ];
        foreach ($sms as $s) {
            CampagneSms::firstOrCreate(['nom' => $s['nom']], array_merge($s, ['created_at'=>now(),'updated_at'=>now()]));
        }

        $this->command->info('Données démo insérées avec succès.');
    }
}
