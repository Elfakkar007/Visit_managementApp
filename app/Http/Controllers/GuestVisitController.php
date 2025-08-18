<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GuestVisitController extends Controller
{
    /**
     * Menampilkan halaman formulir untuk diisi oleh tamu.
     */
    public function create()
    {
        return view('guests.create');
    }

    /**
     * Menyimpan data tamu dan visit, lalu redirect ke halaman sukses.
     */
    public function store(Request $request)
    {
        // 1. Sesuaikan validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'company' => 'required|string|max:255',
            'visit_destination' => 'required|string|max:255', // Diubah dari destination_person
            // 'purpose' dihapus
        ]);

        try {
            DB::beginTransaction();

            $guest = Guest::firstOrCreate(
                ['phone' => $validated['phone']],
                ['name' => $validated['name'], 'company' => $validated['company']]
            );
            $guest->update([
                'name' => $validated['name'], 'company' => $validated['company'],
            ]);

            // 2. Sesuaikan pembuatan record visit
            $visit = GuestVisit::create([
                'guest_id' => $guest->id,
                'uuid' => Str::uuid(),
                'visit_destination' => $validated['visit_destination'], // Diubah dari destination_person
                // 'purpose' dihapus
                'status' => 'waiting_check_in',
            ]);
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mendaftar. Silakan coba lagi.')->withInput();
        }

        return redirect()->route('guest.success', ['visit' => $visit->uuid]);
    }
    /**
     * Menampilkan halaman sukses dengan QR Code.
     */
    public function success(GuestVisit $visit)
    {
        // Laravel otomatis akan mencari GuestVisit berdasarkan UUID karena route key name di model
        return view('guests.success', compact('visit'));
    }
}