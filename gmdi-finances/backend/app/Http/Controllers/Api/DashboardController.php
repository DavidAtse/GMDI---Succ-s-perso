<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\LigneBudget;
use App\Models\Recette;

class DashboardController extends Controller
{
    public function stats()
    {
        $totalRecettes = (float) Recette::where('statut', 'valide')->sum('montant');
        $totalDepenses = (float) Depense::where('statut', 'valide')->sum('montant');

        $budgetRecettes = (float) LigneBudget::where('chapitre', 'recettes')->sum('montant_previsionnel');
        $tauxExecution  = $budgetRecettes > 0 ? round(($totalRecettes / $budgetRecettes) * 100) : 0;

        $totalMM = (float) Recette::where('statut', 'valide')->where('mode_paiement', 'mobile_money')->sum('montant');
        $tauxDematerialise = $totalRecettes > 0 ? round(($totalMM / $totalRecettes) * 100) : 0;

        return response()->json([
            'totalRecettes'     => $totalRecettes,
            'totalDepenses'     => $totalDepenses,
            'tauxExecution'     => $tauxExecution,
            'tauxDematerialise' => $tauxDematerialise,
        ]);
    }
}
