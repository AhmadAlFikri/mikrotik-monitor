<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

// IMPORT MIDDLEWARE KAMU
use App\Http\Middleware\AdminOnly;
use App\Http\Middleware\AdministratorOnly;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
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
