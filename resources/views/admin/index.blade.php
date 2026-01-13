@extends('layouts.app')

@section('title', 'Manajemen Admin')

@section('content')

<!-- HEADER -->
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-slate-800">
            Manajemen Admin
        </h2>
        <p class="text-sm text-slate-500">
            Kelola akun admin dan administrator sistem
        </p>
    </div>

    <a href="/admin/create"
       class="inline-flex items-center gap-2
              bg-blue-600 hover:bg-blue-700
              text-white px-4 py-2 rounded shadow">
        <span class="text-lg">+</span>
        Tambah Admin
    </a>
</div>

<!-- CARD TABLE -->
<div class="bg-white rounded-lg shadow overflow-hidden">

    <!-- TABLE -->
    <table class="min-w-full text-sm">

        <!-- HEAD -->
        <thead class="bg-slate-800 text-white">
            <tr>
                <th class="px-4 py-3 text-left">Username</th>
                <th class="px-4 py-3 text-center">Role</th>
                <th class="px-4 py-3 text-center">Aksi</th>
            </tr>
        </thead>

        <!-- BODY -->
        <tbody class="divide-y">

            @foreach($admins as $admin)
            <tr class="hover:bg-slate-50">

                <!-- USERNAME -->
                <td class="px-4 py-3 font-medium text-slate-700">
                    {{ $admin->username }}
                </td>

                <!-- ROLE -->
                <td class="px-4 py-3 text-center">
                    @if($admin->role === 'administrator')
                        <span class="px-3 py-1 text-xs rounded-full
                                     bg-yellow-100 text-yellow-700 font-semibold">
                            Administrator
                        </span>
                    @else
                        <span class="px-3 py-1 text-xs rounded-full
                                     bg-blue-100 text-blue-700 font-semibold">
                            Admin
                        </span>
                    @endif
                </td>

                <!-- ACTION -->
                <td class="px-4 py-3 text-center space-x-3">

                    <a href="/admin/{{ $admin->id }}/edit"
                       class="text-blue-600 hover:underline font-medium">
                        Edit
                    </a>

                    <form action="/admin/{{ $admin->id }}"
                          method="POST"
                          class="inline"
                          onsubmit="return confirm('Yakin ingin menghapus admin ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="text-red-600 hover:underline font-medium">
                            Hapus
                        </button>
                    </form>

                </td>

            </tr>
            @endforeach

            @if($admins->count() === 0)
            <tr>
                <td colspan="3" class="px-4 py-6 text-center text-slate-400">
                    Belum ada admin terdaftar
                </td>
            </tr>
            @endif

        </tbody>

    </table>

</div>

@endsection
