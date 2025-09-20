<?php

namespace App\Services;

use App\Models\User;
use App\Models\ApprovalWorkflow;
use App\Models\VisitRequest;
use Illuminate\Support\Collection;

class WorkflowService
{
    public function findBestWorkflowFor(User $requester): ?ApprovalWorkflow
    {
        $requesterProfile = $requester->profile;
        if (!$requesterProfile) return null;

        $workflows = ApprovalWorkflow::with('conditions')->get();

        $matchingWorkflows = $workflows->filter(function ($workflow) use ($requester, $requesterProfile) {
            if ($workflow->conditions->isEmpty()) return false;
            
            foreach ($workflow->conditions as $condition) {
                $match = match ($condition->condition_type) {
                    'user' => $requester->id == $condition->condition_value,
                    'department' => $requesterProfile->department_id == $condition->condition_value,
                    'subsidiary' => $requesterProfile->subsidiary_id == $condition->condition_value,
                    'role' => $requester->roles->pluck('id')->contains($condition->condition_value),
                    'level' => $requesterProfile->level_id == $condition->condition_value,
                    default => false,
                };
                if (!$match) return false;
            }
            return true;
        });

        if ($matchingWorkflows->isEmpty()) return null;

        return $matchingWorkflows->sortByDesc(fn ($workflow) => $workflow->conditions->count())->first();
    }

    public function findApproversFor(User $requester): Collection
    {
        $bestWorkflow = $this->findBestWorkflowFor($requester);
        if (!$bestWorkflow) return collect();

        $firstStep = $bestWorkflow->steps()->where('step', 1)->get();
        if ($firstStep->isEmpty()) return collect();

        $approverIds = collect();
        foreach ($firstStep as $step) {
            $query = User::query();
            if ($step->approver_type === 'user') {
                $query->where('id', $step->approver_id);
            } else {
                $query->whereHas('profile', function ($q) use ($step, $requester) {
                    if ($step->approver_type === 'level') {
                        $q->where('level_id', $step->approver_id);
                    }
                    if ($requester->profile) {
                        if ($step->scope === 'department') {
                            $q->where('department_id', $requester->profile->department_id);
                        } elseif ($step->scope === 'subsidiary') {
                            $q->where('subsidiary_id', $requester->profile->subsidiary_id);
                        }
                    } else {
                        $q->whereRaw('1 = 0');
                    }
                });
            }
            $approverIds = $approverIds->merge($query->pluck('id'));
        }
        return User::whereIn('id', $approverIds->unique())->get();
    }
    
    public function getRequestIdsFor(User $approver): array
    {
        $pendingRequests = VisitRequest::with('user.profile', 'user.roles')
            ->whereHas('status', fn($q) => $q->where('name', 'Pending'))
            ->get();

        $requestIdsForApprover = [];

        // HAPUS TOTAL BLOK 'canApproveAll'. 
        // Dashboard approval HARUS SELALU tunduk pada aturan workflow dinamis.

        foreach ($pendingRequests as $request) {
            // Langsung jalankan logika inti untuk setiap request tanpa bypass.
            $expectedApprovers = $this->findApproversFor($request->user);
            
            // Cek apakah user yang sedang login ($approver) ada di daftar approver yang sah.
            if ($expectedApprovers->contains('id', $approver->id)) {
                $requestIdsForApprover[] = $request->id;
            }
        }
        return array_unique($requestIdsForApprover);
    }
}