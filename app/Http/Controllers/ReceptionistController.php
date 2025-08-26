<?php

namespace App\Http\Controllers;

use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage; 

class ReceptionistController extends Controller
{
    public function scanner()
    {
        return view('receptionist.scanner');
    }

    public function getVisitStatus($uuid)
    {
        $visit = GuestVisit::with('guest')->where('uuid', $uuid)->first();

        if (!$visit) {
            return response()->json(['status' => 'error', 'message' => 'Kode QR tidak valid.']);
        }

        switch ($visit->status) {
            case 'waiting_check_in':
                return response()->json(['status' => 'needs_check_in', 'guest_name' => $visit->guest->name, 'uuid' => $visit->uuid]);
            case 'checked_in':
                $visit->update(['status' => 'checked_out', 'time_out' => now(), 'checked_out_by' => Auth::id()]);
                return response()->json(['status' => 'checked_out_success', 'message' => "Check-out berhasil: {$visit->guest->name}."]);
            case 'checked_out':
                return response()->json(['status' => 'already_checked_out', 'message' => "Tamu {$visit->guest->name} sudah check-out."]);
        }
         return response()->json(['status' => 'error', 'message' => 'Status tidak diketahui.'], 400);
    }

    public function performCheckIn(Request $request)
    {
        $request->validate(['uuid' => 'required|exists:guest_visits,uuid', 'visit_destination' => 'required|string|max:255']);
        $visit = GuestVisit::where('uuid', $request->uuid)->firstOrFail();

        if ($visit->status === 'waiting_check_in') {
            $visit->update(['status' => 'checked_in', 'time_in' => now(), 'checked_in_by' => Auth::id(), 'visit_destination' => $request->visit_destination]);
            return response()->json(['status' => 'check_in_success', 'message' => 'Check-in berhasil untuk: ' . $visit->guest->name]);
        }
        return response()->json(['status' => 'error', 'message' => 'Gagal check-in.'], 422);
    }

    public function guestStatus()
{
    // Ambil data tamu yang statusnya 'waiting_check_in' atau 'checked_in'
     return view('receptionist.guest-status');
}


    public function history()
    {
        return view('receptionist.history');
    }

    public function showKtpImage(GuestVisit $visit)
    {
        // Pastikan file ada di storage
        if (!Storage::disk('public')->exists($visit->ktp_photo_path)) {
            abort(404);
        }

        // Ambil path lengkap ke file
        $path = Storage::disk('public')->path($visit->ktp_photo_path);
        
        // Ambil tipe mime file (misal: image/jpeg)
        $mime = File::mimeType($path);

        // Kembalikan file sebagai response dengan header yang benar
        return response()->file($path, ['Content-Type' => $mime]);
    }
}