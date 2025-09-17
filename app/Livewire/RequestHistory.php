<?php

namespace App\Livewire;

use App\Models\Status;
use App\Models\User;
use App\Models\VisitRequest;
use App\Models\Department;
use App\Models\Subsidiary;
use App\Models\ApprovalWorkflow;
use App\Models\Level;
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
                $query->where('user_id', '>', 0); // Placeholder to start query
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
        $approverProfile = $approver->profile;
        if (!$approverProfile?->level_id) {
            return $query->whereRaw('1 = 0');
        }

        $applicableRules = ApprovalWorkflow::where('approver_level_id', $approverProfile->level_id)->get();

        if ($applicableRules->isEmpty()) {
            return $query->whereRaw('1 = 0');
        }

        $query->where(function (Builder $subQuery) use ($applicableRules, $approverProfile) {
            foreach ($applicableRules as $rule) {
                $subQuery->orWhere(function (Builder $clause) use ($rule, $approverProfile) {
                    
                    $clause->whereHas('user.profile', function (Builder $profileQuery) use ($rule) {
                        // Kondisi 1: Level requester harus cocok
                        $profileQuery->where('level_id', $rule->requester_level_id);

                        // KONDISI BARU 2: Jika aturan punya kondisi subsidiary, terapkan
                        if ($rule->requester_subsidiary_id) {
                            $profileQuery->where('subsidiary_id', $rule->requester_subsidiary_id);
                        }
                    });

                    // Terapkan SCOPE seperti biasa
                    switch ($rule->scope) {
                        case 'department':
                            $clause->whereHas('user.profile', function (Builder $p) use ($approverProfile) {
                                $p->where('department_id', $approverProfile->department_id)
                                ->where('subsidiary_id', $approverProfile->subsidiary_id);
                            });
                            break;
                        case 'subsidiary':
                            $clause->whereHas('user.profile', fn(Builder $p) => $p->where('subsidiary_id', $approverProfile->subsidiary_id));
                            break;
                        case 'cross_subsidiary':
                            // Tidak ada filter tambahan
                            break;
                    }
                });
            }
        });

        if (! $approver->can('view approval history')) {
            $query->whereHas('status', fn($q) => $q->where('name', 'Pending'));
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

