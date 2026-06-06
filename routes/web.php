<?php

use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Operation\TeamController;
use App\Http\Controllers\Operation\TimelineController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Middleware 'auth' memastikan hanya staff yang sudah login yang bisa akses
Route::middleware(['auth'])->group(function () {

    // Kelompok Rute Operasional Panitia Lomba (UC-04 & UC-05)
    Route::prefix('operation')->group(function () {

        // REQ-07 & REQ-08: Manajemen Daftar Tim dan Verifikasi Berkas
        Route::get('/teams', [TeamController::class, 'index'])->name('operation.teams.index');
        Route::get('/teams/{id}', [TeamController::class, 'show'])->name('operation.teams.show');
        Route::post('/teams/{id}/verify', [TeamController::class, 'updateStatus'])->name('operation.teams.verify');
        Route::post('/teams/{teamId}/members/{userId}/verify', [TeamController::class, 'updateMemberStatus'])->name('operation.teams.verifyMember');

        // REQ-10: Pengelolaan Lini Masa Kompetisi (CRUD Timeline)
        Route::resource('timeline', TimelineController::class);
    });

    // REQ-09: Implementasi Pengunci Data (Data Freezing)
    // Rute di bawah ini akan menggunakan 'gembok' yang sudah Anda buat
    Route::middleware(['data_frozen'])->group(function () {
        // Contoh: Rute peserta untuk mengubah data tim akan dipasang di sini
        // Route::post('/team/update', [ParticipantController::class, 'update']);
    });

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('admin')->name('admin.')->middleware('verified')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        Route::get('/staff', [AdminDashboardController::class, 'staff'])->name('staff.index');
        Route::get('/transactions', [AdminDashboardController::class, 'transactions'])->name('transactions.index');
        Route::patch('/transactions/{team}/accept', [AdminDashboardController::class, 'acceptTransaction'])->name('transactions.accept');
        Route::patch('/transactions/{team}/reject', [AdminDashboardController::class, 'rejectTransaction'])->name('transactions.reject');
        Route::get('/files-participants', [AdminDashboardController::class, 'filesParticipants'])->name('files-participants.index');
        Route::get('/timelines', [AdminDashboardController::class, 'timelines'])->name('timelines.index');
    });

    Route::post('/transaction/{teamId}/verify', [TransactionController::class, 'verify']);
    Route::get('/transaction/recap', [TransactionController::class, 'getRecap']);
});

require __DIR__.'/auth.php';
