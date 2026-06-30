<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitoyenController;
use App\Http\Controllers\OtpController;
use App\Http\Controllers\AlerteController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\RegulateurController;
use App\Http\Controllers\AmbulancierController;
use App\Http\Controllers\AmbulanceController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SignalementController;

// Pages publiques
Route::get('/', [App\Http\Controllers\PageController::class, 'home']);
Route::get('/a-propos', function () {
    return view('a-propos');
});
Route:: get('/fonctionnalites', function () {
    return view('fonctionnalites');
});
Route::get('/partenaires', function () {
    return view('partenaires');
});
Route::get('/contact', [App\Http\Controllers\PageController::class, 'show']);
Route::post('/contact', [App\Http\Controllers\PageController::class, 'contact']);
Route::get('/mentions-legales', [App\Http\Controllers\PageController::class, 'legal']);

//CITOYEN AUTH (public) 
Route::get('/citoyen/auth', [CitoyenController::class, 'auth']);
Route::post('/otp/envoyer', [OtpController::class, 'envoyer']);
Route::post('/otp/valider', [OtpController::class, 'valider']);
Route::get('/citoyen/mentions-legales', function () { return view('citoyen.mentions-legales'); });

//CITOYEN (protégé) 
Route::middleware('auth.citoyen')->group(function () {
    Route::get('/citoyen/consentement', [CitoyenController::class, 'consentement']);
    Route::post('/citoyen/consentement', [CitoyenController::class, 'validerConsentement']);
    Route::get('/citoyen/nouvelle-alerte', [AlerteController::class, 'nouvelle']);
    Route::post('/citoyen/nouvelle-alerte', [AlerteController::class, 'envoyer']);
    Route::get('/citoyen/suivi-alerte', [AlerteController::class, 'suivi']);
    Route::get('/citoyen/historique', [AlerteController::class, 'historique']);
});

//RÉGULATEUR AUTH (public) 
Route::get('/regulateur/login', [RegulateurController::class, 'login']);
Route::post('/regulateur/login', [RegulateurController::class, 'authentifier']);
Route::get('/regulateur/deconnexion', [RegulateurController::class, 'deconnexion']);

//RÉGULATEUR (protégé) 
Route::middleware('auth.regulateur')->group(function () {
    Route::post('/regulateur/flotte/{id}', [RegulateurController::class, 'updateAmbulance']);
    Route::post('/regulateur/ambulancier/ajouter', [RegulateurController::class, 'ajouterAmbulancier']);
    Route::post('/regulateur/ambulance/ajouter', [RegulateurController::class, 'ajouterAmbulance']);
    Route::post('/regulateur/ambulancier/assigner', [RegulateurController::class, 'assignerAmbulance']);
    Route::post('/regulateur/ambulancier/detacher', [RegulateurController::class, 'detacherAmbulance']);
    Route::get('/regulateur/dashboard', [RegulateurController::class, 'dashboard']);
    Route::get('/regulateur/dispatch', [RegulateurController::class, 'dispatch']);
    Route::post('/regulateur/dispatcher', [RegulateurController::class, 'dispatcher']);
    Route::get('/regulateur/flotte', [RegulateurController::class, 'flotte']);
    Route::get('/regulateur/statistiques', [RegulateurController::class, 'statistiques']);
    Route::get('/regulateur/parametres', [RegulateurController::class, 'parametres']);
    Route::post('/regulateur/parametres', [RegulateurController::class, 'changerMotDePasse']);
    Route::get('/regulateur/export-pdf', [RegulateurController::class, 'exportPdf']);
});

//AMBULANCIER AUTH (public) 
Route::get('/ambulancier/login', [AmbulancierController::class, 'login']);
Route::post('/ambulancier/login', [AmbulancierController::class, 'authentifier']);
Route::get('/ambulancier/deconnexion', [AmbulancierController::class, 'deconnexion']);

//AMBULANCIER (protégé) 
Route::middleware('auth.ambulancier')->group(function () {
    Route::get('/ambulancier/missions', [MissionController::class, 'index']);
    Route::get('/ambulancier/mission-active', [MissionController::class, 'active']);
    Route::get('/ambulancier/historique', [MissionController::class, 'historique']);
    Route::post('/ambulancier/mission/{id}/statut', [MissionController::class, 'updateStatut']);
    Route::get('/ambulancier/parametres', [AmbulancierController::class, 'parametres']);
    Route::post('/ambulancier/parametres', [AmbulancierController::class, 'changerMotDePasse']);
});

//ADMIN AUTH (public) 
Route::get('/admin/login', [AdminController::class, 'login']);
Route::post('/admin/login', [AdminController::class, 'authentifier']);
Route::get('/admin/deconnexion', [AdminController::class, 'deconnexion']);


//ADMIN (protégé)
Route::middleware('auth.admin')->group(function () {
    Route::post('/admin/utilisateurs/supprimer', [AdminController::class, 'supprimerSelection']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/export-pdf', [AdminController::class, 'exportPdf']);
    Route::get('/admin/export-csv', [AdminController::class, 'exportCsv']);
    Route::get('/admin/export-excel', [AdminController::class, 'exportExcel']);
    Route::post('/admin/territoire/{id}', [AdminController::class, 'updateCommune']);
    Route::get('/admin/utilisateurs', [AdminController::class, 'utilisateurs']);
    Route::post('/admin/utilisateurs', [AdminController::class, 'creerUtilisateur']);
    Route::get('/admin/territoire', [AdminController::class, 'territoire']);
    Route::get('/admin/moderation', [SignalementController::class, 'index']);
    Route::get('/admin/rapports', [AdminController::class, 'rapports']);
    Route::post('/admin/rapports/generer', [AdminController::class, 'genererRapport']);
    Route::post('/admin/utilisateurs/{role}/{id}/bloquer', [AdminController::class, 'bloquer']);
    Route::post('/admin/utilisateurs/{role}/{id}/debloquer', [AdminController::class, 'debloquer']);
    Route::post('/admin/territoire', [AdminController::class, 'sauvegarderTerritoire']);
    Route::post('/admin/territoire/{id}', [AdminController::class, 'sauvegarderTerritoire']);
    Route::post('/admin/territoire/{id}/hopital', [AdminController::class, 'ajouterHopital']);
    Route::post('/admin/hopital/{id}/supprimer', [AdminController::class, 'supprimerHopital']);
    Route::post('/admin/utilisateurs/{role}/{id}/modifier', [AdminController::class, 'modifier']);
    Route::post('/admin/utilisateurs/citoyen/{id}/avertir', [AdminController::class, 'avertir']);
    });
    
//AMBULANCES & SIGNALEMENTS 
Route::post('/ambulance/{id}/statut', [AmbulanceController::class, 'updateStatut']);
Route::post('/ambulance/{id}/position', [AmbulanceController::class, 'updatePosition']);
Route::post('/signalement/{id}/traiter', [SignalementController::class, 'traiter']);
Route::post('/signalement/{id}/bloquer', [SignalementController::class, 'bloquerCitoyen']);