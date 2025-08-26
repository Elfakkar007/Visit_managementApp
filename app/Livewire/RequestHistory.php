<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VisitRequest;
use App\Models\Department;
use App\Models\Subsidiary;
use App\Models\Status;
use App\Models\User;
use App\Models\Level;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Exports\VisitRequestsExport;
use Maatwebsite\Excel\Facades\Excel;

class RequestHistory extends Component
{
    use WithPagination;

    public $mode = 'monitor';
    public $filterUser = '', $filterDepartment = '', $filterSubsidiary = '', $filterStatus = '', $filterDate = '';
    public $showDetailModal = false;
    public $selectedRequest;

    // Properti baru untuk form di modal
    public $rejection_reason = '';

    public function updating($property)
    {
        if (in_array($property, ['filterUser', 'filterDepartment', 'filterSubsidiary', 'filterStatus', 'filterDate'])) {
            $this->resetPage();
        }
    }

    public function viewDetail($requestId)
    {
        $this->selectedRequest = VisitRequest::with(['user.profile.department', 'user.profile.subsidiary', 'status', 'approver'])->find($requestId);
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedRequest = null;
        $this->rejection_reason = ''; // Reset catatan saat modal ditutup
    }

    // --- LOGIKA AKSI BARU ---
    public function approveRequest()
    {
        if (!$this->selectedRequest) return;

        Gate::authorize('approve', $this->selectedRequest);
        
        $approvedStatus = Status::where('name', 'Approved')->firstOrFail();
        $this->selectedRequest->update([
            'status_id' => $approvedStatus->id, 
            'approved_by' => Auth::id(), 
            'approved_at' => now(), 
            'rejection_reason' => $this->rejection_reason ?: null // Simpan catatan jika ada
        ]);
        
        session()->flash('success', 'Permintaan berhasil disetujui.');
        $this->closeModal();
    }

    public function rejectRequest()
    {
        if (!$this->selectedRequest) return;

        Gate::authorize('approve', $this->selectedRequest);

        $rejectedStatus = Status::where('name', 'Rejected')->firstOrFail();
        $this->selectedRequest->update([
            'status_id' => $rejectedStatus->id,
            'approved_by' => Auth::id(), // Catat siapa yang menolak
            'approved_at' => now(), // Catat kapan ditolak
            'rejection_reason' => $this->rejection_reason
        ]);

        session()->flash('success', 'Permintaan telah ditolak.');
        $this->closeModal();
    }



    public function exportExcel()
    {
        $filters = [
            'filterUser' => $this->filterUser,
            'filterDepartment' => $this->filterDepartment,
            'filterSubsidiary' => $this->filterSubsidiary,
            'filterStatus' => $this->filterStatus,
            'filterDate' => $this->filterDate,
        ];
        return Excel::download(new VisitRequestsExport($filters), 'laporan_aktivitas_request.xlsx');
    }

    private function buildQuery()
    {
        $query = VisitRequest::query()->with(['user.profile.department', 'user.profile.subsidiary', 'status', 'user.profile.level', 'approver']);
        $user = Auth::user();
        $userProfile = $user->profile;

        // --- LOGIKA APPROVAL BARU YANG SANGAT KETAT ---
        if ($this->mode === 'approval' || $this->mode === 'hrd_approval') {
            $query->where('status_id', Status::where('name', 'Pending')->firstOrFail()->id);
            
            $userLevelName = $userProfile->level->name;

            if ($userLevelName === 'Manager') {
                $query->whereHas('user.profile', function ($q) use ($userProfile) {
                    $q->where('department_id', $userProfile->department_id)
                      ->where('subsidiary_id', $userProfile->subsidiary_id)
                      ->whereIn('level_id', Level::whereIn('name', ['Staff', 'SPV'])->pluck('id'));
                });
            } elseif ($userLevelName === 'Deputi') {
                $pusatId = Subsidiary::where('name', 'Pusat')->firstOrFail()->id;
                $query->whereHas('user.profile', function ($q) use ($userProfile, $pusatId) {
                    $q->where('level_id', Level::where('name', 'Manager')->firstOrFail()->id)
                      ->where(function ($subq) use ($userProfile, $pusatId) {
                          $subq->where('subsidiary_id', $userProfile->subsidiary_id)
                               ->orWhere('subsidiary_id', $pusatId);
                      });
                });
            } else {
                $query->whereRaw('1 = 0'); // Mencegah level lain melihat data approval
            }
        }
        
        // Terapkan filter manual HANYA jika mode-nya adalah monitor atau admin
        if ($this->mode === 'monitor' || $this->mode === 'admin') {
            $query->when($this->filterUser, fn($q) => $q->where('user_id', $this->filterUser));
            $query->when($this->filterDepartment, fn($q) => $q->whereHas('user.profile', fn($subq) => $subq->where('department_id', $this->filterDepartment)));
            $query->when($this->filterSubsidiary, fn($q) => $q->whereHas('user.profile', fn($subq) => $subq->where('subsidiary_id', $this->filterSubsidiary)));
            $query->when($this->filterStatus, fn($q) => $q->where('status_id', $this->filterStatus));
            $query->when($this->filterDate, fn($q) => $q->whereDate('from_date', '=', \Carbon\Carbon::parse($this->filterDate)->format('Y-m-d')));
        }

        return $query->latest();
    }

    public function render()
    {
        return view('livewire.request-history', [
            'requests' => $this->buildQuery()->paginate(15),
            'departments' => Department::orderBy('name')->get(),
            'subsidiaries' => Subsidiary::orderBy('name')->get(),
            'statuses' => Status::all(),
            'users' => User::orderBy('name')->get(),
        ]);
    }
}
