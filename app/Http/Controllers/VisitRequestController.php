<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\VisitRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\VisitRequestsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Notification;

class VisitRequestController extends Controller
{
    // ... (method approval, monitor, hrdApproval, myRequests, create tidak berubah) ...

    public function approval()
    {
        $this->authorize('approve visit requests');
        return view('visit_requests.approval');
    }

    public function monitor()
    {
        $this->authorize('view monitor page');
        return view('visit_requests.monitor');
    }
    
    public function hrdApproval()
    {
        $this->authorize('view monitor page');
        return view('visit_requests.hrd-approval');
    }
    
    public function myRequests()
    {
        $this->authorize('create visit requests');
        return view('visit_requests.my_requests');
    }

    public function create()
    {
        $this->authorize('create visit requests');
        return view('visit_requests.create');
    }

    // Logika untuk menyimpan request baru dari form
    public function store(Request $request)
    {
        $this->authorize('create visit requests');
        
        $request->validate([
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);

        $pendingStatus = Status::where('name', 'Pending')->firstOrFail();
        
        // --- DIUBAH: Simpan hasil create ke dalam variabel ---
        $visitRequest = VisitRequest::create([
            'user_id' => Auth::id(),
            'destination' => $request->destination,
            'purpose' => $request->purpose,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'status_id' => $pendingStatus->id,
        ]);

        // --- Blok untuk mengirim notifikasi ke approver ---
        $requester = Auth::user();
        $approvers = $requester->getApprovers();

        if ($approvers->isNotEmpty()) {
            // Kode ini sekarang akan berjalan dengan benar karena $visitRequest sudah ada
            Notification::send($approvers, new \App\Notifications\NewVisitRequest($visitRequest));
        }
        // -----------------------------------------------------------------

        return redirect()->route('requests.my')->with('success', 'Permintaan kunjungan berhasil diajukan.');
    }

    // ... (method show, approve, reject, cancel, export tidak berubah) ...
    
    public function show(VisitRequest $visitRequest)
    {
        $this->authorize('view', $visitRequest);
        return view('visit_requests.show', compact('visitRequest'));
    }

   public function approve(VisitRequest $visitRequest)
{
    Log::info('Memulai proses approve untuk request ID: ' . $visitRequest->id);

    $this->authorize('approve', $visitRequest);

    $approvedStatus = Status::where('name', 'Approved')->firstOrFail();
    $visitRequest->update([
        'status_id' => $approvedStatus->id,
        'approved_by' => Auth::id(),
        'approved_at' => now(),
        'rejection_reason' => null
    ]);
    Log::info('Status request ID: ' . $visitRequest->id . ' berhasil diupdate di database.');

    $visitRequest->refresh();
    Log::info('Model untuk request ID: ' . $visitRequest->id . ' telah di-refresh.');

    $requester = $visitRequest->user;
    if ($requester) {
        Log::info('Requester ditemukan: ' . $requester->email . '. Mencoba mengirim notifikasi...');
        $requester->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest));
        Log::info('Perintah notifikasi untuk requester ' . $requester->email . ' TELAH DIJALANKAN.');
    } else {
        Log::info('!!! ERROR: Requester tidak ditemukan untuk request ID: ' . $visitRequest->id);
    }

    return redirect()->route('requests.approval')->with('success', 'Permintaan berhasil disetujui.');
}

    public function reject(Request $request, VisitRequest $visitRequest)
    {
        Log::info('Memulai proses reject untuk request ID: ' . $visitRequest->id);
        $this->authorize('approve', $visitRequest);

        $request->validate(['rejection_reason' => 'nullable|string|max:500']);
        $rejectedStatus = Status::where('name', 'Rejected')->firstOrFail();

        $visitRequest->update([
            'status_id' => $rejectedStatus->id, 
            'approved_by' => null, 
            'approved_at' => null, 
            'rejection_reason' => $request->rejection_reason 
        ]);

        Log::info('Status request ID: ' . $visitRequest->id . ' berhasil diupdate di database.');

         $visitRequest->refresh();

          Log::info('Model untuk request ID: ' . $visitRequest->id . ' telah di-refresh.');
       $requester = $visitRequest->user;
    if ($requester) {
        Log::info('Requester ditemukan: ' . $requester->email . '. Mencoba mengirim notifikasi...');
        $requester->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest));
        Log::info('Perintah notifikasi untuk requester ' . $requester->email . ' TELAH DIJALANKAN.');
    } else {
        Log::info('!!! ERROR: Requester tidak ditemukan untuk request ID: ' . $visitRequest->id);
    }

        return redirect()->route('requests.approval')->with('success', 'Permintaan telah ditolak.');
    }

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
    
    public function export(Request $request)
    {
        $this->authorize('view all visit requests');

        $filters = $request->all();
        $fileName = 'visit_requests_' . date('Y-m-d') . '.xlsx';
        return Excel::download(new VisitRequestsExport($filters), $fileName);
    }
}