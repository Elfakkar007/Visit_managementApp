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

    // Properti untuk setiap filter
    public $filterUser = '';
    public $filterDepartment = '';
    public $filterSubsidiary = '';
    public $filterStatus = '';
    public $filterDate = '';

    // Aksi ini akan dipanggil setiap kali salah satu filter diubah
    public function updating($property, $value)
    {
        if (in_array($property, ['filterUser', 'filterDepartment', 'filterSubsidiary', 'filterStatus', 'filterDate'])) {
            $this->resetPage(); // Reset paginasi saat filter berubah
        }
    }

    public function render()
    {
        $query = VisitRequest::with('user.profile.department', 'user.profile.subsidiary', 'status');

        $user = Auth::user();
        $userProfile = $user->profile;

        $query = VisitRequest::with('user.profile.department', 'user.profile.subsidiary', 'status');
        
        // --- LOGIKA FILTER OTOMATIS BERDASARKAN PERAN ---
        if ($userProfile->department?->name !== 'HRD') {
            $query->whereHas('status', function ($q) {
                $q->where('name', 'Pending');
            });
        }

        $userLevel = $userProfile->level->name;
        if ($userLevel === 'Manager' && $userProfile->department->name !== 'HRD') {
            $query->whereHas('user.profile', function ($q) use ($userProfile) {
                $q->where('department_id', $userProfile->department_id)
                  ->where('subsidiary_id', $userProfile->subsidiary_id)
                  ->whereIn('level_id', \App\Models\Level::whereIn('name', ['Staff', 'SPV'])->pluck('id'));
            });
        } 
        elseif ($userLevel === 'Deputi') {
            $pusatId = \App\Models\Subsidiary::where('name', 'Pusat')->firstOrFail()->id;
            $query->whereHas('user.profile', function ($q) use ($userProfile, $pusatId) {
                $q->where('level_id', \App\Models\Level::where('name', 'Manager')->firstOrFail()->id)
                  ->where(function ($subq) use ($userProfile, $pusatId) {
                      $subq->where('subsidiary_id', $userProfile->subsidiary_id)->orWhere('subsidiary_id', $pusatId);
                  });
            });
        }

        // Terapkan filter jika ada input
        if (!empty($this->filterUser)) {
            $query->where('user_id', $this->filterUser);
        }

        if (!empty($this->filterDepartment)) {
            $query->whereHas('user.profile', function ($q) {
                $q->where('department_id', $this->filterDepartment);
            });
        }
        
        if (!empty($this->filterSubsidiary)) {
            $query->whereHas('user.profile', function ($q) {
                $q->where('subsidiary_id', $this->filterSubsidiary);
            });
        }

        if (!empty($this->filterStatus)) {
            $query->where('status_id', $this->filterStatus);
        }

        if (!empty($this->filterDate)) {
            $query->whereDate('from_date', $this->filterDate);
        }

        $requests = $query->latest()->paginate(15);

        // Kirim data master ke view untuk dropdown filter
        return view('livewire.request-history', [
            'requests' => $requests,
            'departments' => Department::orderBy('name')->get(),
            'subsidiaries' => Subsidiary::orderBy('name')->get(),
            'statuses' => Status::all(),
            'users' => User::orderBy('name')->get(),
        ]);
    }
}