<?php

use App\Http\Controllers\ExportController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware('auth')->prefix('export')->name('export.')->group(function () {
    // Per-event/kompetisi
    Route::get('/teams', [ExportController::class, 'exportTeams'])->name('teams');
    Route::get('/participants', [ExportController::class, 'exportParticipants'])->name('participants');

    // Global (semua event sekaligus, biasanya untuk Pimpinan)
    Route::get('/teams/global', [ExportController::class, 'exportTeamsGlobal'])->name('teams.global');
    Route::get('/participants/global', [ExportController::class, 'exportParticipantsGlobal'])->name('participants.global');
});

require __DIR__.'/auth.php';
