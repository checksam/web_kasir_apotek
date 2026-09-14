<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'PharmaPOS')</title>
    <style>[x-cloak] { display: none !important; }</style>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Instrument+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback Tailwind CSS CDN for instant preview -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            brand: {
                                DEFAULT: '#006680',
                                dark: '#004d60',
                                accent: '#0e83a2',
                                darkcharcoal: '#2d3235',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Instrument Sans', 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background-color: #f8fafc;
        }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false" class="min-h-screen bg-[#f8fafc] text-slate-800 antialiased">
    <div class="min-h-screen">
        <!-- Sidebar Navigation -->
        @include('layouts.partials.sidebar')

        <!-- Mobile Backdrop -->
        <div x-cloak x-show="sidebarOpen" x-transition.opacity class="fixed inset-0 z-40 bg-black/50 lg:hidden" @click="sidebarOpen = false" aria-hidden="true"></div>

        <!-- Main Content Area -->
        <div class="flex min-h-screen min-w-0 flex-col lg:pl-64">
            <!-- Top Header Navbar -->
            @include('layouts.partials.navbar')

            <!-- Main Canvas Body -->
            <main class="w-full flex-1 overflow-x-hidden p-4 sm:p-6 md:p-8">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
