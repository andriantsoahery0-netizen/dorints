<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TauxChangeController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\TransfertController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\RechercheController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SauvegardeController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Module Clients
    Route::get('/clients/export', [ClientController::class, 'export'])->name('clients.export');
    Route::resource('clients', ClientController::class);

    // Module Taux de change
    Route::get('/taux-changes', [TauxChangeController::class, 'index'])->name('taux-changes.index');
    Route::post('/taux-changes', [TauxChangeController::class, 'store'])
        ->middleware('role:admin')
        ->name('taux-changes.store');

    // Module Commandes
    Route::get('/commandes/export', [CommandeController::class, 'export'])->name('commandes.export');
    Route::resource('commandes', CommandeController::class);
    Route::post('/commandes/{commande}/valider', [CommandeController::class, 'valider'])->name('commandes.valider');

    // Factures
    Route::get('/factures', [FactureController::class, 'index'])->name('factures.index');
    Route::get('/factures/export', [FactureController::class, 'export'])->name('factures.export');
    Route::get('/recherche', [RechercheController::class, 'index'])->name('recherche');
    Route::get('/factures/{facture}', [FactureController::class, 'show'])->name('factures.show');
    Route::get('/factures/{facture}/pdf', [FactureController::class, 'pdf'])->name('factures.pdf');
    Route::post('/factures/{facture}/relancer-email', [FactureController::class, 'relancerEmail'])->name('factures.relancer-email');
    Route::get('/factures/{facture}/relancer-whatsapp', [FactureController::class, 'relancerWhatsapp'])->name('factures.relancer-whatsapp');

    // Module Transferts 
    Route::get('/commandes/{commande}/transferts/create', [TransfertController::class, 'create'])->name('transferts.create');
    Route::post('/commandes/{commande}/transferts', [TransfertController::class, 'store'])->name('transferts.store');

    // Modules réservés à l'admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{user}/ticket', [UserController::class, 'ticket'])->name('users.ticket');
        Route::post('/users/{user}/nouveau-ticket', [UserController::class, 'nouveauTicket'])->name('users.nouveau-ticket');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-actif', [UserController::class, 'toggleActif'])->name('users.toggle-actif');

        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
        Route::get('/rapports/export', [RapportController::class, 'export'])->name('rapports.export');

        Route::get('/sauvegarde', [SauvegardeController::class, 'index'])->name('sauvegarde.index');
        Route::get('/sauvegarde/telecharger', [SauvegardeController::class, 'telecharger'])->name('sauvegarde.telecharger');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

    // Notifications : liste, ouverture d'une notification, tout marquer comme lu
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'ouvrir'])->name('notifications.ouvrir');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'toutMarquerLu'])->name('notifications.markAllRead');
});

require __DIR__.'/auth.php';