<?php

namespace App\Http\Controllers;

use App\Models\GuestVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Department;

class ReceptionistController extends Controller
{
    public function scanner()
    {
         $departments = Department::orderBy('name')->get();
        return view('receptionist.scanner', compact('departments'));
    }

    public function getVisitStatus($uuid)
    {
        $visit = GuestVisit::with('guest')->where('uuid', $uuid)->first();

        if (!$visit) {
            return response()->json(['status' => 'error', 'message' => 'Kode QR tidak valid.']);
        }

        if (!$visit->created_at->isToday()) {
             return response()->json(['status' => 'error', 'message' => 'Kode QR sudah kadaluarsa.']);
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
        // --- PERBARUI VALIDASI ---
        $validated = $request->validate([
            'uuid' => 'required|exists:guest_visits,uuid',
            'destination_department_id' => 'required|exists:departments,id',
            'destination_person_name' => 'required|string|max:255',
            'notification_duration_hours' => 'required|integer|min:1|max:24', 
        ]);

        $visit = GuestVisit::where('uuid', $validated['uuid'])->firstOrFail();

        if ($visit->status === 'waiting_check_in') {
            $department = Department::find($validated['destination_department_id']);

            $visit->update([
                'status' => 'checked_in',
                'time_in' => now(),
                'checked_in_by' => Auth::id(),
                'visit_destination' => $department->name,
                'destination_department_id' => $validated['destination_department_id'],
                'destination_person_name' => $validated['destination_person_name'],
                'notification_duration_hours' => $validated['notification_duration_hours'],
            ]);

            return response()->json(['status' => 'check_in_success', 'message' => 'Check-in berhasil untuk: ' . $visit->guest->name]);
        }
        return response()->json(['status' => 'error', 'message' => 'Gagal check-in.'], 422);
    }

    public function guestStatus()
    {
       
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

        activity()
        ->performedOn($visit)
        ->causedBy(Auth::user())
        ->log('Melihat foto KTP tamu: ' . $visit->guest->name);

        $path = Storage::disk('public')->path($visit->ktp_photo_path);
        $file = File::get($path);
        $type = File::mimeType($path);

        return response($file, 200)->header('Content-Type', $type)
        ->header('Content-Disposition', 'inline; filename="ktp.jpg"');
    }
}