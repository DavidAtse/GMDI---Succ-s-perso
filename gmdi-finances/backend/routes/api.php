<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\ComptabiliteController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\DepenseController;
use App\Http\Controllers\Api\RapportController;
use App\Http\Controllers\Api\RecetteController;
use App\Http\Controllers\Api\TresorerieController;
use Illuminate\Support\Facades\Route;

// ── Authentification (public) ─────────────────────────────────────
Route::post('/auth/login', [AuthController::class, 'login']);

// ── Routes protégées (Sanctum) ────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me',      [AuthController::class, 'me']);

    // Dashboard
    Route::get('/dashboard/stats', [DashboardController::class, 'stats']);

    // Recettes
    Route::get('/recettes',              [RecetteController::class, 'index']);
    Route::post('/recettes',             [RecetteController::class, 'store']);
    Route::patch('/recettes/{id}/encaisser', [RecetteController::class, 'encaisser']);

    // Dépenses
    Route::get('/depenses',              [DepenseController::class, 'index']);
    Route::post('/depenses',             [DepenseController::class, 'store']);
    Route::patch('/depenses/{id}/payer', [DepenseController::class, 'payer']);

    // Budget
    Route::get('/budget/lignes',              [BudgetController::class, 'lignes']);
    Route::post('/budget/lignes',             [BudgetController::class, 'ajouterLigne']);
    Route::post('/budget/revisions',          [BudgetController::class, 'revision']);
    Route::get('/budget/execution-mensuelle', [BudgetController::class, 'executionMensuelle']);

    // Comptabilité
    Route::get('/comptabilite/ecritures',  [ComptabiliteController::class, 'ecritures']);
    Route::post('/comptabilite/ecritures', [ComptabiliteController::class, 'ajouterEcriture']);
    Route::get('/comptabilite/comptes',    [ComptabiliteController::class, 'comptes']);

    // Trésorerie
    Route::get('/tresorerie/mouvements-caisse', [TresorerieController::class, 'mouvementsCaisse']);
    Route::get('/tresorerie/mouvements-banque', [TresorerieController::class, 'mouvementsBanque']);

    // Rapports
    Route::get('/rapports/recettes-par-service',  [RapportController::class, 'recettesParService']);
    Route::get('/rapports/situation-financiere',  [RapportController::class, 'situationFinanciere']);
});
