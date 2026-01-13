<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
    @vite('resources/css/app.css')

    <!-- Animasi fade-out (inline agar FULL dalam satu file) -->
    <style>
        .fade-out {
            animation: fadeOut .6s ease-in-out forwards;
        }
        @keyframes fadeOut {
            from { opacity: 1; }
            to   { opacity: 0; }
        }
    </style>
</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center">

<!-- OVERLAY REDIRECT -->
<div id="redirectOverlay"
     class="fixed inset-0 bg-slate-900/70 hidden
            flex items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 text-center animate-pulse">
        <p class="text-slate-700 font-semibold mb-1">Login berhasil</p>
        <p class="text-sm text-slate-500">Mengalihkan ke dashboard...</p>
    </div>
</div>

<div class="w-full max-w-md">

    <!-- CARD -->
    <div class="bg-white shadow-lg rounded-lg p-8">

        <h2 class="text-2xl font-bold text-center mb-6 text-slate-700">
            Login Admin
        </h2>

        <!-- ERROR -->
        @if(session('error'))
            <div class="bg-red-100 text-red-600 text-sm p-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- FORM -->
        <form method="POST" action="/login" class="space-y-4" onsubmit="handleLogin()">
            @csrf

            <!-- USERNAME -->
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">
                    Username
                </label>
                <input
                    type="text"
                    name="username"
                    placeholder="Masukkan username"
                    class="w-full border border-slate-300 rounded px-3 py-2
                           focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required
                >
            </div>

            <!-- PASSWORD + TOGGLE -->
            <div>
                <label class="block text-sm font-semibold text-slate-600 mb-1">
                    Password
                </label>

                <div class="relative">
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Masukkan password"
                        class="w-full border border-slate-300 rounded px-3 py-2 pr-12
                               focus:outline-none focus:ring-2 focus:ring-blue-500"
                        required
                    >

                    <!-- TOGGLE ICON -->
                    <button type="button"
                        onclick="togglePassword()"
                        class="absolute inset-y-0 right-3 flex items-center
                               text-slate-500 hover:text-slate-700">

                        <!-- EYE OPEN -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M2.458 12C3.732 7.943 7.523 5 12 5
                                     c4.478 0 8.268 2.943 9.542 7
                                     -1.274 4.057-5.064 7-9.542 7
                                     -4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>

                        <!-- EYE OFF -->
                        <svg id="eyeOff" xmlns="http://www.w3.org/2000/svg"
                             class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M13.875 18.825A10.05 10.05 0 0112 19
                                     c-4.478 0-8.268-2.943-9.543-7
                                     a9.97 9.97 0 012.642-4.362M6.18 6.18
                                     A9.953 9.953 0 0112 5
                                     c4.478 0 8.268 2.943 9.543 7
                                     a9.978 9.978 0 01-4.132 5.411M15 12
                                     a3 3 0 00-3-3m0 0
                                     a3 3 0 013 3m-3-3L3 3m18 18l-3-3"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- BUTTON -->
            <button
                id="loginBtn"
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700
                       text-white py-2 rounded font-semibold transition
                       flex items-center justify-center gap-2"
            >
                <span id="btnText">Login</span>

                <!-- LOADING SPINNER -->
                <svg id="loader" class="hidden animate-spin h-5 w-5 text-white"
                     xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
            </button>
        </form>

    </div>

    <!-- FOOTER -->
    <p class="text-center text-sm text-slate-500 mt-4">
        © {{ date('Y') }} MikroTik Monitoring
    </p>

</div>

<!-- SCRIPT -->
<script>
function togglePassword() {
    const input = document.getElementById('password');
    const eyeOpen = document.getElementById('eyeOpen');
    const eyeOff  = document.getElementById('eyeOff');

    if (input.type === 'password') {
        input.type = 'text';
        eyeOpen.classList.add('hidden');
        eyeOff.classList.remove('hidden');
    } else {
        input.type = 'password';
        eyeOpen.classList.remove('hidden');
        eyeOff.classList.add('hidden');
    }
}

function handleLogin() {
    const btn     = document.getElementById('loginBtn');
    const text    = document.getElementById('btnText');
    const loader  = document.getElementById('loader');
    const overlay = document.getElementById('redirectOverlay');

    // tombol loading
    btn.disabled = true;
    btn.classList.add('opacity-70', 'cursor-not-allowed');
    text.textContent = 'Logging in...';
    loader.classList.remove('hidden');

    // animasi fade halaman
    setTimeout(() => {
        document.body.classList.add('fade-out');
    }, 400);

    // tampilkan overlay redirect
    setTimeout(() => {
        overlay.classList.remove('hidden');
    }, 700);
}
</script>

</body>
</html>
