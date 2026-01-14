@extends('layouts.app')

@section('title', 'Tambah Router MikroTik')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white p-6 sm:p-8 rounded-lg shadow-sm">
        <h2 class="text-2xl font-bold text-slate-800 mb-6">
            Formulir Router Baru
        </h2>

        <form method="POST" action="{{ url('/router/store') }}" class="space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-600 mb-1">
                    Nama Router
                </label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                    placeholder="Contoh: MikroTik Kantor Pusat"
                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                <p class="mt-2 text-xs text-slate-500">Nama yang mudah diingat untuk router ini.</p>
            </div>

            <div>
                <label for="ip" class="block text-sm font-medium text-slate-600 mb-1">
                    Alamat IP
                </label>
                <input id="ip" type="text" name="ip" value="{{ old('ip') }}" required
                    placeholder="Contoh: 192.168.88.1"
                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-slate-600 mb-1">
                    Username API
                </label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required
                    placeholder="Contoh: admin"
                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            </div>

            <div class="relative">
                <label for="password" class="block text-sm font-medium text-slate-600 mb-1">
                    Password API
                </label>
                <input id="password" type="password" name="password" required
                    class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pr-10">
                <button type="button" onclick="togglePasswordVisibility()"
                    class="absolute inset-y-0 right-0 top-7 flex items-center px-3 text-slate-500 hover:text-indigo-600 rounded-full">
                    <svg id="eyeIcon" class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="eyeOffIcon" class="h-5 w-5 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 012.642-4.362M6.18 6.18A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a9.978 9.978 0 01-4.132 5.411M15 12a3 3 0 00-3-3m0 0a3 3 0 013 3m-3-3L3 3m18 18l-3-3" />
                    </svg>
                </button>
            </div>

            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ url('/dashboard') }}"
                   class="px-4 py-2 bg-slate-200 text-slate-800 rounded-md font-semibold text-sm
                          hover:bg-slate-300 transition">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex justify-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Simpan Router
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    function togglePasswordVisibility() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');
        const eyeOffIcon = document.getElementById('eyeOffIcon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
</script>
@endsection
