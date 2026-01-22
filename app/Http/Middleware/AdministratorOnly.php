<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdministratorOnly
{
    public function handle(Request $request, Closure $next)
    {
        if (! session('admin_login') || session('role') !== 'administrator') {
            abort(403);
        }

        return $next($request);
    }
}
