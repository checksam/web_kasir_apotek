<header class="h-14 bg-[#006680] text-white flex items-center justify-between px-4 sm:px-6 shadow-sm sticky top-0 z-30 select-none">
    <!-- Left Section: Hamburger & Mobile Toggle -->
    <div class="flex items-center gap-4">
        <button type="button" class="text-white/90 hover:text-white p-1.5 rounded-md hover:bg-white/10 transition-colors focus:outline-none" aria-label="Toggle Menu">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        @hasSection('navbar_search')
            @yield('navbar_search')
        @endif
    </div>

    <!-- Right Section: Breadcrumb & User Profile Dropdown -->
    <div class="flex items-center gap-4 sm:gap-6">
        <!-- Breadcrumb Text -->
        <div class="text-xs sm:text-sm text-teal-100 font-normal tracking-wide">
            @yield('breadcrumb', 'Home / Dashboard')
        </div>

        @hasSection('navbar_actions')
            @yield('navbar_actions')
        @endif

        <!-- Profile Dropdown Button -->
        <div class="relative">
            <button type="button" class="flex items-center gap-2 bg-[#004e62] hover:bg-[#004354] text-white px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-colors shadow-sm focus:outline-none">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span>admin</span>
                <svg class="w-3 h-3 text-teal-200" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
        </div>
    </div>
</header>
