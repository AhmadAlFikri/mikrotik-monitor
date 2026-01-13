<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Services\MikrotikService;
use Illuminate\Support\Facades\Crypt;

class DashboardController extends Controller
{
    public function index()
    {
        $routers = Router::all();
        return view('dashboard.index', compact('routers'));
    }

    public function realtime($id)
    {
        $router = Router::find($id);

        if (!$router) {
            return response()->json([]);
        }

        return response()->json(
            MikrotikService::active(
                $router->ip,
                $router->username,
                Crypt::decryptString($router->password)
            )
        );
    }
}
