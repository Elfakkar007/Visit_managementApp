<?php

namespace App\Livewire;

use App\Models\VisitRequest;
use App\Models\Status;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class MyRequests extends Component
{
    use WithPagination;

    public $showDetailModal = false;
    public $selectedRequest;

    #[On('cancel-request')]
    public function cancel($requestId)
    {
        $visitRequest = VisitRequest::where('id', $requestId)->where('user_id', Auth::id())->firstOrFail();
        $this->authorize('cancel', $visitRequest);

        if ($visitRequest->status->name !== 'Pending') {
            $this->dispatch('show-toast', type: 'error', message: 'Hanya permintaan yang pending yang bisa dibatalkan.');
            return;
        }

        $cancelledStatusId = Status::getIdByName('Cancelled');
        $visitRequest->update(['status_id' => $cancelledStatusId]);

        $this->dispatch('show-toast', type: 'success', message: 'Permintaan berhasil dibatalkan.');
    }

    public function viewDetail($requestId)
    {
        $this->selectedRequest = VisitRequest::with(['user.profile.department', 'status', 'approver'])
            ->where('id', $requestId)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $this->showDetailModal = true;
    }

    public function closeModal()
    {
        $this->reset(['showDetailModal', 'selectedRequest']);
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