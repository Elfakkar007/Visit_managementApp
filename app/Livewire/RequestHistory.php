<?php

namespace App\Livewire;

use App\Models\Status;
use App\Models\User;
use App\Models\VisitRequest;
use App\Models\Department;
use App\Models\Subsidiary;
use App\Models\ApprovalWorkflow;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\VisitRequestsExport;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Attributes\On;

class RequestHistory extends Component
{
    use WithPagination;

    public $mode;
    public $filterUser = '', $filterDepartment = '', $filterSubsidiary = '', $filterStatus = '', $filterDate = '';
    
    public $showDetailModal = false;
    public $selectedRequest;
    
    // Mengganti nama properti agar lebih umum (bisa untuk approve/reject)
    public $approverNote = '';

    public function updating($property)
    {
        if (in_array($property, ['filterUser', 'filterDepartment', 'filterSubsidiary', 'filterStatus', 'filterDate'])) {
            $this->resetPage();
        }
    }

    // Aksi approve, bisa dipanggil dari tabel atau modal
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
            'approver_note' => $this->approverNote ?: null // Simpan catatan jika ada
        ]);

        $visitRequest->user->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest->refresh()));
        $this->dispatch('show-toast', type: 'success', message: 'Permintaan berhasil disetujui.');
        $this->closeModal();
    }

    // Aksi reject, bisa dipanggil dari tabel atau modal
    #[On('reject-request')]
    public function reject($requestId)
    {
        $visitRequest = VisitRequest::findOrFail($requestId);
        $this->authorize('approve', $visitRequest);
        
        // VALIDASI DIHAPUSKAN KARENA CATATAN SEKARANG OPSIONAL

        $rejectedStatus = Status::where('name', 'Rejected')->firstOrFail();
        $visitRequest->update([
            'status_id' => $rejectedStatus->id,
            'approved_by' => Auth::id(), // Tetap catat siapa yang beraksi
            'approved_at' => now(), // Tetap catat waktu aksi
            'approver_note' => $this->approverNote ?: 'Ditolak tanpa catatan.' // Simpan catatan jika ada, jika tidak beri default
        ]);
        
        $visitRequest->user->notify(new \App\Notifications\VisitRequestStatusUpdated($visitRequest->refresh()));
        $this->dispatch('show-toast', type: 'error', message: 'Permintaan telah ditolak.');
        $this->closeModal();
    }
    
    // Aksi untuk membuka modal detail
    public function viewDetail($requestId)
    {
        $this->approverNote = ''; // Reset catatan setiap kali modal dibuka
        $this->selectedRequest = VisitRequest::with(['user.profile.department', 'status', 'approver'])
            ->findOrFail($requestId);
        // Isi textarea dengan catatan yang sudah ada jika ada
        $this->approverNote = $this->selectedRequest->approver_note ?? '';
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showDetailModal', 'selectedRequest', 'approverNote']);
    }

    public function exportExcel()
    {
        $this->authorize('view all visit requests');
        $filters = $this->only(['filterUser', 'filterDepartment', 'filterSubsidiary', 'filterStatus', 'filterDate']);
        return Excel::download(new VisitRequestsExport($filters), 'laporan_request_kunjungan.xlsx');
    }

    private function buildQuery()
    {
        $query = VisitRequest::query()->with(['user.profile.department', 'user.profile.subsidiary', 'status', 'user.profile.level', 'approver']);
        $user = Auth::user();

        switch ($this->mode) {
            case 'my_requests':
                $query->where('user_id', $user->id);
                break;
            case 'approval':
                 // 1. Ambil data approver yang sedang login
                $approver = $user;
                $approverLevelId = $approver->profile->level_id;
                $approverDepartmentId = $approver->profile->department_id;
                $approverSubsidiaryId = $approver->profile->subsidiary_id;

                // 2. Cari semua aturan workflow yang berlaku untuk level approver ini
                $applicableRules = ApprovalWorkflow::where('approver_level_id', $approverLevelId)->get();
                
                // 3. Bangun query dasar untuk request yang relevan
                $query->where(function ($q) use ($applicableRules, $approverDepartmentId, $approverSubsidiaryId) {
                    if ($applicableRules->isEmpty()) {
                        $q->whereRaw('1 = 0'); // Jika tidak ada aturan, jangan tampilkan apa-apa
                        return;
                    }
                    foreach ($applicableRules as $rule) {
                        $q->orWhere(function ($subQuery) use ($rule, $approverDepartmentId, $approverSubsidiaryId) {
                            $subQuery->whereHas('user.profile', fn($p) => $p->where('level_id', $rule->requester_level_id));
                            if ($rule->scope === 'department') {
                                $subQuery->whereHas('user.profile', fn($p) => $p->where('department_id', $approverDepartmentId));
                            } elseif ($rule->scope === 'subsidiary') {
                                $subQuery->whereHas('user.profile', fn($p) => $p->where('subsidiary_id', $approverSubsidiaryId));
                            }
                        });
                    }
                });

                // 4. KONDISI UTAMA: Cek apakah user punya izin untuk melihat riwayat
                if (! $approver->can('view approval history')) {
                    // Jika TIDAK punya izin, filter HANYA untuk status 'Pending'
                    $query->whereHas('status', fn($q) => $q->where('name', 'Pending'));
                }
                // Jika user punya izin, maka filter status 'Pending' ini dilewati,
                // sehingga semua request (approved, rejected, dll.) akan tampil.
                
                break;

                
            
        }

        $query->when($this->filterUser, fn($q) => $q->where('user_id', $this->filterUser));
        $query->when($this->filterDepartment, fn($q) => $q->whereHas('user.profile', fn($subq) => $subq->where('department_id', $this->filterDepartment)));
        $query->when($this->filterSubsidiary, fn($q) => $q->whereHas('user.profile', fn($subq) => $subq->where('subsidiary_id', $this->filterSubsidiary)));
        $query->when($this->filterStatus, fn($q) => $q->where('status_id', $this->filterStatus));
        $query->when($this->filterDate, fn($q) => $q->whereDate('from_date', '=', \Carbon\Carbon::parse($this->filterDate)->format('Y-m-d')));

        return $query->latest('created_at');
    }

    public function render()
    {
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
            'requests' => $this->buildQuery()->paginate(10),
            'departments' => Department::orderBy('name')->get(),
            'subsidiaries' => Subsidiary::orderBy('name')->get(),
            'all_statuses' => Status::all(),
            'users' => User::orderBy('name')->get(),
            'statusColors' => $statuses,
        ]);
    }
}