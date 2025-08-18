<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisitRequest;

class VisitRequestPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        return $user->profile?->role?->name === 'Admin' ? true : null;
    }

    public function viewAny(User $user): bool
    {
        return $user->profile?->role?->name === 'Approver' || $user->profile?->department?->name === 'HRD';
    }

    public function view(User $user, VisitRequest $visitRequest): bool
    {
        if ($user->id === $visitRequest->user_id) return true;
        return $this->viewAny($user);
    }
    
    public function approve(User $user, VisitRequest $visitRequest): bool
    {
        $requester = $visitRequest->user;
        $userLevel = $user->profile?->level?->name;
        $requesterLevel = $requester->profile?->level?->name;
        
        if ($userLevel === 'Manager' && in_array($requesterLevel, ['Staff', 'SPV'])) {
            return $user->profile?->department_id === $requester->profile?->department_id && 
                   $user->profile?->subsidiary_id === $requester->profile?->subsidiary_id;
        }

        if ($userLevel === 'Deputi' && $requesterLevel === 'Manager') {
            $requesterSubsidiaryId = $requester->profile->subsidiary_id;
            $pusatId = \App\Models\Subsidiary::where('name', 'Pusat')->firstOrFail()->id;

            if ($requesterSubsidiaryId === $pusatId) return true;
            
            return $user->profile->subsidiary_id === $requesterSubsidiaryId;
        }
        
        return false;
    }
}