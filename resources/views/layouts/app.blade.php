<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Nemodas Network Monitoring Dashboard System')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">


    <!-- Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <!-- Vite -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="antialiased bg-slate-50 text-slate-800">

@includeIf('components.loading')

<div x-data="{ sidebarOpen: false }" class="flex h-screen bg-white">

    <!-- ================= SIDEBAR ================= -->
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
        class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-slate-200
               transform transition-transform duration-300 ease-in-out
               md:relative md:translate-x-0 flex flex-col">

        <!-- LOGO -->
        <div class="flex items-center justify-center h-20 shrink-0">
            <a href="/dashboard" class="flex items-center gap-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <div class="text-center">
                    <h1 class="font-poppins text-2xl font-bold tracking-wider text-transparent bg-clip-text bg-gradient-to-r from-indigo-500 to-purple-500">
                        Nemodas
                    </h1>
                    <p class="font-poppins text-xs text-slate-500 tracking-wide">
                        Network Monitoring Dashboard System
                    </p>
                </div>
            </a>
        </div>

        <!-- NAVIGATION -->
        <nav class="flex-grow px-4 space-y-2">
            <div x-data="{ open: {{ request()->is('dashboard*') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg
                               transition-colors duration-200 text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                               {{ request()->is('dashboard*') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Dashboard</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="mt-2 space-y-2 pl-6">
                    <a href="/dashboard"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200
                              text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                              {{ request()->is('dashboard') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        <span>Dashboard Utama</span>
                    </a>
                    <div x-data="{ open: {{ request()->is('dashboard/bandwidth-report*') ? 'true' : 'false' }} }" class="relative">
                        <button @click="open = !open"
                                class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg
                                       transition-colors duration-200 text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                                       {{ request()->is('dashboard/bandwidth-report*') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                            <div class="flex items-center gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                                <span>Laporan Bandwidth</span>
                            </div>
                            <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                             class="mt-2 space-y-2 pl-6">
                            <a href="/dashboard/bandwidth-report/internet"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200
                                      text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                                      {{ request()->is('dashboard/bandwidth-report/internet') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.562a4.5 4.5 0 017.778 0M12 20h.01m-7.08-7.071a10.5 10.5 0 0114.142 0M1.394 8.394a15 15 0 0121.213 0" />
                                </svg>
                                <span>Internet</span>
                            </a>
                            <a href="/dashboard/bandwidth-report/ethernet"
                               class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200
                                      text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                                      {{ request()->is('dashboard/bandwidth-report/ethernet') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" />
                                </svg>
                                <span>Ethernet</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <x-nav-link href="/router/add" :active="request()->is('router*')">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Tambah Router</span>
            </x-nav-link>

            <div x-data="{ open: {{ request()->is('report*') ? 'true' : 'false' }} }" class="relative">
                <button @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-2.5 rounded-lg
                               transition-colors duration-200 text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                               {{ request()->is('report*') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2m-6 0h6M5 4h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V6a2 2 0 012-2z" />
                        </svg>
                        <span>Report</span>
                    </div>
                    <svg :class="open ? 'rotate-180' : ''" class="w-4 h-4 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                     class="mt-2 space-y-2 pl-6">
                    <a href="/report/monthly"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200
                              text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                              {{ request()->is('report/monthly') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.111 16.562a4.5 4.5 0 017.778 0M12 20h.01m-7.08-7.071a10.5 10.5 0 0114.142 0M1.394 8.394a15 15 0 0121.213 0" />
                        </svg>
                        <span>Monthly Report</span>
                    </a>
                    <a href="/report/sessions"
                       class="flex items-center gap-3 px-4 py-2.5 rounded-lg transition-colors duration-200
                              text-gray-500 hover:bg-indigo-100 hover:text-indigo-600
                              {{ request()->is('report/sessions') ? 'bg-indigo-600 text-white font-semibold shadow' : '' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                           <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Session Logs</span>
                    </a>
                </div>
            </div>

            @if(session('role') === 'administrator')
                <x-nav-link href="/admin" :active="request()->is('admin*')">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 11z" />
                    </svg>
                    <span>Manajemen Admin</span>
                </x-nav-link>
            @endif
        </nav>

        <!-- PROFILE -->
        <div class="px-4 py-4 border-t border-slate-200">
            <div class="flex items-center gap-3">
                 <div class="w-10 h-10 rounded-full bg-indigo-600/10 text-indigo-600 font-bold
                            flex items-center justify-center uppercase">
                    {{ strtoupper(substr(session('role','A'),0,1)) }}
                </div>
                <div class="flex-grow">
                    <div class="font-semibold text-slate-700">
                        {{ ucfirst(session('role')) }}
                    </div>
                    <div class="text-sm text-slate-500">Online</div>
                </div>
                <button onclick="openLogoutModal()"
                        class="text-slate-500 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </div>
    </aside>

    <!-- ================= MAIN ================= -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- HEADER -->
        <header class="sticky top-0 bg-white/70 backdrop-blur-lg shadow-sm z-10">
            <div class="flex items-center justify-between p-4 border-b border-slate-200">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-slate-700">@yield('title', 'Nemodas Network Monitoring Dashboard System')</h1>
                <div class="w-6"></div>
            </div>
        </header>

        <!-- CONTENT -->
        <main class="flex-1 overflow-y-auto bg-slate-100 opacity-0 transition-opacity duration-500 ease-in-out">
            <div class="container mx-auto px-6 py-8">
                @yield('content')
            </div>
        </main>
    </div>
</div>

<!-- ================= LOGOUT MODAL ================= -->
<div id="logoutModal"
     class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-[999]">
    <div class="bg-white rounded-lg w-full max-w-sm p-6 shadow-xl m-4">
        <h3 class="text-lg font-semibold mb-2">Konfirmasi Logout</h3>
        <p class="text-sm text-slate-600 mb-6">Apakah Anda yakin ingin keluar dari sesi ini?</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeLogoutModal()"
                    class="px-4 py-2 bg-slate-200 text-slate-800 rounded-md font-semibold text-sm
                           hover:bg-slate-300 transition">
                Batal
            </button>
            <form method="POST" action="/logout">
                @csrf
                <button class="px-4 py-2 bg-red-600 text-white rounded-md font-semibold text-sm
                               hover:bg-red-700 transition">
                    Logout
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openLogoutModal(){
    document.getElementById('logoutModal').classList.remove('hidden');
}
function closeLogoutModal(){
    document.getElementById('logoutModal').classList.add('hidden');
}

document.addEventListener("DOMContentLoaded", function() {
    const mainContent = document.querySelector('main');
    const loadingOverlay = document.getElementById('loading-overlay');

    // Fade in content on initial load
    if (mainContent) {
        mainContent.classList.remove('opacity-0');
    }
    if (loadingOverlay) {
        // Ensure loader is hidden on back/forward navigation
        loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
    }

    // Show loader and fade out content when navigating away
    const links = document.querySelectorAll('a:not([target="_blank"])');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            // Check if it's a normal navigation link
            const href = link.getAttribute('href');
            if (href && !href.startsWith('#') && !href.startsWith('javascript:')) {
                if (mainContent) {
                    mainContent.style.transition = 'opacity 0.3s ease-out';
                    mainContent.classList.add('opacity-0');
                }
                if (loadingOverlay) {
                    loadingOverlay.classList.remove('opacity-0', 'pointer-events-none');
                }
            }
        });
    });

    // Handle back/forward cache
    window.addEventListener('pageshow', function (event) {
        if (event.persisted) {
            if (mainContent) {
                mainContent.style.transition = 'none'; // No transition on bfcache restore
                mainContent.classList.remove('opacity-0');
            }
            if (loadingOverlay) {
                loadingOverlay.classList.add('opacity-0', 'pointer-events-none');
            }
        }
    });
});
</script>

</body>
</html>
