<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\VisitRequest;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class VisitRequestController extends Controller
{
    /**
     * Menampilkan halaman approval utama.
     */
    public function approval()
    {
        $this->authorize('approve visit requests');
        return view('visit_requests.approval');
    }

    /**
     * Menampilkan halaman monitor untuk semua request.
     */
    public function monitor()
    {
        $this->authorize('view monitor page');
        return view('visit_requests.monitor');
    }

    /**
     * Menampilkan halaman "Request Saya".
     */
    public function myRequests()
    {
        $this->authorize('create visit requests');
        return view('visit_requests.my_requests');
    }

    /**
     * Menampilkan form untuk membuat request baru.
     */
    public function create()
    {
        $this->authorize('create visit requests');
        $destinations = Destination::orderBy('name')->get(); 
        return view('visit_requests.create', compact('destinations')); 
    }

    /**
     * Menyimpan request baru ke database.
     */
    public function store(Request $request)
    {
         $this->authorize('create visit requests');
    
        $validated = $request->validate([
            'destination_option' => 'required|string',
            'destination_custom' => 'required_if:destination_option,other|nullable|string|max:255',
            'purpose'     => 'required|string',
            'from_date'   => 'required|date',
            'to_date'     => 'required|date|after_or_equal:from_date',
        ]);

        $pendingStatus = Status::where('name', 'Pending')->firstOrFail();
        
        // Logika untuk menentukan tujuan final
        $finalDestination = $validated['destination_option'] === 'other' 
            ? $validated['destination_custom'] 
            : $validated['destination_option'];

        $visitRequest = VisitRequest::create([
            'user_id'     => Auth::id(),
            'status_id'   => $pendingStatus->id,
            'destination' => $finalDestination, // Simpan tujuan final
            'purpose'     => $validated['purpose'],
            'from_date'   => $validated['from_date'],
            'to_date'     => $validated['to_date'],
        ]);

        // Kirim notifikasi ke approver yang relevan
        $approvers = Auth::user()->getApprovers();
        if ($approvers->isNotEmpty()) {
            Notification::send($approvers, new \App\Notifications\NewVisitRequest($visitRequest));
        }

        return redirect()->route('requests.my')->with('success', 'Permintaan kunjungan berhasil diajukan.');
    }

    /**
     * Menyetujui sebuah request.
     */
    public function approve(VisitRequest $visitRequest)
    {
        $this->authorize('approve', $visitRequest);

        $approvedStatus = Status::where('name', 'Approved')->firstOrFail();
        
        $visitRequest->update([
            'status_id'        => $approvedStatus->id,
            'approved_by'      => Auth::id(),
            'approved_at'      => now(),
            'rejection_reason' => null // Pastikan alasan penolakan dibersihkan jika ada
        ]);

        // Kirim notifikasi ke pembuat request
        $this->sendUpdateNotification($visitRequest);

        return redirect()->back()->with('success', 'Permintaan berhasil disetujui.');
    }

    /**
     * Menolak sebuah request.
     */
    public function reject(Request $request, VisitRequest $visitRequest)
    {
        $this->authorize('approve', $visitRequest);
        
        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);
        
        $rejectedStatus = Status::where('name', 'Rejected')->firstOrFail();

        $visitRequest->update([
            'status_id'        => $rejectedStatus->id, 
            'approved_by'      => Auth::id(), // DIUBAH: Catat siapa yang menolak
            'approved_at'      => now(),      // DIUBAH: Catat kapan ditolak
            'rejection_reason' => $validated['rejection_reason']
        ]);

        // Kirim notifikasi ke pembuat request
        $this->sendUpdateNotification($visitRequest);

        return redirect()->back()->with('success', 'Permintaan telah ditolak.');
    }

    /**
     * Membatalkan sebuah request.
     */
    public function cancel(VisitRequest $visitRequest)
    {
        $this->authorize('cancel', $visitRequest);

        if ($visitRequest->status->name !== 'Pending') {
            return back()->with('error', 'Hanya permintaan yang sedang pending yang bisa dibatalkan.');
        }

        $cancelledStatus = Status::where('name', 'Cancelled')->firstOrFail();
        $visitRequest->update(['status_id' => $cancelledStatus->id]);

        return redirect()->route('requests.my')->with('success', 'Permintaan berhasil dibatalkan.');
    }
    
    /**
     * Method private untuk mengirim notifikasi status update ke requester.
     */
    private function sendUpdateNotification(VisitRequest $visitRequest)
    {
        // Refresh model untuk mendapatkan data terbarunya, termasuk relasi user
        $visitRequest->refresh();
        
        $requester = $visitRequest->user;

        if ($requester) {
            $requester->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest));
        }
    }
}
