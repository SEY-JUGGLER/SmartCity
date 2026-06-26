<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\Agent\ClassementPerformance;
use App\Livewire\Agent\Dashboard as AgentDashboard;
use App\Livewire\Agent\DetailMission;
use App\Livewire\Agent\HistoriqueInterventions;
use App\Livewire\Agent\MesMateriels;
use App\Livewire\Agent\MesMissions;
use App\Livewire\Agent\NavigationCarte;
use App\Livewire\Agent\Notifications as AgentNotifications;
use App\Livewire\Agent\Pointage;
use App\Livewire\Agent\ProfilAgent;
use App\Livewire\Agent\SupportRequests;
use App\Livewire\Citoyen\ClassementEngagement;
use App\Livewire\Citoyen\CreerSignalement;
use App\Livewire\Citoyen\Dashboard as CitoyenDashboard;
use App\Livewire\Citoyen\DetailSignalement;
use App\Livewire\Citoyen\Historique;
use App\Livewire\Citoyen\MesSignalements;
use App\Livewire\Citoyen\Notifications as CitoyenNotifications;
use App\Livewire\Citoyen\Profil as CitoyenProfil;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/admin/rapports/{rapport}/print', [\App\Http\Controllers\RapportController::class, 'print'])
        ->name('rapport.print');
});

Route::middleware(['auth', 'role:AGENT'])->prefix('agent')->name('agent.')->group(function () {
    Route::get('/live-dashboard', AgentDashboard::class)->name('dashboard');
    Route::get('/mes-missions', MesMissions::class)->name('missions.index');
    Route::get('/missions/{signalement}', DetailMission::class)->name('missions.show');
    Route::get('/historique', HistoriqueInterventions::class)->name('historique');
    Route::get('/pointage', Pointage::class)->name('pointage');
    Route::get('/support', SupportRequests::class)->name('support');
    Route::get('/materiels', MesMateriels::class)->name('materiels');
    Route::get('/carte', NavigationCarte::class)->name('carte');
    Route::get('/notifications', AgentNotifications::class)->name('notifications');
    Route::get('/profil', ProfilAgent::class)->name('profil');
    Route::get('/classement', ClassementPerformance::class)->name('classement');
});

Route::middleware(['auth', 'role:CITOYEN'])->prefix('citoyen')->name('citoyen.')->group(function () {
    Route::get('/dashboard', CitoyenDashboard::class)->name('dashboard');
    Route::get('/signalements', MesSignalements::class)->name('signalements.index');
    Route::get('/signalements/create', CreerSignalement::class)->name('signalements.create');
    Route::get('/signalements/{signalement}', DetailSignalement::class)->name('signalements.show');
    Route::get('/historique', Historique::class)->name('historique');
    Route::get('/notifications', CitoyenNotifications::class)->name('notifications');
    Route::get('/profil', CitoyenProfil::class)->name('profil');
    Route::get('/classement', ClassementEngagement::class)->name('classement');
});
