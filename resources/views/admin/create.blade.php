@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-6">Tambah Admin</h2>

<form method="POST" action="/admin/store"
      class="bg-white p-6 rounded shadow max-w-md">
@csrf

<label>Username</label>
<input name="username" class="w-full border p-2 mb-4" required>

<label>Password</label>
<input type="password" name="password" class="w-full border p-2 mb-4" required>

<label>Role</label>
<select name="role" class="w-full border p-2 mb-6">
    <option value="admin">Admin</option>
    <option value="administrator">Administrator</option>
</select>

<button class="bg-blue-600 text-white px-4 py-2 rounded">
    Simpan
</button>
</form>
@endsection
