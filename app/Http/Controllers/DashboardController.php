<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DashboardController extends Controller
{
    /**
     * Mengarahkan pengguna ke halaman yang sesuai berdasarkan perannya.
     */
    public function index()
    {
        $user = Auth::user();

        // Keamanan: Jika user tidak memiliki profil, logout.
        if (!$user->profile) {
            Auth::logout();
            return Redirect::route('login')->with('error', 'Akun Anda tidak lengkap. Hubungi Administrator.');
        }
        
        $roleName = $user->profile->role->name;

        // --- Aturan Pengalihan Berdasarkan Peran ---
        
        if ($roleName === 'Admin') {
            return Redirect::route('admin.dashboard');
        }
        
        if ($roleName === 'Resepsionis') {
            return Redirect::route('receptionist.scanner');
        }

        if ($roleName === 'Approver') {
            return Redirect::route('requests.index');
        }

        // Default untuk peran 'Staff'
        return Redirect::route('requests.my');
    }
}