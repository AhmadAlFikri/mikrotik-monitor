@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">
                Manajemen Admin
            </h2>
            <p class="text-sm text-slate-500 mt-1">
                Kelola akun admin dan administrator untuk sistem.
            </p>
        </div>

        <a href="{{ url('/admin/create') }}"
            class="mt-4 sm:mt-0 inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-md shadow-sm text-sm transition">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span>Tambah Admin</span>
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm align-middle">
                <thead class="bg-slate-50 text-slate-600 font-semibold">
                    <tr class="text-left">
                        <th class="px-6 py-3">Username</th>
                        <th class="px-6 py-3">Role</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($admins as $admin)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-slate-800">
                                {{ $admin->username }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($admin->role === 'administrator')
                                    <span class="px-2.5 py-1 inline-flex items-center text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800">
                                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 20.944a11.955 11.955 0 009 2.056 11.955 11.955 0 009-2.056c0-1.281-.14-2.522-.402-3.712z" />
                                        </svg>
                                        Administrator
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                        Admin
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right font-medium space-x-4">
                                <a href="{{ url('/admin/' . $admin->id . '/edit') }}" class="text-indigo-600 hover:text-indigo-800">
                                    Edit
                                </a>
                                <button type="button" onclick="openDeleteModal('{{ url('/admin/' . $admin->id) }}')"
                                    class="text-red-600 hover:text-red-800">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                                Tidak ada data admin yang ditemukan.
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
                <h3 class="text-lg font-semibold mt-4">Hapus Admin</h3>
                <p class="text-sm text-slate-600 my-2">
                    Apakah Anda yakin ingin menghapus admin ini? Tindakan ini tidak dapat dibatalkan.
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
