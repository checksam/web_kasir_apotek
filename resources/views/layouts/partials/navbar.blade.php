<header class="sticky top-0 z-30 flex h-14 select-none items-center justify-between bg-[#006680] px-4 text-white shadow-sm sm:px-6">
    <!-- Left Section: Hamburger & Mobile Toggle -->
    <div class="flex items-center gap-4">
        <button type="button" class="rounded-md p-1.5 text-white/90 transition-colors hover:bg-white/10 hover:text-white focus:outline-none lg:hidden" @click="sidebarOpen = true" aria-label="Buka menu navigasi">
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
        <div class="relative" x-data="{ profileOpen: false }" @keydown.escape.window="profileOpen = false" @click.outside="profileOpen = false">
            <button type="button" @click="profileOpen = !profileOpen" :aria-expanded="profileOpen.toString()" aria-haspopup="true" aria-controls="profile-menu" class="flex items-center gap-2 bg-[#004e62] hover:bg-[#004354] text-white px-3 py-1.5 rounded-full text-xs sm:text-sm font-medium transition-colors shadow-sm focus:outline-none">
                <div class="w-5 h-5 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                    <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </div>
                <span>{{ auth()->user()->name ?? 'Pengguna' }}</span>
                <span class="text-teal-200">({{ auth()->user()?->getRoleNames()->first() ?? 'user' }})</span>
                <svg class="w-3 h-3 text-teal-200" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="profile-menu" x-cloak x-show="profileOpen" x-transition.origin.top.right class="absolute right-0 mt-2 w-48 rounded-md border border-slate-200 bg-white py-1 text-slate-700 shadow-lg">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm hover:bg-slate-50 focus:bg-slate-50 focus:outline-none">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
