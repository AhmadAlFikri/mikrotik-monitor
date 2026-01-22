@extends('layouts.app')

@section('title', 'Daftar Router')

@section('content')
<div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                Manajemen Router
            </h1>
            <p class="mt-1 text-slate-500">
                Kelola daftar perangkat MikroTik yang terhubung ke sistem.
            </p>
        </div>
        <a href="{{ url('/router/add') }}"
           class="mt-4 md:mt-0 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
            Tambah Router
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm" role="alert">
            {{ session('error') }}
        </div>
    @endif

    <!-- Router Table -->
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-slate-600 font-semibold">
                <tr class="text-left">
                    <th class="px-6 py-3">Nama Router</th>
                    <th class="px-6 py-3">IP Address</th>
                    <th class="px-6 py-3">Username</th>
                    <th class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse ($routers as $router)
                    <tr class="hover:bg-slate-50/70 transition-colors duration-150">
                        <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">{{ $router->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $router->ip }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-slate-500">{{ $router->username }}</td>
                        <td class="px-6 py-4 whitespace-nowrap space-x-4">
                            <button type="button" onclick="openDeleteModal('{{ url('/routers', $router->id) }}')"
                                    class="text-red-600 hover:text-red-900 font-semibold">
                                Hapus
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center p-8 text-slate-500">
                            Belum ada router yang ditambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-[999]">
    <div class="bg-white rounded-lg w-full max-w-sm p-6 shadow-xl m-4">
        <div class="text-center">
             <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-lg font-semibold mt-4">Hapus Router</h3>
            <p class="text-sm text-slate-600 my-2">
                Apakah Anda yakin ingin menghapus router ini? Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>
        <div class="flex justify-center gap-3 mt-6">
            <button onclick="closeDeleteModal()"
                class="px-4 py-2 rounded-md bg-slate-200 text-slate-800 hover:bg-slate-300 text-sm font-semibold transition">
                Batal
            </button>
            <form id="deleteForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="px-4 py-2 rounded-md bg-red-600 hover:bg-red-700 text-white text-sm font-semibold transition">
                    Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    function openDeleteModal(actionUrl) {
        document.getElementById('deleteForm').setAttribute('action', actionUrl);
        document.getElementById('deleteModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').classList.add('hidden');
    }
</script>
@endsection
