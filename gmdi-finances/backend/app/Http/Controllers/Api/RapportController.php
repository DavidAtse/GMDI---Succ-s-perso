<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\Recette;
use App\Models\RecetteParService;

class RapportController extends Controller
{
    public function recettesParService()
    {
        return response()->json(RecetteParService::all());
    }

    public function situationFinanciere()
    {
        $mois = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
        $annee = date('Y');
        $data = [];
        $cumRecettes = 0;
        $cumDepenses = 0;

        for ($m = 1; $m <= (int) date('m'); $m++) {
            $recMois = (float) Recette::whereYear('date_paiement', $annee)
                ->whereMonth('date_paiement', $m)->where('statut', 'valide')->sum('montant');
            $depMois = (float) Depense::whereYear('date_paiement', $annee)
                ->whereMonth('date_paiement', $m)->where('statut', 'valide')->sum('montant');

            $cumRecettes += $recMois;
            $cumDepenses += $depMois;

            $data[] = [
                'mois'           => $mois[$m - 1],
                'recettes'       => $recMois,
                'depenses'       => $depMois,
                'cumRecettes'    => $cumRecettes,
                'cumDepenses'    => $cumDepenses,
                'excedent'       => $cumRecettes - $cumDepenses,
            ];
        }

        return response()->json($data);
    }
}
