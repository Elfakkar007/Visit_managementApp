<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\User;
use App\Models\VisitRequest;
use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Gate;

class VisitRequestController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', VisitRequest::class);
        $user = auth()->user();

        $query = VisitRequest::with('user.profile.level', 'user.profile.department', 'status')
            ->whereHas('status', function ($q) {
                $q->where('name', 'Pending');
            })
            ->latest();

        $userLevel = $user->profile->level->name;
        $userDeptId = $user->profile->department_id;

         if ($user->profile->department->name === 'HRD') {
        $query->whereHas('user.profile', function ($q) use ($userDeptId) {
            $q->where('department_id', '!=', $userDeptId);
            });
        }

        elseif ($userLevel === 'Manager') {
            $query->whereHas('user.profile', function ($q) use ($user) {
                $q->where('department_id', $user->profile->department_id)
                  ->where('subsidiary_id', $user->profile->subsidiary_id)
                  ->whereIn('level_id', \App\Models\Level::whereIn('name', ['Staff', 'SPV'])->pluck('id'));
            });
        } 
        elseif ($userLevel === 'Deputi') {
            $pusatId = \App\Models\Subsidiary::where('name', 'Pusat')->firstOrFail()->id;
            $managerLevelId = \App\Models\Level::where('name', 'Manager')->firstOrFail()->id;
            
            $query->whereHas('user.profile', function ($q) use ($user, $pusatId, $managerLevelId) {
                $q->where('level_id', $managerLevelId)
                  ->where(function ($subq) use ($user, $pusatId) {
                      $subq->where('subsidiary_id', $user->profile->subsidiary_id)
                           ->orWhere('subsidiary_id', $pusatId);
                  });
            });
        }
        
        $visitRequests = $query->paginate(10);
        return view('visit_requests.index', compact('visitRequests'));
    }
    

    public function hrdApproval()
    {
        Gate::authorize('viewAny', VisitRequest::class);
        $user = auth()->user();

        // Query ini HANYA mengambil request dari bawahan di departemen HRD
        $query = VisitRequest::with('user.profile.level', 'user.profile.department', 'status')
            ->where('status_id', \App\Models\Status::where('name', 'Pending')->firstOrFail()->id)
            ->whereHas('user.profile', function ($q) use ($user) {
                $q->where('department_id', $user->profile->department_id)
                  ->whereIn('level_id', [
                      \App\Models\Level::where('name', 'Staff')->firstOrFail()->id,
                      \App\Models\Level::where('name', 'SPV')->firstOrFail()->id,
                  ]);
            })
            ->latest();

        $visitRequests = $query->paginate(10);
        // Kita gunakan view yang sama dengan index, karena isinya tabel juga
        return view('visit_requests.index', compact('visitRequests'));

    }
  

    
    public function myRequests()
    {
        $visitRequests = VisitRequest::with('status', 'approver')->where('user_id', Auth::id())->latest()->paginate(10);
        return view('visit_requests.my_requests', compact('visitRequests'));
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
        return redirect()->route('requests.index')->with('success', 'Permintaan berhasil disetujui.');
    }

    public function reject(Request $request, VisitRequest $visitRequest)
    {
        Gate::authorize('approve', $visitRequest);
        $request->validate(['rejection_reason' => 'required|string|min:10']);
        $rejectedStatus = Status::where('name', 'Rejected')->firstOrFail();
        $visitRequest->update(['status_id' => $rejectedStatus->id, 'approved_by' => null, 'approved_at' => null, 'rejection_reason' => $request->rejection_reason]);
        return redirect()->route('requests.index')->with('success', 'Permintaan telah ditolak.');
    }

    public function destroy(VisitRequest $visitRequest)
    {
        if (auth()->id() !== $visitRequest->user_id) {
            abort(403);
        }
        if ($visitRequest->status->name !== 'Pending') {
            return back()->with('error', 'Hanya permintaan yang pending yang bisa dibatalkan.');
        }

        $visitRequest->delete();
        return redirect()->route('requests.my')->with('success', 'Permintaan berhasil dibatalkan.');
    }
}