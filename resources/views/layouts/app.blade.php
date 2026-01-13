<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Monitoring MikroTik</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-slate-100">

<div class="flex min-h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-slate-900 text-slate-200 flex flex-col">

        <!-- LOGO -->
        <div class="p-5 text-xl font-bold border-b border-slate-700">
            MikroTik Monitor
        </div>

        <!-- MENU -->
        <nav class="p-4 space-y-1 text-sm flex-1">

            <a href="/dashboard"
               class="block px-3 py-2 rounded
               {{ request()->is('dashboard') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                Dashboard
            </a>

            <a href="/router/add"
               class="block px-3 py-2 rounded
               {{ request()->is('router*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800' }}">
                Tambah MikroTik
            </a>

            @if(session('role') === 'administrator')
                <a href="/admin"
                   class="block px-3 py-2 rounded font-semibold
                   {{ request()->is('admin*') ? 'bg-slate-800 text-yellow-400' : 'hover:bg-slate-800 text-yellow-400' }}">
                    Manajemen Admin
                </a>
            @endif

        </nav>

        <!-- FOOTER SIDEBAR -->
        <div class="border-t border-slate-700 p-4 text-sm">

            <div class="mb-2 text-slate-400">
                Login sebagai:
                <span class="font-semibold text-white">
                    {{ session('role') }}
                </span>
            </div>

            <button
                onclick="openLogoutModal()"
                class="w-full text-left px-3 py-2 rounded text-red-400 hover:bg-slate-800">
                Logout
            </button>

        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <header class="bg-white shadow px-6 py-4 flex items-center justify-between">

            <h1 class="text-xl font-semibold text-slate-700">
                @yield('title', 'Dashboard')
            </h1>

            <!-- PROFILE -->
            <div class="relative">

                <!-- TRIGGER -->
                <button onclick="toggleProfileMenu()"
                        class="flex items-center gap-3 focus:outline-none">

                    <!-- AVATAR -->
                    <div class="w-9 h-9 rounded-full bg-blue-600
                                flex items-center justify-center
                                text-white font-bold uppercase">
                        {{ substr(session('role'),0,1) }}
                    </div>

                    <!-- TEXT -->
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-semibold text-slate-700">
                            {{ ucfirst(session('role')) }}
                        </p>
                        <p class="text-xs text-slate-400">
                            {{ session('role') }}
                        </p>
                    </div>

                    <!-- ARROW -->
                    <svg class="w-4 h-4 text-slate-500"
                         xmlns="http://www.w3.org/2000/svg" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>

                </button>

                <!-- DROPDOWN -->
                <div id="profileMenu"
                     class="hidden absolute right-0 mt-3 w-48
                            bg-white rounded shadow border text-sm z-50">

                    <div class="px-4 py-3 border-b">
                        <p class="font-semibold text-slate-700">
                            {{ ucfirst(session('role')) }}
                        </p>
                        <p class="text-xs text-slate-400">
                            {{ session('role') }}
                        </p>
                    </div>

                    <button
                        onclick="openLogoutModal()"
                        class="w-full text-left px-4 py-2 text-red-600 hover:bg-slate-100">
                        Logout
                    </button>

                </div>
            </div>

        </header>

        <!-- PAGE CONTENT -->
        <section class="p-6 flex-1">
            @yield('content')
        </section>

    </main>

</div>

<!-- LOGOUT MODAL -->
<div id="logoutModal"
     class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">

    <div class="bg-white rounded-lg w-full max-w-sm p-6 shadow-lg">

        <h3 class="text-lg font-semibold text-slate-700 mb-2">
            Konfirmasi Logout
        </h3>

        <p class="text-sm text-slate-500 mb-6">
            Apakah kamu yakin ingin logout dari sistem?
        </p>

        <div class="flex justify-end gap-3">

            <button
                onclick="closeLogoutModal()"
                class="px-4 py-2 rounded bg-slate-200 hover:bg-slate-300">
                Batal
            </button>

            <form method="POST" action="/logout">
                @csrf
                <button
                    class="px-4 py-2 rounded bg-red-600 hover:bg-red-700 text-white">
                    Logout
                </button>
            </form>

        </div>
    </div>
</div>

<!-- SCRIPT -->
<script>
function toggleProfileMenu() {
    document.getElementById('profileMenu').classList.toggle('hidden');
}

function openLogoutModal() {
    document.getElementById('logoutModal').classList.remove('hidden');
}

function closeLogoutModal() {
    document.getElementById('logoutModal').classList.add('hidden');
}

document.addEventListener('click', function (e) {
    if (!e.target.closest('#profileMenu') &&
        !e.target.closest('button[onclick="toggleProfileMenu()"]')) {
        document.getElementById('profileMenu').classList.add('hidden');
    }
});
</script>

</body>
</html>
