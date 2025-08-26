<?php

namespace App\Livewire\Receptionist;

use App\Exports\GuestVisitsExport;
use App\Models\GuestVisit;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class GuestStatusTable extends Component
{
    use WithPagination;

    public $searchName = '';
    public $searchCompany = ''; 
    public $searchDate = '';
    public $selectedVisit = null;

    public function viewDetail($visitId)
    {
        $this->selectedVisit = GuestVisit::with('guest', 'checkedInBy', 'checkedOutBy')->find($visitId);
    }

    public function closeModal()
    {
        $this->selectedVisit = null;
    }

    public function exportExcel()
    {
        $visits = $this->buildQuery()->get();
        return Excel::download(new GuestVisitsExport($visits), 'status_tamu_aktif.xlsx');
    }

    private function buildQuery()
    {
        return GuestVisit::query()
            ->with('guest')
            ->whereIn('status', ['waiting_check_in', 'checked_in'])
            ->when($this->searchName, fn($q) => $q->whereHas('guest', fn($sq) => $sq->where('name', 'ilike', '%' . $this->searchName . '%')))
            ->when($this->searchCompany, fn($q) => $q->whereHas('guest', fn($sq) => $sq->where('company', 'ilike', '%' . $this->searchCompany . '%')))
            ->when($this->searchDate, fn($q) => $q->whereDate('time_in', $this->searchDate))
            ->latest('time_in');
    }

    public function render()
    {
        return view('livewire.receptionist.guest-status-table', [
            'visits' => $this->buildQuery()->paginate(10)
        ]);
    }
}