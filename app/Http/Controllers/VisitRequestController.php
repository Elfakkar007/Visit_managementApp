<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\User;
use App\Models\VisitRequest;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate;
use App\Exports\VisitRequestsExport;
use Maatwebsite\Excel\Facades\Excel;

class VisitRequestController extends Controller
{
       public function approval()
        {
            return view('visit_requests.approval');
        }
    // {
    //     $user = auth()->user();
    //     $query = VisitRequest::with('user.profile.level', 'user.profile.department', 'status')
    //     ->whereHas('status', function ($q) { $q->where('name', 'Pending'); })
    //     ->latest();

        

    //     $userLevel = $user->profile->level->name;

    //     // --- LOGIKA FILTER DENGAN URUTAN YANG BENAR ---

    //     // Terapkan filter berdasarkan level
    //     if ($userLevel === 'Manager' && $user->profile->department->name !== 'HRD') {
    //         $query->whereHas('user.profile', function ($q) use ($user) {
    //             $q->where('department_id', $user->profile->department_id)
    //             ->where('subsidiary_id', $user->profile->subsidiary_id)
    //             ->whereIn('level_id', \App\Models\Level::whereIn('name', ['Staff', 'SPV'])->pluck('id'));
    //         });
    //     } 
    //     elseif ($userLevel === 'Deputi') {
    //         $pusatId = \App\Models\Subsidiary::where('name', 'Pusat')->firstOrFail()->id;
            
    //         $query->whereHas('user.profile', function ($q) use ($user, $pusatId) {
    //             $q->where('level_id', \App\Models\Level::where('name', 'Manager')->firstOrFail()->id)
    //             ->where(function ($subq) use ($user, $pusatId) {
    //                 $subq->where('subsidiary_id', $user->profile->subsidiary_id)
    //                     ->orWhere('subsidiary_id', $pusatId);
    //             });
    //         });
    //     }
        
    //     $visitRequests = $query->paginate(15);

    //     // Sekarang, kita kirimkan data ke komponen Livewire
    //      return view('visit_requests.approval', compact('visitRequests'));
    // }

    //     public function monitor()
    // {
    //     // Cek otorisasi khusus untuk HRD jika perlu
    //     if (auth()->user()->profile->department?->name !== 'HRD') {
    //         abort(403);
    //     }
    //     // Halaman ini hanya akan memanggil komponen Livewire
    //     return view('visit_requests.monitor');
    // }
     public function monitor()
    {
        if (auth()->user()->profile->department?->name !== 'HRD') {
            abort(403);
        }
        return view('visit_requests.monitor');
    }
        

    // public function hrdApproval()
    // {
    //     Gate::authorize('viewAny', VisitRequest::class);
    //     $user = auth()->user();

    //     // Query ini HANYA mengambil request dari bawahan di departemen HRD
    //     $query = VisitRequest::with('user.profile.level', 'user.profile.department', 'status')
    //         ->where('status_id', \App\Models\Status::where('name', 'Pending')->firstOrFail()->id)
    //         ->whereHas('user.profile', function ($q) use ($user) {
    //             $q->where('department_id', $user->profile->department_id)
    //               ->whereIn('level_id', [
    //                   \App\Models\Level::where('name', 'Staff')->firstOrFail()->id,
    //                   \App\Models\Level::where('name', 'SPV')->firstOrFail()->id,
    //               ]);
    //         })
    //         ->latest();

    //     $visitRequests = $query->paginate(10);
    //     // Kita gunakan view yang sama dengan index, karena isinya tabel juga
    //     return view('visit_requests.approval', compact('visitRequests'));

    // }
    public function hrdApproval()
    {
        Gate::authorize('viewAny', VisitRequest::class);
        return view('visit_requests.hrd-approval');
    }
 
  

    
    public function myRequests()
    {
         return view('visit_requests.my_requests');
    }

    public function create()
    {
        return view('visit_requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'destination' => 'required|string|max:255',
            'purpose' => 'required|string',
            'from_date' => 'required|date|after_or_equal:today',
            'to_date' => 'required|date|after_or_equal:from_date',
        ]);
        $pendingStatus = Status::where('name', 'Pending')->firstOrFail();
        VisitRequest::create(['user_id' => Auth::id(), 'destination' => $request->destination, 'purpose' => $request->purpose, 'from_date' => $request->from_date, 'to_date' => $request->to_date, 'status_id' => $pendingStatus->id,]);
        return redirect()->route('requests.my')->with('success', 'Permintaan kunjungan berhasil diajukan.');
    }

    public function show(VisitRequest $visitRequest)
    {
        Gate::authorize('view', $visitRequest);
        return view('visit_requests.show', compact('visitRequest'));
    }

    public function approve(VisitRequest $visitRequest)
    {
        Gate::authorize('approve', $visitRequest);
        $approvedStatus = Status::where('name', 'Approved')->firstOrFail();
        $visitRequest->update(['status_id' => $approvedStatus->id, 'approved_by' => Auth::id(), 'approved_at' => now(), 'rejection_reason' => null]);
        return redirect()->route('requests.approval')->with('success', 'Permintaan berhasil disetujui.');
    }



        public function reject(Request $request, VisitRequest $visitRequest)
        {
            Gate::authorize('approve', $visitRequest);

            $request->validate([
                'rejection_reason' => 'nullable|string|max:500'
            ]);

            $rejectedStatus = Status::where('name', 'Rejected')->firstOrFail();

            $visitRequest->update([
                'status_id' => $rejectedStatus->id, 
                'approved_by' => null, 
                'approved_at' => null, 
                'rejection_reason' => $request->rejection_reason 
            ]);

            return redirect()->route('requests.approval')->with('success', 'Permintaan telah ditolak.');
        }


    public function cancel(VisitRequest $visitRequest)
    {
        // Pastikan hanya pemilik request yang bisa membatalkan
        if (auth()->id() !== $visitRequest->user_id) {
            abort(403);
        }
        // Pastikan hanya request yang 'Pending' yang bisa dibatalkan
        if ($visitRequest->status->name !== 'Pending') {
            return back()->with('error', 'Hanya permintaan yang sedang pending yang bisa dibatalkan.');
        }

        $cancelledStatus = Status::where('name', 'Cancelled')->firstOrFail();
        $visitRequest->update(['status_id' => $cancelledStatus->id]);

        return redirect()->route('requests.my')->with('success', 'Permintaan berhasil dibatalkan.');
    }

    public function export(Request $request)
    {
        // Ambil semua filter dari URL
        $filters = $request->all();

        // Siapkan nama file
        $fileName = 'visit_requests_' . date('Y-m-d') . '.xlsx';

        // Panggil kelas Export dengan membawa filter dan unduh file
        return Excel::download(new VisitRequestsExport($filters), $fileName);
    }
}