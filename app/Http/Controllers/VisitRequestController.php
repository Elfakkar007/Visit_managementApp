<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\VisitRequest;
use App\Models\Destination;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Jobs\SendVisitRequestNotification;
use Barryvdh\DomPDF\Facade\Pdf;

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
        
        $finalDestination = $validated['destination_option'] === 'other' 
            ? $validated['destination_custom'] 
            : $validated['destination_option'];

        $visitRequest = VisitRequest::create([
            'user_id'     => Auth::id(),
            'status_id'   => $pendingStatus->id,
            'destination' => $finalDestination,
            'purpose'     => $validated['purpose'],
            'from_date'   => $validated['from_date'],
            'to_date'     => $validated['to_date'],
        ]);

        $approvers = app(\App\Services\WorkflowService::class)->findApproversFor($visitRequest);
        if ($approvers->isNotEmpty()) {
            SendVisitRequestNotification::dispatch($approvers, new \App\Notifications\NewVisitRequest($visitRequest));
        }

        return redirect()->route('requests.my')->with('show-toast', [
            'type' => 'success',
            'message' => 'Permintaan kunjungan berhasil diajukan.'
        ]);
    }

    public function printSppd(VisitRequest $request)
    {
        $this->authorize('view', $request);

        if ($request->status->name !== 'Approved') {
            return back()->with('error', 'Hanya request yang sudah disetujui yang dapat dicetak.');
        }

        // Eager load semua relasi yang dibutuhkan oleh PDF di sini
        $request->load([
            'user.profile.level', 
            'user.profile.department', 
            'approvalLogs.approver.profile.level'
        ]);

        // Menggunakan library PDF yang sudah diinstal sebelumnya
        $pdf = Pdf::loadView('visit_requests.sppd_pdf', ['request' => $request]);
        $fileName = 'SPPD-' . $request->user->name . '-' . $request->id . '.pdf';

        return $pdf->stream($fileName);
    }
}