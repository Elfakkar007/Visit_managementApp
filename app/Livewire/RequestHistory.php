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

class RequestHistory extends Component
{
    use WithPagination;

    public $mode;
    public $filterUser = '', $filterDepartment = '', $filterSubsidiary = '', $filterStatus = '', $filterDate = '';
    
    public $showDetailModal = false;
    public $selectedRequest;
    public $approverNote = '';

    public function updating($property)
    {
        if (in_array($property, ['filterUser', 'filterDepartment', 'filterSubsidiary', 'filterStatus', 'filterDate'])) {
            $this->resetPage();
        }
    }

    #[On('approve-request')]
    public function approve($requestId)
    {
        $visitRequest = VisitRequest::findOrFail($requestId);
        $this->authorize('approve', $visitRequest);

        $approvedStatus = Status::where('name', 'Approved')->firstOrFail();
        $visitRequest->update([
            'status_id' => $approvedStatus->id,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approver_note' => $this->approverNote ?: null
        ]);

        $visitRequest->user->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest->refresh()));
        $this->dispatch('show-toast', type: 'success', message: 'Permintaan berhasil disetujui.');
        $this->closeModal();
    }

    #[On('reject-request')]
    public function reject($requestId)
    {
        $visitRequest = VisitRequest::findOrFail($requestId);
        $this->authorize('approve', $visitRequest);
        
        $rejectedStatus = Status::where('name', 'Rejected')->firstOrFail();
        $visitRequest->update([
            'status_id' => $rejectedStatus->id,
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'approver_note' => $this->approverNote ?: 'Ditolak tanpa catatan.'
        ]);
        
        $visitRequest->user->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest->refresh()));
        $this->dispatch('show-toast', type: 'error', message: 'Permintaan telah ditolak.');
        $this->closeModal();
    }
    
    public function viewDetail($requestId)
    {
        $this->approverNote = '';
        $this->selectedRequest = VisitRequest::with(['user.profile.department', 'status', 'approver'])
            ->findOrFail($requestId);
        $this->approverNote = $this->selectedRequest->approver_note ?? '';
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showDetailModal', 'selectedRequest', 'approverNote']);
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
        }

        $this->applyUiFilters($query);

        return $query->latest('created_at');
    }

   
  
    private function applyApprovalLogic(Builder $query, User $approver)
    {
        $requestIds = app(\App\Services\WorkflowService::class)->getRequestIdsFor($approver);

        if (empty($requestIds)) {
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
        $query->when($this->filterDate, fn($q) => $q->whereDate('from_date', '=', \Carbon\Carbon::parse($this->filterDate)->format('Y-m-d')));
    }

    public function render()
    {
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
        ]);
    }
}