<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\VisitRequest;
use App\Models\Department;
use App\Models\Subsidiary;
use App\Models\Status;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class RequestHistory extends Component
{
    use WithPagination;

    public $mode = 'monitor';

    // Properti filter, diinisialisasi ke string kosong
    public $filterUser = '';
    public $filterDepartment = '';
    public $filterSubsidiary = '';
    public $filterStatus = '';
    public $filterDate = '';

    // Method ini akan mereset halaman ke 1 jika ada filter yang diubah
    public function updating($property)
    {
        if (in_array($property, ['filterUser', 'filterDepartment', 'filterSubsidiary', 'filterStatus', 'filterDate'])) {
            $this->resetPage();
        }
    }

    public function render()
    {
        // Mulai query dasar dengan eager loading
        $query = VisitRequest::with(['user.profile.department', 'user.profile.subsidiary', 'status']);

        // Terapkan filter OTOMATIS hanya jika dalam mode 'approval'
        if ($this->mode === 'approval') {
            $userProfile = Auth::user()->profile;
            $query->where('status_id', Status::where('name', 'Pending')->firstOrFail()->id);

            $userLevel = $userProfile->level->name;

            if ($userLevel === 'Manager' && $userProfile->department->name !== 'HRD') {
                $query->whereHas('user.profile', function ($q) use ($userProfile) {
                    $q->where('department_id', $userProfile->department_id)
                      ->where('subsidiary_id', $userProfile->subsidiary_id)
                      ->whereIn('level_id', \App\Models\Level::whereIn('name', ['Staff', 'SPV'])->pluck('id'));
                });
            } elseif ($userLevel === 'Deputi') {
                $pusatId = \App\Models\Subsidiary::where('name', 'Pusat')->firstOrFail()->id;
                $query->whereHas('user.profile', function ($q) use ($userProfile, $pusatId) {
                    $q->where('level_id', \App\Models\Level::where('name', 'Manager')->firstOrFail()->id)
                      ->where(function ($subq) use ($userProfile, $pusatId) {
                          $subq->where('subsidiary_id', $userProfile->subsidiary_id)->orWhere('subsidiary_id', $pusatId);
                      });
                });
            }
        }

        // Terapkan filter MANUAL dari input dengan metode when() yang lebih andal
        $query->when($this->filterUser, function ($q) {
            return $q->where('user_id', $this->filterUser);
        });

        $query->when($this->filterDepartment, function ($q) {
            return $q->whereHas('user.profile', function ($subq) {
                $subq->where('department_id', $this->filterDepartment);
            });
        });

        $query->when($this->filterSubsidiary, function ($q) {
            return $q->whereHas('user.profile', function ($subq) {
                $subq->where('subsidiary_id', $this->filterSubsidiary);
            });
        });
        
        $query->when($this->filterStatus, function ($q) {
            return $q->where('status_id', $this->filterStatus);
        });
        
       // app/Livewire/RequestHistory.php

        $query->when($this->filterDate, function ($q) {
            $formattedDate = \Carbon\Carbon::parse($this->filterDate)->format('Y-m-d');
            return $q->whereDate('from_date', '=', $formattedDate);
        });

        // Eksekusi query dan siapkan data untuk view
        $requests = $query->latest()->paginate(15);

        return view('livewire.request-history', [
            'requests' => $requests,
            'departments' => Department::orderBy('name')->get(),
            'subsidiaries' => Subsidiary::orderBy('name')->get(),
            'statuses' => Status::all(),
            'users' => User::orderBy('name')->get(),
        ]);
    }
}