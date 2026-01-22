<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $admin = Admin::where('username', $request->username)->first();

        if ($admin && Hash::check($request->password, $admin->password)) {

            session([
                'admin_login' => true,
                'admin_id' => $admin->id,
                'role' => $admin->role,
            ]);

            // 🔥 BEDAKAN ROLE
            if ($admin->role === 'administrator') {
                return redirect('/admin');
            }

            return redirect('/dashboard');
        }

        return back()->with('error', 'Login gagal');
    }

    public function logout()
    {
        session()->forget(['admin_login', 'admin_id', 'role']);

        return redirect('/login');
    }
}
