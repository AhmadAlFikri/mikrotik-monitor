<?php

use App\Http\Middleware\AdministratorOnly;
use App\Http\Middleware\AdminOnly;
use Illuminate\Console\Scheduling\Schedule;
// IMPORT MIDDLEWARE KAMU
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('app:fetch-mikrotik-traffic')->everyMinute();
        $schedule->command('app:fetch-user-traffic')->everyMinute();
    })
    // REGISTER MIDDLEWARE (PENGGANTI Kernel.php)
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'adminOnly' => AdminOnly::class,
            'administratorOnly' => AdministratorOnly::class,
        ]);
    })

    // EXCEPTION HANDLER
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })

    ->create();
