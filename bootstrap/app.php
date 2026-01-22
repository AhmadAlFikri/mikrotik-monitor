<?php

use App\Http\Middleware\AdministratorOnly;
use App\Http\Middleware\AdminOnly;
use Illuminate\Foundation\Application;
// IMPORT MIDDLEWARE KAMU
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

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
