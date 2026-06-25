<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Agent;
use App\Models\Conge;
use App\Models\Absence;
use App\Models\Recrutement;
use App\Models\Formation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Utilisateur admin DRH
        User::create([
            'name'     => 'Mariam Ouattara',
            'email'    => 'drh@mairie.ci',
            'password' => Hash::make('password'),
            'role'     => 'drh',
        ]);

        // Agents
        $agents = [
            ['matricule'=>'EC-001','nom_complet'=>'TRAORÉ Adjoa','poste'=>'Chef Service État Civil','direction'=>'Direction État Civil','type_contrat'=>'fonctionnaire','categorie'=>'A','grade'=>'Administrateur Principal','date_embauche'=>'2010-03-01','date_naissance'=>'1978-06-12','genre'=>'F','telephone'=>'+225 07 00 00 00 10','email'=>'chef.etatcivil@mairie.ci','statut'=>'actif','salaire_brut'=>450000,'conges_restants'=>22,'situation_familiale'=>'Marié(e)','diplome'=>'Master'],
            ['matricule'=>'FIN-001','nom_complet'=>'BAMBA Lassina','poste'=>'Directeur Financier','direction'=>'Direction Finances','type_contrat'=>'fonctionnaire','categorie'=>'A','grade'=>'Administrateur Hors Classe','date_embauche'=>'2008-09-15','date_naissance'=>'1975-02-28','genre'=>'M','telephone'=>'+225 07 00 00 00 20','email'=>'chef.finances@mairie.ci','statut'=>'actif','salaire_brut'=>580000,'conges_restants'=>18,'situation_familiale'=>'Marié(e)','diplome'=>'Master'],
            ['matricule'=>'RH-002','nom_complet'=>'DIOMANDÉ Karim','poste'=>'Gestionnaire RH','direction'=>'DRH','type_contrat'=>'fonctionnaire','categorie'=>'B','grade'=>'Attaché Principal','date_embauche'=>'2015-01-05','date_naissance'=>'1985-08-19','genre'=>'M','telephone'=>'+225 07 00 00 00 31','email'=>'rh.agent@mairie.ci','statut'=>'conge','salaire_brut'=>280000,'conges_restants'=>5,'situation_familiale'=>'Célibataire','diplome'=>'Licence'],
            ['matricule'=>'ST-002','nom_complet'=>'YAPI Kouadio Fernand','poste'=>'Chef Équipe Voirie','direction'=>'Services Techniques','type_contrat'=>'fonctionnaire','categorie'=>'C','grade'=>'Technicien','date_embauche'=>'2012-06-20','date_naissance'=>'1982-11-04','genre'=>'M','telephone'=>'+225 07 00 00 00 40','email'=>'voirie@mairie.ci','statut'=>'actif','salaire_brut'=>210000,'conges_restants'=>28,'situation_familiale'=>'Marié(e)','diplome'=>'BTS / DUT'],
            ['matricule'=>'URB-002','nom_complet'=>'GBANÉ Awa','poste'=>'Instructrice Permis','direction'=>'Direction Urbanisme','type_contrat'=>'contractuel','categorie'=>'B','grade'=>'Attaché','date_embauche'=>'2018-04-10','date_naissance'=>'1990-03-22','genre'=>'F','telephone'=>'+225 07 00 00 00 50','email'=>'permis@mairie.ci','statut'=>'actif','salaire_brut'=>260000,'conges_restants'=>15,'situation_familiale'=>'Célibataire','diplome'=>'Licence'],
            ['matricule'=>'STG-001','nom_complet'=>'KOUAMÉ Brice','poste'=>'Stagiaire Informatique','direction'=>'DSI','type_contrat'=>'stage','categorie'=>'Stagiaire','grade'=>'Stagiaire','date_embauche'=>'2025-04-01','date_naissance'=>'2001-07-15','genre'=>'M','telephone'=>'+225 07 11 22 33 44','email'=>'stagiaire@mairie.ci','statut'=>'actif','salaire_brut'=>50000,'conges_restants'=>0,'situation_familiale'=>'Célibataire','diplome'=>'BTS / DUT'],
        ];
        foreach ($agents as $a) {
            Agent::create($a);
        }

        // Congés
        Conge::create(['matricule'=>'RH-002','agent'=>'DIOMANDÉ Karim','type'=>'annuel','date_debut'=>'2025-05-20','duree'=>14,'statut'=>'approuve']);
        Conge::create(['matricule'=>'EC-001','agent'=>'TRAORÉ Adjoa','type'=>'maladie','date_debut'=>'2025-06-02','duree'=>3,'statut'=>'soumis']);
        Conge::create(['matricule'=>'ST-002','agent'=>'YAPI Kouadio','type'=>'paternite','date_debut'=>'2025-07-01','duree'=>10,'statut'=>'en_cours']);

        // Absences
        Absence::create(['matricule'=>'ST-002','agent'=>'YAPI Kouadio','date'=>'2025-05-23','motif'=>'Maladie non justifiée','justifie'=>false]);
        Absence::create(['matricule'=>'URB-002','agent'=>'GBANÉ Awa','date'=>'2025-05-24','motif'=>'Certificat médical','justifie'=>true]);

        // Recrutements
        Recrutement::create(['poste'=>'Agent de saisie État Civil','direction'=>'Direction État Civil','nb_postes'=>2,'type'=>'concours','cloture'=>'2025-05-31','candidatures'=>14,'statut'=>'en_cours']);
        Recrutement::create(['poste'=>'Technicien Voirie','direction'=>'Services Techniques','nb_postes'=>3,'type'=>'direct','cloture'=>'2025-04-30','candidatures'=>28,'statut'=>'termine']);

        // Formations
        Formation::create(['titre'=>'Formation GMDI — Module État Civil','organisme'=>'DSI Mairie','date_debut'=>'2025-06-10','date_fin'=>'2025-06-12','agents'=>'EC-001, RH-002','cout'=>0,'statut'=>'programme']);
        Formation::create(['titre'=>'Gestion financière des collectivités','organisme'=>'CGECI','date_debut'=>'2025-07-01','date_fin'=>'2025-07-05','agents'=>'FIN-001','cout'=>250000,'statut'=>'programme']);
    }
}
