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

    public function realtime($id, \Illuminate\Http\Request $request)
    {
        $router = Router::find($id);

        if (!$router) {
            return response()->json([]);
        }

        return response()->json(
            MikrotikService::active(
                $router->ip,
                $router->username,
                Crypt::decryptString($router->password),
                $request->get('sort_column', 'uptime'),
                $request->get('sort_direction', 'desc')
            )
        );
    }

    public function kickUser($routerId, $userId)
    {
        $router = Router::find($routerId);

        if (!$router) {
            return response()->json(['success' => false, 'message' => 'Router tidak ditemukan'], 404);
        }

        $result = MikrotikService::kickUser(
            $router->ip,
            $router->username,
            Crypt::decryptString($router->password),
            $userId
        );

        return response()->json($result);
    }
}
