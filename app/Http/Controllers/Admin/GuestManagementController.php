<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GuestManagementController extends Controller
{
    // Metode ini akan menampilkan halaman status tamu
    public function status()
    {
        // Kita panggil view milik resepsionis
        return view('receptionist.guest-status');
    }

    // Metode ini akan menampilkan halaman riwayat kunjungan
    public function history()
    {
        // Kita panggil view milik resepsionis juga
        return view('receptionist.history');
    }
}