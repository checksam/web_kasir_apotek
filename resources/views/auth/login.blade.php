<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk | PharmaPOS - Terminal Administrasi Farmasi</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:wght@400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif

    <style>
        body {
            font-family: 'Instrument Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="bg-[#f8fafc] text-slate-800 min-h-screen flex flex-col justify-center items-center px-4 py-12 antialiased select-none">
    
    <!-- Top Header Area: Icon, Title, Subtitle -->
    <div class="text-center mb-8">
        <!-- Teal Rounded Icon Box with Pharmacy Mortar/Cross -->
        <div class="w-14 h-14 bg-[#006680] rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm text-white">
            <svg class="w-7 h-7 fill-current" viewBox="0 0 24 24">
                <path d="M19 7h-1V6a2 2 0 0 0-2-2h-1a2 2 0 0 0-2 2v1h-1V6a2 2 0 0 0-2-2H9a2 2 0 0 0-2 2v1H6a2 2 0 0 0-2 2v8a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V9a2 2 0 0 0-2-2Zm-6 3a1 1 0 0 1 2 0v2h2a1 1 0 1 1 0 2h-2v2a1 1 0 1 1-2 0v-2h-2a1 1 0 1 1 0-2h2v-2Zm-7-1h2v1H6V9Zm12 0h2v1h-2V9Z"/>
            </svg>
        </div>

        <h1 class="text-3xl font-bold text-slate-900 tracking-tight">PharmaPOS</h1>
        <p class="text-sm text-slate-500 mt-1">Terminal Administrasi Farmasi</p>
    </div>

    <!-- Login Card Container -->
    <div class="w-full max-w-md bg-white rounded-xl border border-slate-200 shadow-sm p-8">
        <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
            @csrf

            @if (isset($errors) && $errors->any())
                <div class="p-3 bg-rose-50 border border-rose-200 rounded-lg text-rose-700 text-xs font-medium">
                    {{ $errors->first() }}
                </div>
            @endif

            <!-- Field: Nama Pengguna -->
            <div>
                <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Pengguna</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <input id="email"
                           name="email"
                           type="text"
                           value="{{ old('email') }}"
                           placeholder="Masukkan nama pengguna"
                           required
                           autofocus
                           class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50/50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#006680] focus:border-transparent transition-colors">
                </div>
            </div>

            <!-- Field: Kata Sandi -->
            <div>
                <label for="password" class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <input id="password"
                           name="password"
                           type="password"
                           placeholder="Masukkan kata sandi"
                           required
                           class="w-full pl-10 pr-3.5 py-2.5 bg-slate-50/50 border border-slate-300 rounded-lg text-sm text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#006680] focus:border-transparent transition-colors">
                </div>
            </div>

            <!-- Checkbox & Forgot Password -->
            <div class="flex items-center justify-between pt-1 text-sm">
                <label class="flex items-center gap-2 cursor-pointer text-slate-600 text-xs sm:text-sm">
                    <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#006680] focus:ring-[#006680]">
                    <span>Ingat saya</span>
                </label>
                <a href="#" class="text-xs sm:text-sm font-medium text-[#006680] hover:text-[#004d60] hover:underline">
                    Lupa sandi?
                </a>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit"
                        class="w-full bg-[#006680] hover:bg-[#004d60] text-white py-2.5 px-4 rounded-lg flex items-center justify-center gap-2 font-medium text-sm transition-colors shadow-sm cursor-pointer">
                    <span>Masuk</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Footer Copyright Note -->
    <div class="mt-8 text-center text-xs text-slate-400 space-y-1">
        <p>Hanya untuk penggunaan internal yang sah.</p>
        <p>© 2026 PharmaPOS. Hak cipta dilindungi.</p>
    </div>

</body>
</html>
