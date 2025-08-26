<?php

namespace App\Livewire;

use App\Models\VisitRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyRequests extends Component
{
    use WithPagination;

    public $showDetailModal = false;
    public $selectedRequest;

    public function viewDetail($requestId)
    {
        // Eager load semua relasi yang dibutuhkan untuk modal detail
        $this->selectedRequest = VisitRequest::with([
            'user.profile.department',
            'user.profile.subsidiary',
            'status',
            'approver'
        ])
        ->where('id', $requestId)
        ->where('user_id', Auth::id())
        ->firstOrFail();
            
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->showDetailModal = false;
        $this->selectedRequest = null;
    }

    public function render()
    {
        $requests = VisitRequest::with('status', 'approver')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('livewire.my-requests', [
            'requests' => $requests
        ]);
    }
}
