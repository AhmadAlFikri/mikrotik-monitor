<?php

namespace App\Http\Controllers;

use App\Models\Router;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class RouterController extends Controller
{
    // TAMPILKAN FORM TAMBAH ROUTER
    public function add()
    {
        return view('router.add');
    }

    // SIMPAN ROUTER KE DATABASE (INI STEP 7)
    public function store(Request $request)
    {
        // VALIDASI DASAR
        $request->validate([
            'name' => 'required',
            'ip' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        // SIMPAN KE DATABASE (PASSWORD DI-ENKRIP)
        Router::create([
            'name' => $request->name,
            'ip' => $request->ip,
            'username' => $request->username,
            'password' => Crypt::encryptString($request->password),
        ]);

        return redirect('/dashboard')->with('success', 'Router berhasil ditambahkan');
    }
}
