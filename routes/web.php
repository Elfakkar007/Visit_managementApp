<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VisitRequestController;
use App\Http\Controllers\GuestVisitController;
use App\Http\Controllers\ReceptionistController;
use App\Http\Controllers\DashboardController; // Controller "Pengatur Lalu Lintas"
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController; // Controller khusus dasbor admin
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ActivityLogController;
// Tambahkan controller lain untuk admin di sini nanti
// use App\Http\Controllers\Admin\UserController;
// ...

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
        Route::get('/approval', [VisitRequestController::class, 'approval'])->name('approval'); 
        Route::get('/monitor', [VisitRequestController::class, 'monitor'])->name('monitor'); 
        Route::get('/export', [VisitRequestController::class, 'export'])->name('export');   
        Route::get('/my-requests', [VisitRequestController::class, 'myRequests'])->name('my');
        Route::get('/create', [VisitRequestController::class, 'create'])->name('create');
        Route::get('/hrd-approval', [VisitRequestController::class, 'hrdApproval'])->name('hrd_approval');
        Route::post('/', [VisitRequestController::class, 'store'])->name('store');
        Route::get('/{visitRequest}', [VisitRequestController::class, 'show'])->name('show');
        Route::patch('/{visitRequest}/approve', [VisitRequestController::class, 'approve'])->name('approve');
        Route::patch('/{visitRequest}/reject', [VisitRequestController::class, 'reject'])->name('reject');
        Route::patch('/{visitRequest}/cancel', [VisitRequestController::class, 'cancel'])->name('cancel');
    });

    // Modul Resepsionis
    Route::prefix('receptionist')->name('receptionist.')->group(function() {
        Route::get('/scanner', [ReceptionistController::class, 'scanner'])->name('scanner');
        Route::get('/history', [ReceptionistController::class, 'history'])->name('history');
        Route::get('/guest-status', [ReceptionistController::class, 'guestStatus'])->name('guestStatus');
        Route::get('/get-visit-status/{uuid}', [ReceptionistController::class, 'getVisitStatus'])->name('getVisitStatus');
        Route::post('/perform-check-in', [ReceptionistController::class, 'performCheckIn'])->name('performCheckIn');
        Route::get('/visits/{visit}/ktp', [ReceptionistController::class, 'showKtpImage'])->name('showKtpImage');
    });
});


// == RUTE UNTUK PANEL ADMIN ==
Route::middleware(['auth', 'is_admin'])
    ->prefix('admin')->name('admin.')->group(function () {
        
        // Ini adalah satu-satunya rute yang benar untuk dashboard admin
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/master-data', [MasterDataController::class, 'index'])->name('master-data');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/activities', function () {return view('admin.activities.index');})->name('activities.index');
});
        

    

// Memuat rute autentikasi dari Breeze
require __DIR__.'/auth.php';
