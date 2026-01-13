@extends('layouts.app')

@section('content')

<h2 class="text-2xl font-bold mb-6">Tambah MikroTik</h2>

<form method="POST"
      action="/router/store"
      class="bg-white rounded shadow p-6 max-w-lg">

    @csrf

    <!-- NAMA ROUTER -->
    <label class="block text-sm font-semibold mb-1">Nama Router</label>
    <input
        name="name"
        class="w-full border rounded px-3 py-2 mb-4"
        placeholder="MikroTik A"
        required
    >

    <!-- IP GATEWAY -->
    <label class="block text-sm font-semibold mb-1">IP Gateway</label>
    <input
        name="ip"
        class="w-full border rounded px-3 py-2 mb-4"
        placeholder="192.168.1.1"
        required
    >

    <!-- USERNAME -->
    <label class="block text-sm font-semibold mb-1">Username</label>
    <input
        name="username"
        class="w-full border rounded px-3 py-2 mb-4"
        placeholder="admin"
        required
    >

    <!-- PASSWORD -->
    <label class="block text-sm font-semibold mb-1">Password</label>
    <input
        type="password"
        name="password"
        class="w-full border rounded px-3 py-2 mb-6"
        required
    >

    <button
        type="submit"
        class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded"
    >
        Simpan Router
    </button>

</form>

@endsection
