<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// 1. TAMBAHKAN USE STATEMENT UNTUK QR CODE
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GuestVisitController extends Controller
{
    public function create()
    {
        return view('guests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'company'   => 'required|string|max:255',
            'phone'     => 'required|string|max:20',
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $guest = Guest::updateOrCreate(
            ['phone' => $validated['phone']],
            ['name' => $validated['name'], 'company' => $validated['company']]
        );

        $ktpPath = $request->file('ktp_photo')->store('ktp_photos', 'public');

        // Ganti 'uuid' menjadi 'unique_id' agar lebih deskriptif jika Anda mau, tapi 'uuid' juga tidak apa-apa
        $visit = GuestVisit::create([
            'guest_id'       => $guest->id,
            'uuid'           => (string) Str::uuid(),
            'ktp_photo_path' => $ktpPath,
            'status'         => 'waiting_check_in',
        ]);

        return redirect()->route('guest.success', $visit->uuid);
    }

    public function success($uuid)
    {
        $visit = GuestVisit::where('uuid', $uuid)->firstOrFail();
        
        // 2. DATA YANG AKAN DIJADIKAN QR CODE
        // Ini adalah data yang akan discan oleh resepsionis
        $qrData = $visit->uuid;

        // 3. GENERATE QR CODE SEBAGAI STRING SVG
        // Format SVG lebih tajam dan ringan
        $qrCode = QrCode::size(250)->generate($qrData);

        // 4. KIRIM DATA VISIT DAN QR CODE KE VIEW
        return view('guests.success', [
            'visit' => $visit,
            'qrCode' => $qrCode
        ]);
    }
}

