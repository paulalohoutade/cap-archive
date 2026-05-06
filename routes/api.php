<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DocumentController;

/*
|--------------------------------------------------------------------------
| Routes API — ArchiveXA
|--------------------------------------------------------------------------
|
| Toutes les routes sont préfixées par /api (défini dans bootstrap/app.php).
| Les routes protégées nécessitent une session active (middleware 'auth').
|
*/

// ── Routes publiques (sans authentification) ──────────────────────────
Route::post('/login', [AuthController::class, 'login']);
// Accessible sans session (appelé par n8n)
Route::post('/ingest', [DocumentController::class, 'ingest']);

// ── Routes protégées (authentification requise) ───────────────────────
Route::middleware('auth:web')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    // Documents — ressource complète
    Route::get('/documents', [DocumentController::class, 'index']);
    Route::post('/documents', [DocumentController::class, 'store']);
    Route::get('/documents/{document}', [DocumentController::class, 'show']);
    Route::delete('/documents/{document}', [DocumentController::class, 'destroy']);
    Route::get('/documents/{document}/download', [DocumentController::class, 'download']);

    // Statistiques pour le dashboard
    Route::get('/stats', [DocumentController::class, 'stats']);
});
