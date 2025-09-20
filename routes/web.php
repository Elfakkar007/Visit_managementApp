<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitRequestController;
use App\Http\Controllers\GuestVisitController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\GuestManagementController;

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

    // Halaman Profil Pengguna (bisa diakses semua user yang login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul Visit Request Internal
    Route::prefix('requests')->name('requests.')->group(function () {
        // Rute-rute ini dilindungi oleh izin spesifik
        Route::get('/approval', [VisitRequestController::class, 'approval'])->name('approval')->middleware('can:approve visit requests');
        Route::get('/monitor', [VisitRequestController::class, 'monitor'])->name('monitor')->middleware('can:view monitor page');
        Route::get('/hrd-approval', [VisitRequestController::class, 'hrdApproval'])->name('hrd_approval')->middleware('can:view monitor page'); // Diasumsikan sama dengan monitor
        
        Route::patch('/{visitRequest}/approve', [VisitRequestController::class, 'approve'])->name('approve')->middleware('can:approve visit requests');
        Route::patch('/{visitRequest}/reject', [VisitRequestController::class, 'reject'])->name('reject')->middleware('can:approve visit requests');
        
        Route::middleware('can:create visit requests')->group(function() {
        Route::get('/my-requests', [VisitRequestController::class, 'myRequests'])->name('my');
        Route::get('/create', [VisitRequestController::class, 'create'])->name('create');
        Route::post('/', [VisitRequestController::class, 'store'])->name('store');
        Route::get('/{visitRequest}', [VisitRequestController::class, 'show'])->name('show');
        Route::patch('/{visitRequest}/cancel', [VisitRequestController::class, 'cancel'])->name('cancel');
        });
        
        // Rute export bisa dilindungi jika perlu
        Route::get('/export', [VisitRequestController::class, 'export'])->name('export')->middleware('can:view all visit requests');
    });

    // Modul Resepsionis (hanya untuk Role Resepsionis)
    Route::middleware('role:Resepsionis')->prefix('receptionist')->name('receptionist.')->group(function() {
        Route::get('/scanner', [ReceptionistController::class, 'scanner'])->name('scanner');
        Route::get('/history', [ReceptionistController::class, 'history'])->name('history');
        Route::get('/guest-status', [ReceptionistController::class, 'guestStatus'])->name('guestStatus');
        Route::get('/get-visit-status/{uuid}', [ReceptionistController::class, 'getVisitStatus'])->name('getVisitStatus');
        Route::post('/perform-check-in', [ReceptionistController::class, 'performCheckIn'])->name('performCheckIn');
        Route::get('/visits/{visit}/ktp', [ReceptionistController::class, 'showKtpImage'])->name('showKtpImage');
    });
});


// == RUTE UNTUK PANEL ADMIN (hanya untuk Role Admin) ==
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/roles', function() {return view('admin.roles.index');})->name('roles.index');
    Route::get('/roles/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
    Route::get('/workflows', function() {return view('admin.workflows.index');})->name('workflows.index');
    Route::get('/activities', function () {return view('admin.activities.index');})->name('activities.index');
    Route::get('/guests/status', [GuestManagementController::class, 'status'])->name('guests.status');
    Route::get('/guests/history', [GuestManagementController::class, 'history'])->name('guests.history');
});

// Memuat rute autentikasi dari Breeze
require __DIR__.'/auth.php';