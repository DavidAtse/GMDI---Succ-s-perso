<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\Patrimoine\BienController;
use App\Http\Controllers\Api\Patrimoine\VehiculeController;
use App\Http\Controllers\Api\Patrimoine\TerrainController;
use App\Http\Controllers\Api\Patrimoine\MobilierController;
use App\Http\Controllers\Api\Patrimoine\InformatiqueController;
use App\Http\Controllers\Api\Patrimoine\EquipementController;
use App\Http\Controllers\Api\Patrimoine\AffectationController;
use App\Http\Controllers\Api\Patrimoine\EntretienController;
use App\Http\Controllers\Api\Patrimoine\ReparationController;
use App\Http\Controllers\Api\Patrimoine\AmortissementController;
use App\Http\Controllers\Api\Patrimoine\StatsPatrimoineController;

// ── Auth publique ─────────────────────────────────────────────────────────────
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// ── Routes protégées ──────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::prefix('auth')->group(function () {
        Route::post('logout',  [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::get('me',       [AuthController::class, 'me']);
    });

    // ── Biens (inventaire général) ────────────────────────────────────────────
    Route::prefix('patrimoine/biens')->group(function () {
        Route::get('/',                     [BienController::class, 'index']);
        Route::post('/',                    [BienController::class, 'store']);
        Route::get('/reference/{ref}',      [BienController::class, 'findByReference']);
        Route::get('/{id}',                 [BienController::class, 'show']);
        Route::put('/{id}',                 [BienController::class, 'update']);
        Route::delete('/{id}',              [BienController::class, 'destroy']);
        Route::patch('/{id}/statut',        [BienController::class, 'updateStatut']);
        Route::get('/{id}/qr',              [BienController::class, 'qrCode']);
        Route::get('/{id}/fiche',           [BienController::class, 'fiche']); // PDF fiche
    });

    // ── Sous-catégories (enregistrement spécialisé → crée un Bien) ───────────
    Route::post('patrimoine/mobilier',      [MobilierController::class, 'store']);
    Route::post('patrimoine/informatique',  [InformatiqueController::class, 'store']);
    Route::post('patrimoine/equipements',   [EquipementController::class, 'store']);

    // ── Véhicules ─────────────────────────────────────────────────────────────
    Route::prefix('patrimoine/vehicules')->group(function () {
        Route::get('/',                     [VehiculeController::class, 'index']);
        Route::post('/',                    [VehiculeController::class, 'store']);
        Route::get('/{id}',                 [VehiculeController::class, 'show']);
        Route::put('/{id}',                 [VehiculeController::class, 'update']);
        Route::patch('/{id}/kilometrage',   [VehiculeController::class, 'updateKm']);
        Route::delete('/{id}',              [VehiculeController::class, 'destroy']);
    });

    // ── Terrains ──────────────────────────────────────────────────────────────
    Route::prefix('patrimoine/terrains')->group(function () {
        Route::get('/',       [TerrainController::class, 'index']);
        Route::post('/',      [TerrainController::class, 'store']);
        Route::get('/{id}',   [TerrainController::class, 'show']);
        Route::put('/{id}',   [TerrainController::class, 'update']);
        Route::delete('/{id}',[TerrainController::class, 'destroy']);
    });

    // ── Affectations & mouvements ─────────────────────────────────────────────
    Route::prefix('patrimoine/affectations')->group(function () {
        Route::get('/',       [AffectationController::class, 'index']);
        Route::post('/',      [AffectationController::class, 'store']);
        Route::get('/export', [AffectationController::class, 'export']);
    });

    // ── Entretiens ────────────────────────────────────────────────────────────
    Route::prefix('patrimoine/entretiens')->group(function () {
        Route::get('/',                   [EntretienController::class, 'index']);
        Route::post('/',                  [EntretienController::class, 'store']);
        Route::get('/{id}',               [EntretienController::class, 'show']);
        Route::delete('/{id}',            [EntretienController::class, 'destroy']);
        Route::patch('/{id}/valider',     [EntretienController::class, 'valider']);
    });

    // ── Réparations ───────────────────────────────────────────────────────────
    Route::prefix('patrimoine/reparations')->group(function () {
        Route::get('/',                   [ReparationController::class, 'index']);
        Route::post('/',                  [ReparationController::class, 'store']);
        Route::get('/{id}',               [ReparationController::class, 'show']);
        Route::patch('/{id}/resoudre',    [ReparationController::class, 'resoudre']);
    });

    // ── Amortissements ────────────────────────────────────────────────────────
    Route::prefix('patrimoine/amortissements')->group(function () {
        Route::get('/',        [AmortissementController::class, 'index']);
        Route::post('/simuler',[AmortissementController::class, 'simuler']);
    });

    // ── Statistiques & export ─────────────────────────────────────────────────
    Route::prefix('patrimoine')->group(function () {
        Route::get('statistiques', [StatsPatrimoineController::class, 'dashboard']);
        Route::get('export',       [StatsPatrimoineController::class, 'export']);
    });
});
