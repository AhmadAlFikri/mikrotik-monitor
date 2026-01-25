@extends('layouts.app')

@section('title', 'Session Logs')

@section('content')

<div class="bg-white rounded-2xl shadow-lg p-6 md:p-8">

    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
                Laporan Sesi Pengguna
            </h1>
            <p class="mt-1 text-slate-500">
                Riwayat sesi pengguna yang telah berakhir.
            </p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ url('/report/sessions/excel') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded">
                Export to Excel
            </a>
            <a href="{{ url('/report/sessions/pdf') }}" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                Export to PDF
            </a>
        </div>
    </div>

    <!-- Filter and Sort Form -->
    <form method="GET" action="{{ url('/report/sessions') }}" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="start_date" class="block text-sm font-medium text-slate-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('start_date') }}">
            </div>
            <div>
                <label for="end_date" class="block text-sm font-medium text-slate-700">End Date</label>
                <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('end_date') }}">
            </div>
            <div>
                <label for="sort_by" class="block text-sm font-medium text-slate-700">Sort By</label>
                <select name="sort_by" id="sort_by" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="username" @if($sortBy == 'username') selected @endif>Username</option>
                    <option value="router_name" @if($sortBy == 'router_name') selected @endif>Router Name</option>
                    <option value="login_time" @if($sortBy == 'login_time') selected @endif>Login Time</option>
                    <option value="logout_time" @if($sortBy == 'logout_time') selected @endif>Logout Time</option>
                </select>
            </div>
            <div>
                <label for="sort_order" class="block text-sm font-medium text-slate-700">Sort Order</label>
                <select name="sort_order" id="sort_order" class="mt-1 block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                    <option value="asc" @if($sortOrder == 'asc') selected @endif>Ascending</option>
                    <option value="desc" @if($sortOrder == 'desc') selected @endif>Descending</option>
                </select>
            </div>
        </div>
        <div class="mt-4 flex space-x-2">
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded">
                Filter
            </button>
            <a href="{{ url('/report/sessions') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded">
                Clear
            </a>
        </div>
    </form>

    <!-- Total Logs -->
    <div class="mb-4">
        <p class="text-sm text-slate-600">
            Total Logs: <span class="font-semibold">{{ $totalLogs }}</span>
        </p>
    </div>

    <!-- Session Log Table -->
    <div class="overflow-x-auto mt-6">
        <table class="min-w-full divide-y divide-slate-200">
            <thead class="bg-slate-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <a href="{{ url('/report/sessions?sort_by=username&sort_order=' . ($sortBy == 'username' && $sortOrder == 'asc' ? 'desc' : 'asc')) }}">Username</a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <a href="{{ url('/report/sessions?sort_by=router_name&sort_order=' . ($sortBy == 'router_name' && $sortOrder == 'asc' ? 'desc' : 'asc')) }}">Router Name</a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        Tanggal
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <a href="{{ url('/report/sessions?sort_by=login_time&sort_order=' . ($sortBy == 'login_time' && $sortOrder == 'asc' ? 'desc' : 'asc')) }}">Login Time</a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        <a href="{{ url('/report/sessions?sort_by=logout_time&sort_order=' . ($sortBy == 'logout_time' && $sortOrder == 'asc' ? 'desc' : 'asc')) }}">Logout Time</a>
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">
                        Duration
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-slate-200">
                @foreach($sessionLogs as $log)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                        {{ $log->username }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ $log->router_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($log->login_time)->timezone('Asia/Jakarta')->format('d-m-Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($log->login_time)->timezone('Asia/Jakarta')->format('H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($log->logout_time)->timezone('Asia/Jakarta')->format('d-m-Y H:i:s') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">
                        {{ \Carbon\Carbon::parse($log->logout_time)->timezone('Asia/Jakarta')->diffForHumans(\Carbon\Carbon::parse($log->login_time)->timezone('Asia/Jakarta'), true) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $sessionLogs->appends(request()->query())->links() }}
    </div>

</div>

@endsection

