<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\CongeController;
use App\Http\Controllers\Api\AbsenceController;
use App\Http\Controllers\Api\RecrutementController;
use App\Http\Controllers\Api\FormationController;
use App\Http\Controllers\Api\DepartController;
use Illuminate\Support\Facades\Route;

// Auth publique
Route::post('/login', [AuthController::class, 'login']);

// Routes protégées par Sanctum
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('agents', AgentController::class);
    Route::apiResource('conges', CongeController::class);
    Route::apiResource('absences', AbsenceController::class);
    Route::apiResource('recrutements', RecrutementController::class);
    Route::apiResource('formations', FormationController::class);
    Route::apiResource('departs', DepartController::class);
});
