<?php

namespace App\Http\Controllers;

use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function scanner()
    {
        return view('receptionist.scanner');
    }

    public function processScan(Request $request)
    {
        $request->validate([
            'uuid' => 'required|string|exists:guest_visits,uuid'
        ], [
            'uuid.exists' => 'Kode QR tidak ditemukan di dalam sistem.'
        ]);

        $visit = GuestVisit::where('uuid', $request->uuid)->firstOrFail();
        $guestName = $visit->guest->name;

        if ($visit->status === 'waiting_check_in') {
            $visit->update([
                'status' => 'checked_in', 'time_in' => now(), 'checked_in_by' => Auth::id(),
            ]);
            return redirect()->route('receptionist.scanner')->with('success', "Check-in berhasil untuk tamu: {$guestName}.");
        }

        if ($visit->status === 'checked_in') {
            $visit->update([
                'status' => 'checked_out', 'time_out' => now(), 'checked_out_by' => Auth::id(),
            ]);
            return redirect()->route('receptionist.scanner')->with('success', "Check-out berhasil untuk tamu: {$guestName}.");
        }

        return redirect()->route('receptionist.scanner')->with('error', "Kode QR untuk tamu {$guestName} sudah check-out.");
    }
    
    public function history()
    {
        $visits = GuestVisit::with('guest', 'checkedInBy', 'checkedOutBy')
                    ->latest('id')
                    ->paginate(15);
        return view('receptionist.history', compact('visits'));
    }
}