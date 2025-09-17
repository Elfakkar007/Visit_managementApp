<?php

namespace App\Livewire\Admin;

use App\Models\ApprovalWorkflow;
use Livewire\Component;
use Livewire\WithPagination;

class WorkflowIndex extends Component
{
    use WithPagination;

    public function delete($workflowId)
    {
        $workflow = ApprovalWorkflow::findOrFail($workflowId);
        $workflow->delete();
        $this->dispatch('show-toast', type: 'success', message: 'Aturan berhasil dihapus!');
    }

    public function render()
    {
        $workflows = ApprovalWorkflow::with([
            'requesterLevel',
            'requesterSubsidiary',
            'approverLevel'
        ])->latest()->paginate(10);

        return view('livewire.admin.workflow-index', [
            'workflows' => $workflows
        ]);
    }
}