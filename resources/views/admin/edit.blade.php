@extends('layouts.app')

@section('title', 'Edit Admin')

@section('content')

<div class="max-w-lg bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">Edit Admin</h2>

    <form method="POST" action="/admin/{{ $admin->id }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Username</label>
            <input type="text" name="username"
                   value="{{ $admin->username }}"
                   class="w-full border rounded px-3 py-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-1">Password (opsional)</label>
            <input type="password" name="password"
                   class="w-full border rounded px-3 py-2"
                   placeholder="Kosongkan jika tidak diubah">
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold mb-1">Role</label>
            <select name="role"
                    class="w-full border rounded px-3 py-2">
                <option value="admin" {{ $admin->role === 'admin' ? 'selected' : '' }}>
                    Admin
                </option>
                <option value="administrator" {{ $admin->role === 'administrator' ? 'selected' : '' }}>
                    Administrator
                </option>
            </select>
        </div>

        <div class="flex justify-end gap-2">
            <a href="/admin"
               class="px-4 py-2 bg-slate-200 rounded">
                Batal
            </a>
            <button
                class="px-4 py-2 bg-blue-600 text-white rounded">
                Simpan
            </button>
        </div>

    </form>

</div>

@endsection
