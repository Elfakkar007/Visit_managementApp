<?php

namespace App\Livewire\Receptionist;

use App\Exports\GuestVisitsExport;
use App\Models\GuestVisit;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class GuestHistoryTable extends Component
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
        return Excel::download(new GuestVisitsExport($visits), 'riwayat_kunjungan_tamu.xlsx');
    }

    private function buildQuery()
    {
        // Perbedaan utama ada di sini: tidak ada filter status
        return GuestVisit::query()
            ->with('guest')
            ->when($this->searchName, fn($q) => $q->whereHas('guest', fn($sq) => $sq->where('name', 'ilike', '%' . $this->searchName . '%')))
            ->when($this->searchCompany, fn($q) => $q->whereHas('guest', fn($sq) => $sq->where('company', 'ilike', '%' . $this->searchCompany . '%')))
            ->when($this->searchDate, fn($q) => $q->whereDate('created_at', $this->searchDate)) // Filter berdasarkan tanggal dibuat
            ->latest('id');
    }

    public function render()
    {
        return view('livewire.receptionist.guest-history-table', [
            'visits' => $this->buildQuery()->paginate(10)
        ]);
    }
}