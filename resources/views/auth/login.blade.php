<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - MikroTik Monitor</title>

    @vite('resources/css/app.css')
</head>

<body class="antialiased bg-slate-50 text-slate-700">

    @includeIf('components.loading')

    <div class="min-h-screen flex flex-col items-center justify-center sm:p-6">

        <a href="/" class="flex items-center justify-center gap-2 mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-2xl font-bold tracking-wide text-slate-800">
                MikroTik Monitor
            </span>
        </a>

        <div id="login-container" class="w-full sm:max-w-md bg-white shadow-md rounded-lg px-6 py-8 transition-opacity duration-500 ease-in-out">

            <h2 class="text-2xl font-bold text-center mb-1 text-slate-800">
                Selamat Datang Kembali
            </h2>
            <p class="text-center text-sm text-slate-500 mb-6">Silakan masuk untuk melanjutkan.</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="bg-green-100 text-green-800 text-sm font-semibold p-3 rounded-md mb-4" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-100 text-red-800 text-sm p-3 rounded-md mb-4" role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-800 text-sm font-semibold p-3 rounded-md mb-4" role="alert">
                    {{ session('error') }}
                </div>
            @endif

            <form id="loginForm" method="POST" action="{{ url('/login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="username" class="block text-sm font-medium text-slate-600 mb-1">
                        Username
                    </label>
                    <input id="username" type="text" name="username" value="{{ old('username') }}" required
                        autofocus autocomplete="username"
                        class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-slate-600 mb-1">
                        Password
                    </label>
                    <input id="password" type="password" name="password" required
                        autocomplete="current-password"
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

                <div class="pt-2">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                        Login
                    </button>
                </div>
            </form>
        </div>

        <p class="text-center text-sm text-slate-500 mt-8">
            &copy; {{ date('Y') }} MikroTik Monitor. All rights reserved.
        </p>
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

        document.getElementById('loginForm').addEventListener('submit', function() {
            const overlay = document.getElementById('loading-overlay');
            const loginContainer = document.getElementById('login-container');
            
            if(overlay) {
                overlay.classList.remove('opacity-0', 'pointer-events-none');
            }
            if(loginContainer) {
                loginContainer.classList.add('opacity-0');
            }
        });
    </script>
</body>
</html>
