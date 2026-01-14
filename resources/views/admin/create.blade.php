@extends('layouts.app')

@section('title', 'Tambah Admin Baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">
            Formulir Admin Baru
        </h2>

        <form method="POST" action="{{ url('/admin') }}" class="space-y-6">
            @csrf

            <div>
                <label for="username" class="block text-sm font-medium text-slate-600 mb-1">
                    Username
                </label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required
                    autocomplete="username"
                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-600 mb-1">
                    Password
                </label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-600 mb-1">
                    Role
                </label>
                <select id="role" name="role"
                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="admin">Admin</option>
                    <option value="administrator">Administrator</option>
                </select>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4">
                 <a href="{{ url('/admin') }}"
                   class="px-4 py-2 bg-slate-200 text-slate-800 rounded-md font-semibold text-sm
                          hover:bg-slate-300 transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Admin
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
