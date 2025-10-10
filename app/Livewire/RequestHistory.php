<?php

namespace App\Livewire;

use App\Models\Status;
use App\Models\User;
use App\Models\VisitRequest;
use App\Models\Department;
use App\Models\Subsidiary;
use App\Models\ApprovalWorkflowCondition;
use App\Models\ApprovalWorkflowStep;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\On;
use App\Exports\VisitRequestsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;

class RequestHistory extends Component
{
    use WithPagination;

    public $mode;
    public $filterUser = '', $filterDepartment = '', $filterSubsidiary = '', $filterStatus = '', $filterDate = '', $filterMonth = '',  $filterYear = '';
    
    public $showDetailModal = false;
    public $selectedRequest;
    public $approverNote = '';

    public function updating($property)
    {
        if (in_array($property, ['filterUser', 'filterDepartment', 'filterSubsidiary', 'filterStatus', 'filterDate', 'filterMonth', 'filterYear'])) {
            $this->resetPage();
        }
    }

   #[On('approve-request')]
    public function approve($requestId) 
    {
        DB::transaction(function () use ($requestId) {
            
            $visitRequest = VisitRequest::with('status')->lockForUpdate()->findOrFail($requestId); 

            if ($visitRequest->status->name !== 'Pending') {
                 $this->dispatch('show-toast', [
                    'type' => 'info',
                    'message' => 'Permintaan ini sudah diproses oleh approver lain.'
                ]);
                $this->closeModal();
                return; 
            }

            $this->authorize('approve', $visitRequest);

            $workflow = app(\App\Services\WorkflowService::class)->findBestWorkflowFor($visitRequest->user);
            
            $hasNextStep = false;
            if ($workflow) {
                $steps = $workflow->steps();
                $hasNextStep = $steps->where('step', $visitRequest->current_step + 1)->exists();
            }
        
            if ($hasNextStep) {
                $visitRequest->increment('current_step');
                $nextApprovers = app(\App\Services\WorkflowService::class)->findApproversFor($visitRequest->fresh());
                if ($nextApprovers->isNotEmpty()) {

                }
                $this->dispatch('show-toast', [
                                'type' => 'success',
                                'message' => 'Disetujui. Diteruskan ke approver selanjutnya.'
                            ]);
            } else {
                $approvedStatusId = Status::getIdByName('Approved');
                $visitRequest->update([
                    'status_id' => $approvedStatusId,
                    'approved_by' => Auth::id(),
                    'processed_at' => now(),
                    'approver_note' => $this->approverNote ?: null
                ]);

                $visitRequest->user->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest));
            $this->dispatch('show-toast', [
                    'type' => 'success',
                    'message' => 'Permintaan berhasil disetujui sepenuhnya.'
                ]);
            }
            
            $this->closeModal();

        }, 5);
    }
    
    #[On('reject-request')]
    public function reject($requestId)
    {
  
        DB::transaction(function () use ($requestId) {
            $visitRequest = VisitRequest::with('status')->lockForUpdate()->findOrFail($requestId);
            
            if ($visitRequest->status->name !== 'Pending') {
                $this->dispatch('show-toast', [
                   'type' => 'info',
                   'message' => 'Permintaan ini sudah diproses oleh approver lain.'
               ]);
               $this->closeModal();
               return;
            }

            $this->authorize('approve', $visitRequest);
            
            $rejectedStatusId = Status::getIdByName('Rejected');
            $visitRequest->update([
                'status_id' => $rejectedStatusId,
                'approved_by' => Auth::id(),
                'processed_at' => now(),
                'approver_note' => $this->approverNote ?: __('request.rejection_note_default')
            ]);
            
            $visitRequest->user->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest));
            $this->dispatch('show-toast', [
                'type' => 'error',
                'message' => 'Permintaan telah ditolak.'
            ]);
            $this->closeModal();
        }, 5);
    }

    public function closeModal()
    {
        $this->reset(['showDetailModal', 'selectedRequest', 'approverNote']);
    }

     public function viewDetail($requestId)
    {
        $this->approverNote = '';
        $this->selectedRequest = VisitRequest::with(['user.profile.department', 'status', 'approver'])
            ->findOrFail($requestId);
        $this->approverNote = $this->selectedRequest->approver_note ?? '';
        $this->showDetailModal = true;
    }

    private function buildQuery()
    {
        $query = VisitRequest::query()->with(['user.profile.department', 'user.profile.subsidiary', 'status', 'approver']);
        $user = Auth::user();

        switch ($this->mode) {
            case 'my_requests':
                $query->where('user_id', Auth::id()); // Diperbaiki agar hanya menampilkan request milik user
                break;

            case 'approval':
                $this->applyApprovalLogic($query, $user);
                break;
            
            case 'monitor':
                 $this->authorize('view monitor page');
                break;

            case 'admin_approval':
            $this->authorize('approve visit requests');
            break;

        }

        $this->applyUiFilters($query);

        return $query->latest('created_at');
    }

   
  
    private function applyApprovalLogic(Builder $query, User $approver)
    {
        $requestIds = app(\App\Services\WorkflowService::class)->getRequestIdsFor($approver);

        if (empty($requestIds)) {
            // Use a false condition to ensure no results are returned when there are no request IDs
            $query->whereRaw('1 = 0'); // Trik agar tidak menampilkan apa-apa
        } else {
            $query->whereIn('id', $requestIds);
        }
    }

    private function applyUiFilters(Builder $query)
    {
        $query->when($this->filterUser, fn($q) => $q->where('user_id', $this->filterUser));
        $query->when($this->filterDepartment, fn($q) => $q->whereHas('user.profile', fn($subq) => $subq->where('department_id', $this->filterDepartment)));
        $query->when($this->filterSubsidiary, fn($q) => $q->whereHas('user.profile', fn($subq) => $subq->where('subsidiary_id', $this->filterSubsidiary)));
        $query->when($this->filterStatus, fn($q) => $q->where('status_id', $this->filterStatus));
        $query->when($this->filterDate, fn($q) => $q->whereDate('from_date', $this->filterDate));
        $query->when($this->filterMonth, fn($q) => $q->whereMonth('from_date', $this->filterMonth));
        $query->when($this->filterYear, fn($q) => $q->whereYear('from_date', $this->filterYear));
    }

    public function exportExcel()
    {
        // Hanya izinkan export jika dalam mode monitor/admin
        if (!in_array($this->mode, ['monitor', 'admin'])) {
            return;
        }

        // Ambil semua filter yang sedang aktif
        $filters = [
            'filterUser' => $this->filterUser,
            'filterDepartment' => $this->filterDepartment,
            'filterSubsidiary' => $this->filterSubsidiary,
            'filterStatus' => $this->filterStatus,
            'filterDate' => $this->filterDate,
            'filterMonth' => $this->filterMonth,
            'filterYear' => $this->filterYear,
        ];
        
        return Excel::download(new VisitRequestsExport($filters), 'riwayat_perjalanan_dinas.xlsx');
    }

    public function render()
    {
        
        $years = VisitRequest::selectRaw('EXTRACT(YEAR FROM from_date) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $months = collect(range(1, 12))->mapWithKeys(function ($month) {
            return [$month => \Carbon\Carbon::create()->month($month)->isoFormat('MMMM')];
        });
        
        // Pastikan variabel $requests di-inisialisasi sebelum dikirim ke view
        $requests = $this->buildQuery()->paginate(10);

        $statuses = Status::all()->mapWithKeys(function ($status) {
            $color = match (strtolower($status->name)) {
                'approved' => 'bg-green-100 text-green-800',
                'rejected' => 'bg-red-100 text-red-800',
                'cancelled' => 'bg-orange-100 text-orange-800',
                'pending' => 'bg-yellow-100 text-yellow-800',
                default => 'bg-gray-100 text-gray-800',
            };
            return [$status->id => $color];
        });

        return view('livewire.request-history', [
            'requests' => $requests,
            'departments' => Department::orderBy('name')->get(),
            'subsidiaries' => Subsidiary::orderBy('name')->get(),
            'all_statuses' => Status::all(),
            'users' => User::orderBy('name')->get(),
            'statusColors' => $statuses,
            'years' => $years,     
            'months' => $months,
        ]);
    }
}