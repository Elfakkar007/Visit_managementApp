<?php

namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        return view('guests.success', compact('visit'));
    }
}