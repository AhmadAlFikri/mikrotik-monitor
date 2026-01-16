<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', ''); // Initialize $search with an empty string if not present

        $query = Admin::query();

        if ($search) {
            $query->where('username', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
        }

        $admins = $query->paginate(10); // Remove orderBy

        return view('admin.index', compact('admins', 'search')); // Remove sortBy and sortDirection
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:admins',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        Admin::create([
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'role' => $request->role
        ]);

        return redirect('/admin')->with('success', 'Admin berhasil ditambahkan');
    }

    // 🔥 INI YANG DIPANGGIL SAAT KLIK EDIT
    public function edit($id)
    {
        $admin = Admin::findOrFail($id);
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::findOrFail($id);

        $admin->username = $request->username;
        $admin->role = $request->role;

        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }

        $admin->save();

        return redirect('/admin')->with('success', 'Admin berhasil diupdate');
    }

    public function destroy($id)
    {
        Admin::destroy($id);
        return redirect('/admin')->with('success', 'Admin berhasil dihapus');
    }
}
