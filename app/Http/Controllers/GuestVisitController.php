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
            'phone' => 'required|string|regex:/^(08|\+628)[0-9]{8,16}$/',
            'ktp_photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'phone.numeric' => 'Nomor telepon harus berupa angka.',
            'phone.digits_between' => 'Nomor telepon harus antara 10 hingga 15 digit.',
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
        
    
        $qrData = $visit->uuid;

        $qrCode = QrCode::size(250)->generate($qrData);


        return view('guests.success', [
            'visit' => $visit,
            'qrCode' => $qrCode
        ]);
    }
}

