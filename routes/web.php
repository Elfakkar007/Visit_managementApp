<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitRequestController;
use App\Http\Controllers\GuestVisitController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\DashboardController; // Controller untuk "Pengatur Lalu Lintas"
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\LevelController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubsidiaryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // Controller untuk dasbor admin

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// == RUTE UTAMA & PUBLIK ==
Route::get('/', function () {
    return Redirect::route('login');
});

Route::get('/form-tamu', [GuestVisitController::class, 'create'])->name('guest.create');
Route::post('/guest-visits', [GuestVisitController::class, 'store'])->name('guest.store');
Route::get('/guest-visits/{visit:uuid}/success', [GuestVisitController::class, 'success'])->name('guest.success');


// == RUTE UNTUK PENGGUNA TERAUTENTIKASI ==
Route::middleware('auth')->group(function () {
    // Rute ini memanggil "Pengatur Lalu Lintas" setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Halaman Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul Visit Request Internal
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/', [VisitRequestController::class, 'index'])->name('index');
        Route::get('/my-requests', [VisitRequestController::class, 'myRequests'])->name('my');
        Route::get('/create', [VisitRequestController::class, 'create'])->name('create');
        Route::get('/hrd-approval', [VisitRequestController::class, 'hrdApproval'])->name('hrd_approval');
        Route::post('/', [VisitRequestController::class, 'store'])->name('store');
        Route::get('/{visitRequest}', [VisitRequestController::class, 'show'])->name('show');
        Route::patch('/{visitRequest}/approve', [VisitRequestController::class, 'approve'])->name('approve');
        Route::patch('/{visitRequest}/reject', [VisitRequestController::class, 'reject'])->name('reject');
        Route::delete('/{visitRequest}', [VisitRequestController::class, 'destroy'])->name('destroy');
    });
});


// == RUTE UNTUK RESEPSIONIS ==
Route::middleware(['auth', 'check.receptionist'])
    ->prefix('receptionist')->name('receptionist.')->group(function () {
        Route::get('/scanner', [ReceptionistController::class, 'scanner'])->name('scanner');
        Route::post('/process-scan', [ReceptionistController::class, 'processScan'])->name('processScan');
        Route::get('/history', [ReceptionistController::class, 'history'])->name('history');
    });


// == RUTE UNTUK PANEL ADMIN ==
Route::middleware(['auth', 'admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        // PERBAIKAN DI SINI: Gunakan AdminDashboardController
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('departments', DepartmentController::class);
        Route::resource('levels', LevelController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('subsidiaries', SubsidiaryController::class);
        Route::resource('users', UserController::class);
    });

// Memuat rute autentikasi dari Breeze
require __DIR__.'/auth.php';