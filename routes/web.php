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
use App\Http\Controllers\Admin\GuestManagementController;

// == RUTE UTAMA & PUBLIK ==
Route::get('/', fn() => Redirect::route('login'));
Route::get('/form-tamu', [GuestVisitController::class, 'create'])->name('guest.create');
Route::post('/guest-visits', [GuestVisitController::class, 'store'])->name('guest.store');
Route::get('/guest-visits/{visit:uuid}/success', [GuestVisitController::class, 'success'])->name('guest.success');

// == RUTE UNTUK PENGGUNA TERAUTENTIKASI ==
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Modul Visit Request Internal
    Route::prefix('requests')->name('requests.')->group(function () {
        Route::get('/approval', [VisitRequestController::class, 'approval'])->name('approval')->middleware('can:approve visit requests');
        Route::get('/monitor', [VisitRequestController::class, 'monitor'])->name('monitor')->middleware('can:view monitor page');
        
        
        
        Route::middleware('can:create visit requests')->group(function() {
            Route::get('/my-requests', [VisitRequestController::class, 'myRequests'])->name('my');
            Route::get('/create', [VisitRequestController::class, 'create'])->name('create');
            Route::post('/', [VisitRequestController::class, 'store'])->name('store');
            Route::get('/{request}/print', [VisitRequestController::class, 'printSppd'])->name('print');
        });
        
    });

    // Modul Resepsionis
    Route::middleware('can:use scanner')->prefix('receptionist')->name('receptionist.')->group(function()  {
        Route::get('/scanner', [ReceptionistController::class, 'scanner'])->name('scanner');
        Route::get('/history', [ReceptionistController::class, 'history'])->name('history');
        Route::get('/guest-status', [ReceptionistController::class, 'guestStatus'])->name('guestStatus');
        Route::get('/get-visit-status/{uuid}', [ReceptionistController::class, 'getVisitStatus'])->name('getVisitStatus')->middleware('throttle:15,1');
        Route::post('/perform-check-in', [ReceptionistController::class, 'performCheckIn'])->name('performCheckIn');
        Route::get('/visits/{visit}/ktp', [ReceptionistController::class, 'showKtpImage'])->name('showKtpImage');
    });
});

// == RUTE PANEL ADMIN ==
Route::middleware(['auth', 'role:Admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data');
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/roles', fn() => view('admin.roles.index'))->name('roles.index');
    Route::get('/roles/{role}/edit', [\App\Http\Controllers\Admin\RoleController::class, 'edit'])->name('roles.edit');
    Route::put('/roles/{role}', [\App\Http\Controllers\Admin\RoleController::class, 'update'])->name('roles.update');
    Route::get('/workflows', fn() => view('admin.workflows.index'))->name('workflows.index');
    Route::get('/activities', fn() => view('admin.activities.index'))->name('activities.index');
    Route::get('/guests/status', [GuestManagementController::class, 'status'])->name('guests.status');
    Route::get('/guests/history', [GuestManagementController::class, 'history'])->name('guests.history');
    Route::get('/requests/approval', [App\Http\Controllers\VisitRequestController::class, 'approval'])->name('requests.approval');
});

require __DIR__.'/auth.php';