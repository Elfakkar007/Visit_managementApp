<?php

namespace App\Providers;

use App\Models\User;
use App\Models\VisitRequest;
use App\Policies\VisitRequestPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // Mendaftarkan VisitRequestPolicy untuk model VisitRequest
        VisitRequest::class => VisitRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // --- DEFINISI SEMUA GATE ---

        // Gate untuk menu "Approval Dashboard"
        Gate::define('view-approval-dashboard', function (User $user) {
            return $user->profile?->role?->name === 'Approver';
        });

        // Gate untuk menu "Lihat Semua Request" (khusus HRD)
        Gate::define('view-all-requests', function (User $user) {
            return $user->profile?->department?->name === 'HRD';
        });

       
    }
}