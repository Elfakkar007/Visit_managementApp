<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisitRequest;
use App\Models\ApprovalWorkflow;
use Illuminate\Support\Facades\Log; // <-- DITAMBAHKAN

class VisitRequestPolicy
{
    /**
     * Berikan akses penuh kepada Admin untuk semua tindakan.
     */
    public function before(User $user, string $ability): bool|null
    {
        return $user->hasRole('Admin') ? true : null;
    }

    /**
     * Tentukan apakah user bisa melihat daftar semua request.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view all visit requests');
    }

    /**
     * Tentukan apakah user bisa melihat detail sebuah request.
     */
    public function view(User $user, VisitRequest $visitRequest): bool
    {
        if ($user->id === $visitRequest->user_id) {
            return true;
        }
        return $this->viewAny($user);
    }
    
    /**
     * Tentukan apakah user bisa menyetujui sebuah request.
     */
    public function approve(User $user, VisitRequest $visitRequest): bool
    {
        // --- BLOK PELACAKAN DIMULAI ---
        Log::info('--- POLICY CHECK: Memulai pengecekan approve untuk Request ID: ' . $visitRequest->id . ' ---');
        $requester = $visitRequest->user;

        // Mencatat data yang akan dicek
        Log::info('Requester Level ID: ' . $requester->profile->level_id . ' (' . $requester->profile->level->name . ')');
        Log::info('Approver Level ID: ' . $user->profile->level_id . ' (' . $user->profile->level->name . ')');
        
        $workflow = ApprovalWorkflow::where('requester_level_id', $requester->profile->level_id)
                                     ->where('approver_level_id', $user->profile->level_id)
                                     ->first();

        if (!$workflow) {
            Log::info('HASIL POLICY: Ditolak. Tidak ada workflow yang cocok ditemukan di database.');
            Log::info('----------------------------------------------------');
            return false;
        }

        Log::info('Workflow ditemukan. Scope yang dibutuhkan: ' . $workflow->scope);

        // Pengecekan scope
        $result = false;
        if ($workflow->scope === 'department') {
            $isMatch = $user->profile->department_id === $requester->profile->department_id;
            Log::info('Pengecekan scope department. Dept Approver: ' . $user->profile->department->name . ' | Dept Requester: ' . $requester->profile->department->name . '. Hasil: ' . ($isMatch ? 'Cocok' : 'Tidak Cocok'));
            $result = $isMatch;
        }

        if ($workflow->scope === 'subsidiary') {
            if ($requester->profile->subsidiary->name === 'Pusat') {
                $isMatch = in_array($user->profile->subsidiary->name, ['Agro', 'Aneka']);
                Log::info('Pengecekan scope subsidiary (kasus Pusat). Approver ada di ' . $user->profile->subsidiary->name . '. Hasil: ' . ($isMatch ? 'Cocok' : 'Tidak Cocok'));
                $result = $isMatch;
            } else {
                $isMatch = $user->profile->subsidiary_id === $requester->profile->subsidiary_id;
                Log::info('Pengecekan scope subsidiary (normal). Sub Approver: ' . $user->profile->subsidiary->name . ' | Sub Requester: ' . $requester->profile->subsidiary->name . '. Hasil: ' . ($isMatch ? 'Cocok' : 'Tidak Cocok'));
                $result = $isMatch;
            }
        }

        Log::info('--- HASIL FINAL POLICY: ' . ($result ? 'DIIZINKAN' : 'DITOLAK') . ' ---');
        Log::info('----------------------------------------------------');
        return $result;
    }

    /**
     * Tentukan apakah user bisa membatalkan sebuah request.
     */
    public function cancel(User $user, VisitRequest $visitRequest): bool
    {
        // Aturan: Hanya pemilik request yang bisa membatalkan.
        return $user->id === $visitRequest->user_id;
    }
}