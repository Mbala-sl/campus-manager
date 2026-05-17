<?php

use App\Http\Controllers\AnneeAcademiqueController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FiliereController;
use App\Http\Controllers\InscriptionController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

// ── Landing ──────────────────────────────────────
Route::get('/', fn() => view('welcome'))->name('home');

// ── Auth (invités uniquement) ────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login',     [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'login']);
    Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// ── Zone protégée ────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/search',  SearchController::class)->name('search');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Étudiants
    Route::get('/students',              [StudentController::class, 'index'])->name('students.index');
    Route::post('/students',             [StudentController::class, 'store'])->name('students.store');
    Route::put('/students/{student}',    [StudentController::class, 'update'])->name('students.update');
    Route::delete('/students/{student}', [StudentController::class, 'destroy'])->name('students.destroy');

    // Filières
    Route::get('/filieres',              [FiliereController::class, 'index'])->name('filieres.index');
    Route::post('/filieres',             [FiliereController::class, 'store'])->name('filieres.store');
    Route::put('/filieres/{filiere}',    [FiliereController::class, 'update'])->name('filieres.update');
    Route::delete('/filieres/{filiere}', [FiliereController::class, 'destroy'])->name('filieres.destroy');

    // Inscriptions
    Route::get('/inscriptions',                  [InscriptionController::class, 'index'])->name('inscriptions.index');
    Route::post('/inscriptions',                 [InscriptionController::class, 'store'])->name('inscriptions.store');
    Route::put('/inscriptions/{inscription}',    [InscriptionController::class, 'update'])->name('inscriptions.update');
    Route::delete('/inscriptions/{inscription}', [InscriptionController::class, 'destroy'])->name('inscriptions.destroy');

    // Années académiques
    Route::get('/annees',            [AnneeAcademiqueController::class, 'index'])->name('annees.index');
    Route::post('/annees',           [AnneeAcademiqueController::class, 'store'])->name('annees.store');
    Route::put('/annees/{annee}',    [AnneeAcademiqueController::class, 'update'])->name('annees.update');
    Route::delete('/annees/{annee}', [AnneeAcademiqueController::class, 'destroy'])->name('annees.destroy');

    // Pages verrouillées (coming soon)
    $locked = fn(string $page, string $desc = '') => view('locked', ['page' => $page, 'description' => $desc]);

    Route::get('/utilisateurs',   fn() => $locked('Utilisateurs', 'Gérez les comptes et les rôles des administrateurs.'))->name('utilisateurs');
    Route::get('/paiements',      fn() => $locked('Paiements', 'Suivez et gérez les paiements des frais de scolarité.'))->name('paiements');
    Route::get('/exportations',   fn() => $locked('Exportations', 'Exportez vos données en CSV, Excel ou PDF.'))->name('exportations');
    Route::get('/journal',        fn() => $locked('Journal d\'activité', 'Consultez l\'historique complet des actions effectuées.'))->name('journal');
    Route::get('/statistiques',   fn() => $locked('Statistiques', 'Analysez les tendances et performances de votre université.'))->name('statistiques');
    Route::get('/notifications',  fn() => $locked('Notifications', 'Configurez et envoyez des alertes aux étudiants et admins.'))->name('notifications');
    Route::get('/parametres',     fn() => $locked('Paramètres', 'Personnalisez les préférences de votre établissement.'))->name('parametres');
});
