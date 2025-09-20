<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisitRequest;
use App\Services\WorkflowService; // 1. Import WorkflowService

class VisitRequestPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->can('view all visit requests')) {
            return true;
        }
        return null;
    }

    public function view(User $user, VisitRequest $visitRequest): bool
    {
        return $user->id === $visitRequest->user_id;
    }

    /**
     * Tentukan apakah user bisa menyetujui atau menolak sebuah request.
     * --- VERSI DIPERKUAT (ZERO TRUST) ---
     */
    public function approve(User $user, VisitRequest $visitRequest): bool
    {
        // Cek izin dasar untuk efisiensi
        if (!$user->can('approve visit requests')) {
            return false;
        }

        // Validasi ulang dengan WorkflowService sebagai sumber kebenaran absolut
        $validApprovers = app(WorkflowService::class)->findApproversFor($visitRequest->user);

        // Aksi hanya diizinkan jika user saat ini ada di dalam daftar approver yang sah
        // menurut workflow engine untuk request ini.
        return $validApprovers->contains('id', $user->id);
    }

    public function cancel(User $user, VisitRequest $visitRequest): bool
    {
        return $user->id === $visitRequest->user_id && $visitRequest->status->name === 'Pending';
    }
}