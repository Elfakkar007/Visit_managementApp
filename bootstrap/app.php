<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('requests:cancel-overdue')->dailyAt('01:00'); // Berjalan setiap hari jam 1 pagi
    })
    ->withMiddleware(function (Middleware $middleware) {
         $middleware->alias([
            'is_admin' => \App\Http\Middleware\IsAdmin::class,
            'check.receptionist' => \App\Http\Middleware\CheckReceptionistRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
