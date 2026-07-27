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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Module Clients
    Route::resource('clients', ClientController::class);

    // Module Taux de change
    Route::get('/taux-changes', [TauxChangeController::class, 'index'])->name('taux-changes.index');
    Route::post('/taux-changes', [TauxChangeController::class, 'store'])
        ->middleware('role:admin')
        ->name('taux-changes.store');

    // Module Commandes
    Route::resource('commandes', CommandeController::class);

    // Module Transferts 
    Route::get('/commandes/{commande}/transferts/create', [TransfertController::class, 'create'])->name('transferts.create');
    Route::post('/commandes/{commande}/transferts', [TransfertController::class, 'store'])->name('transferts.store');

    // Modules réservés à l'admin
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{user}/toggle-actif', [UserController::class, 'toggleActif'])->name('users.toggle-actif');

        Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');

        Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    });

        Route::post('/notifications/mark-all-read', function () {
        auth()->user()->unreadNotifications->markAsRead();
        return back();
    })->name('notifications.markAllRead');
});

require __DIR__.'/auth.php';