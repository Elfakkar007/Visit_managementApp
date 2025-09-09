<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApprovalWorkflow;
use App\Models\Level;
use Illuminate\Http\Request;

class ApprovalWorkflowController extends Controller
{
    public function index()
    {
        // Ambil semua workflow beserta relasi level untuk ditampilkan
        $workflows = ApprovalWorkflow::with('requesterLevel', 'approverLevel')->get();
        return view('admin.workflows.index', compact('workflows'));
    }

    public function create()
    {
        // Ambil semua level untuk mengisi dropdown di form
        $levels = Level::orderBy('name')->get();
        return view('admin.workflows.create', compact('levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'requester_level_id' => 'required|exists:levels,id',
            'approver_level_id' => 'required|exists:levels,id|different:requester_level_id',
            'scope' => 'required|in:department,subsidiary',
        ]);

        ApprovalWorkflow::create($request->all());

        return redirect()->route('admin.workflows.index')->with('success', 'Aturan alur approval berhasil ditambahkan.');
    }

    public function edit(ApprovalWorkflow $workflow)
    {
        $levels = Level::orderBy('name')->get();
        return view('admin.workflows.edit', compact('workflow', 'levels'));
    }

    public function update(Request $request, ApprovalWorkflow $workflow)
    {
        $request->validate([
            'requester_level_id' => 'required|exists:levels,id',
            'approver_level_id' => 'required|exists:levels,id|different:requester_level_id',
            'scope' => 'required|in:department,subsidiary',
        ]);

        $workflow->update($request->all());

        return redirect()->route('admin.workflows.index')->with('success', 'Aturan alur approval berhasil diperbarui.');
    }

    public function destroy(ApprovalWorkflow $workflow)
    {
        $workflow->delete();
        return redirect()->route('admin.workflows.index')->with('success', 'Aturan alur approval berhasil dihapus.');
    }
}