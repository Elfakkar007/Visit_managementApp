<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VisitRequest;

class VisitRequestPolicy
{
    /**
     * Berikan akses penuh kepada pengguna yang memiliki izin 'Admin'.
     * Di Spatie, role 'Super Admin' biasanya diberikan semua izin secara default.
     * Kita akan gunakan Gate::before() untuk ini nanti agar lebih bersih.
     */
    public function before(User $user, string $ability): bool|null
    {
        // Jika user punya izin untuk mengelola SEMUA request, maka loloskan.
        // Di seeder, hanya role 'Admin' yang punya ini.
        if ($user->can('view all visit requests')) {
            return true;
        }
        return null;
    }

    /**
     * Tentukan apakah user bisa melihat detail sebuah request.
     */
    public function view(User $user, VisitRequest $visitRequest): bool
    {
        // Pengguna bisa melihat jika itu adalah request miliknya.
        return $user->id === $visitRequest->user_id;
    }

    /**
     * Tentukan apakah user bisa menyetujui atau menolak sebuah request.
     */
    public function approve(User $user, VisitRequest $visitRequest): bool
    {
        // Logika approve sekarang sangat sederhana:
        // Cukup cek apakah user punya izin 'approve visit requests'.
        // Logika kompleks 'siapa approver-nya' sudah diurus oleh query di RequestHistory.php
        return $user->can('approve visit requests');
    }

    /**
     * Tentukan apakah user bisa membatalkan sebuah request.
     */
    public function cancel(User $user, VisitRequest $visitRequest): bool
    {
        // Aturan tetap sama: Hanya pemilik request yang bisa membatalkan.
        return $user->id === $visitRequest->user_id && $visitRequest->status->name === 'Pending';
    }
}