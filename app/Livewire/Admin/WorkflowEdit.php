<?php

namespace App\Livewire\Admin;

use App\Models\ApprovalWorkflow;
use App\Models\Level;
use App\Models\Subsidiary;
use Livewire\Component;

class WorkflowEdit extends Component
{
    public $workflowId;
    public $workflow; // Untuk menyimpan model asli

    // Properti untuk form, sama seperti di Create
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

    // Method ini berjalan pertama kali saat komponen dimuat
    public function mount($workflowId)
    {
        $this->workflowId = $workflowId;
        $workflow = ApprovalWorkflow::findOrFail($workflowId);
        $this->workflow = $workflow;

        // Isi properti form dengan data yang ada
        $this->requester_level_id = $workflow->requester_level_id;
        $this->subsidiary_id = $workflow->requester_subsidiary_id;
        $this->approver_level_id = $workflow->approver_level_id;
        $this->scope = $workflow->scope;
    }

    // Method untuk update data
    public function update()
    {
        $validatedData = $this->validate();

        $this->workflow->update([
            'requester_level_id' => $validatedData['requester_level_id'],
            'requester_subsidiary_id' => $validatedData['subsidiary_id'] ?: null,
            'approver_level_id' => $validatedData['approver_level_id'],
            'scope' => $validatedData['scope'],
        ]);

        $this->dispatch('show-toast', type: 'success', message: 'Aturan alur kerja berhasil diperbarui!');

        // Redirect ke halaman index setelah update
        return $this->redirect('/admin/workflows', navigate: true);
    }

    public function render()
    {
        $levels = Level::orderBy('name')->get();
        $subsidiaries = Subsidiary::orderBy('name')->get();

        return view('livewire.admin.workflow-edit', [
            'levels' => $levels,
            'subsidiaries' => $subsidiaries,
        ]);
    }
}