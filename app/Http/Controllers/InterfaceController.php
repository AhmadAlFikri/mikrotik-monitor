<?php

namespace App\Http\Controllers;

use App\Models\Router;
use App\Services\MikrotikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class InterfaceController extends Controller
{
    public function index()
    {
        $routers = Router::all();

        return view('interfaces.index', compact('routers'));
    }

    public function realtime($id, Request $request)
    {
        $router = Router::findOrFail($id);

        return response()->json(
            MikrotikService::getInterfaces(
                $router->ip,
                $router->username,
                Crypt::decryptString($router->password),
                $request->get('sort_column', 'name'),
                $request->get('sort_direction', 'asc')
            )
        );
    }
}
