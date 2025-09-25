<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogViewer extends Component
{
    use WithPagination;

    public $search = '';
    public $filterUser = '';

    public function render()
    {
        $logs = Activity::with('causer', 'subject')
            ->where(function ($query) {
                $query->where('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('causer', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filterUser, function ($query) {
                $query->where('causer_id', $this->filterUser);
            })
            ->latest()
            ->paginate(15);
            
        return view('livewire.admin.activity-log-viewer', [
            'logs' => $logs,
            'users' => User::orderBy('name')->get(['id', 'name'])
        ]);
    }
}