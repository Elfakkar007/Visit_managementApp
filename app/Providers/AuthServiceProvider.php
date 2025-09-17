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
        // 1. Ini sudah benar, kita tetap mendaftarkan Policy utama kita.
        VisitRequest::class => VisitRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

       
        Gate::before(function (User $user, string $ability) {
            return $user->hasRole('Admin') ? true : null;
        });

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Admin') ? true : null;
        });
    }
}