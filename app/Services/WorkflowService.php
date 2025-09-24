<?php

namespace App\Services;

use App\Models\User;
use App\Models\ApprovalWorkflow;
use App\Models\VisitRequest;
use Illuminate\Support\Collection;

class WorkflowService
{
    /**
     * Menemukan workflow yang paling cocok untuk seorang requester dengan sistem skor.
     */
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

        return $matchingWorkflows->sortByDesc(function ($workflow) {
            $score = 0;
            foreach ($workflow->conditions as $condition) {
                $score += match ($condition->condition_type) {
                    'user' => 100,
                    'department', 'subsidiary', 'role' => 10,
                    'level' => 1,
                    default => 0,
                };
            }
            return $score;
        })->first();
    }

    /**
     * Mencari approver berdasarkan progress request (menerima VisitRequest).
     */
    public function findApproversFor(VisitRequest $request): Collection
    {
        $requester = $request->user;
        if (!$requester) return collect();

        $bestWorkflow = $this->findBestWorkflowFor($requester);
        if (!$bestWorkflow) return collect();

        $currentStepRules = $bestWorkflow->steps()->where('step', $request->current_step)->get();
        if ($currentStepRules->isEmpty()) return collect();

        $approverIds = collect();
        foreach ($currentStepRules as $stepRule) {
            $query = User::query();
            if ($stepRule->approver_type === 'user') {
                $query->where('id', $stepRule->approver_id);
            } else {
                $query->whereHas('profile', function ($q) use ($stepRule, $requester) {
                    if ($stepRule->approver_type === 'level') {
                        $q->where('level_id', $stepRule->approver_id);
                    }
                    if ($requester->profile) {
                        if ($stepRule->scope === 'department') {
                            $q->where('department_id', $requester->profile->department_id)
                              ->where('subsidiary_id', $requester->profile->subsidiary_id);
                        } elseif ($stepRule->scope === 'subsidiary') {
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
    
    /**
     * Mendapatkan ID request yang menunggu persetujuan seorang approver.
     */
    public function getRequestIdsFor(User $approver): array
    {
        $pendingRequests = VisitRequest::with('user.profile', 'user.roles')
            ->whereHas('status', fn($q) => $q->where('name', 'Pending'))
            ->get();

        $requestIdsForApprover = [];

        foreach ($pendingRequests as $request) {
            // Memanggil findApproversFor dengan input yang benar (objek $request)
            $expectedApprovers = $this->findApproversFor($request);
            if ($expectedApprovers->contains('id', $approver->id)) {
                $requestIdsForApprover[] = $request->id;
            }
        }
        return array_unique($requestIdsForApprover);
    }
}