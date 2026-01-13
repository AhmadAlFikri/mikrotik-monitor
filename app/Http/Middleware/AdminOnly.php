<?php

namespace App\Http\Middleware;

use Closure;

class AdminOnly
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('admin_login')) {
            return redirect('/login');
        }

        if (!in_array(session('role'), ['admin','administrator'])) {
            abort(403);
        }

        return $next($request);
    }
}
