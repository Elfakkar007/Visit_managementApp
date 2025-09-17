<?php

namespace App\Livewire\Admin;

use App\Models\ApprovalWorkflow;
use App\Models\Level;
use App\Models\Subsidiary;
use Livewire\Component;

class WorkflowCreate extends Component
{
    // Properti untuk menampung data dari form (dihubungkan dengan wire:model)
    public $requester_level_id = '';
    public $subsidiary_id = '';
    public $approver_level_id = '';
    public $scope = '';

    // Aturan validasi
    protected $rules = [
        'requester_level_id' => 'required|exists:levels,id',
        'approver_level_id' => 'required|exists:levels,id',
        'scope' => 'required|in:department,subsidiary,cross_subsidiary',
        'subsidiary_id' => 'nullable|exists:subsidiaries,id',
    ];

    // Method yang akan dipanggil saat form disubmit (dihubungkan dengan wire:submit)
    public function save()
    {
        $this->validate();

        ApprovalWorkflow::create([
            'requester_level_id' => $this->requester_level_id,
            'requester_subsidiary_id' => $this->subsidiary_id ?: null, // Simpan null jika kosong
            'approver_level_id' => $this->approver_level_id,
            'scope' => $this->scope,
        ]);

        // Reset form setelah berhasil disimpan
        $this->reset();

        // Kirim pesan sukses
        $this->dispatch('show-toast', type: 'success', message: 'Aturan alur kerja berhasil dibuat!');

        // Optional: redirect ke halaman lain jika perlu
        // return $this->redirect('/admin/workflows', navigate: true);
    }

    public function render()
    {
        // Ambil data untuk dropdown
        $levels = Level::orderBy('name')->get();
        $subsidiaries = Subsidiary::orderBy('name')->get();

        return view('livewire.admin.workflow-create', [
            'levels' => $levels,
            'subsidiaries' => $subsidiaries,
        ]);
    }
}