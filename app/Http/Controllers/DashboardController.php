<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman yang sesuai berdasarkan perannya.
     * Versi ini sudah menggunakan Spatie Roles & Permissions.
     */
    public function index()
    {
        $user = Auth::user();

        // Keamanan: Jika user tidak memiliki profil, logout. Ini sudah bagus.
        if (!$user->profile) {
            Auth::logout();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return Redirect::route('login')->with('error', 'Akun Anda tidak lengkap. Hubungi Administrator.');
        }
        
        // --- Aturan Pengalihan Baru Berdasarkan Peran dari Spatie ---
        
        // Peran diperiksa dari yang paling spesifik/berkuasa
        if ($user->hasRole('Admin')) {
            return Redirect::route('admin.dashboard');
        }
        
        if ($user->hasRole('Resepsionis')) {
            return Redirect::route('receptionist.scanner');
        }
        
        // Untuk HRD, kita bisa arahkan ke halaman monitor khususnya
        if ($user->hasRole('HRD')) {
            return Redirect::route('requests.monitor');
        }

        if ($user->hasRole('Approver')) {
            return Redirect::route('requests.approval');
        }

        if ($user->hasRole('Staff')) {
            return Redirect::route('requests.my');
        }

        // Fallback: Jika user login tapi tidak punya peran yang dikenali, logout demi keamanan.
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return Redirect::route('login')->with('error', 'Anda tidak memiliki peran yang valid.');
    }
}