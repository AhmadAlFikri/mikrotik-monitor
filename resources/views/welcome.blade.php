<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Selamat Datang di Nemodas</title>

    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-slate-50 text-slate-700">
    <div class="w-full">
        <!-- Header -->
        <header class="bg-white/80 backdrop-blur-lg shadow-sm sticky top-0 z-50">
            <div class="container mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <a href="/" class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
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
                    <div class="flex items-center">
                        <a href="{{ url('/login') }}"
                           class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md
                                  hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
                                  transition duration-150 ease-in-out">
                            Login
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center py-24 sm:py-32">
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 tracking-tight">
                    Pantau Jaringan Anda
                    <span class="block text-indigo-600">Dengan Mudah dan Real-time</span>
                </h1>
                <p class="mt-6 max-w-2xl mx-auto text-lg text-slate-600">
                    Dapatkan kendali penuh atas traffic, pengguna aktif, dan performa router MikroTik Anda melalui dashboard yang intuitif dan modern.
                </p>
                <div class="mt-8 flex justify-center gap-4">
                    <a href="{{ url('/login') }}"
                       class="inline-block px-8 py-3 bg-indigo-600 text-white font-bold rounded-lg shadow-lg
                              hover:bg-indigo-700 transform hover:-translate-y-1 transition-all duration-300">
                        Masuk ke Dashboard
                    </a>
                </div>
            </div>

            <!-- Features Section -->
            <div class="py-16 sm:py-24 bg-white rounded-xl shadow-lg -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl mx-auto text-center">
                    <h2 class="text-3xl font-bold text-slate-900">Fitur Unggulan</h2>
                    <p class="mt-4 text-slate-600">Semua yang Anda butuhkan untuk monitoring jaringan yang efisien.</p>
                </div>
                <div class="mt-12 grid gap-8 md:grid-cols-3">
                    <div class="text-center p-6 border border-slate-200 rounded-lg">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-slate-900">Real-time Monitoring</h3>
                        <p class="mt-2 text-slate-600">Lihat traffic upload/download dan pengguna aktif secara langsung tanpa delay.</p>
                    </div>
                    <div class="text-center p-6 border border-slate-200 rounded-lg">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-slate-900">Grafik Interaktif</h3>
                        <p class="mt-2 text-slate-600">Analisis tren traffic dengan mudah melalui grafik yang jelas dan mudah dibaca.</p>
                    </div>
                    <div class="text-center p-6 border border-slate-200 rounded-lg">
                        <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white mx-auto">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M15 21a6 6 0 00-9-5.197m9 5.197a6.002 6.002 0 00-3.41-1.176M9 6.528v-1.056a4.002 4.002 0 017.916 0v1.056" />
                            </svg>
                        </div>
                        <h3 class="mt-5 text-lg font-semibold text-slate-900">Manajemen Multi-Router</h3>
                        <p class="mt-2 text-slate-600">Tambahkan dan pantau beberapa perangkat MikroTik dari satu tempat.</p>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="mt-16 sm:mt-24 border-t">
            <div class="container mx-auto py-6 px-4 sm:px-6 lg:px-8 text-center text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} Nemodas. Dibuat dengan cinta dan kode.</p>
            </div>
        </footer>
    </div>
</body>

</html>