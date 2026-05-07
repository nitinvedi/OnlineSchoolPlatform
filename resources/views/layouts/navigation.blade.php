<nav x-data="{ open: false }" class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20 items-center">
            
            <div class="flex items-center gap-12">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ Auth::check() ? route('dashboard') : '/' }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 bg-violet-600 rounded-lg flex items-center justify-center text-white shadow-sm group-hover:scale-105 transition-all duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="font-display font-black text-2xl tracking-tighter text-gray-900">Live<span class="text-violet-600">School</span></span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center gap-8">
                    @auth
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-violet-600 font-semibold' : 'text-gray-600 font-medium' }} hover:text-violet-600 transition-colors text-sm uppercase tracking-widest">Dashboard</a>
                    @endauth
                    <a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.*') ? 'text-violet-600 font-semibold' : 'text-gray-600 font-medium' }} hover:text-violet-600 transition-colors text-sm uppercase tracking-widest">Courses</a>
                    @auth
                    <a href="{{ route('live-sessions.index') }}" class="{{ request()->routeIs('live-sessions.*') ? 'text-violet-600 font-semibold' : 'text-gray-600 font-medium' }} hover:text-violet-600 transition-colors text-sm uppercase tracking-widest">Live Classes</a>
                    @endauth
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center">
                @auth
                <x-dropdown align="right" width="64">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none transition-all duration-200">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b67f5&color=fff&rounded=true&size=32" alt="Avatar" class="w-8 h-8 rounded-lg shadow-sm">
                                <span>{{ Auth::user()->name }}</span>
                            </div>
                            <svg class="ml-2 h-4 w-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-6 py-4 border-b border-gray-100">
                            <p class="text-[10px] uppercase tracking-widest font-semibold text-gray-500 mb-1">Signed in as</p>
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                            <span class="inline-block px-2 py-0.5 bg-violet-50 text-violet-700 text-[10px] font-semibold rounded mt-2 uppercase tracking-tight">{{ Auth::user()->role }}</span>
                        </div>
                        <div class="p-2">
                            <x-dropdown-link :href="route('profile.edit')" class="rounded-xl hover:bg-brand-500/10 hover:text-brand-500">
                                {{ __('Profile Settings') }}
                            </x-dropdown-link>
                            
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="rounded-xl text-red-500 hover:bg-red-500/10">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </div>
                    </x-slot>
                </x-dropdown>
                @else
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 bg-gray-50 border border-gray-200 text-sm font-semibold rounded-lg text-gray-800 hover:bg-gray-100 focus:outline-none transition-all duration-200">
                    Login
                </a>
                @endauth
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="p-3 rounded-2xl text-slate-500 hover:bg-slate-100 transition-colors">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden border-slate-200 bg-white">
        <div class="p-4 space-y-2">
            @auth
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl">Dashboard</x-responsive-nav-link>
            @endauth
            <x-responsive-nav-link :href="route('courses.index')" :active="request()->routeIs('courses.*')" class="rounded-xl">Courses</x-responsive-nav-link>
            @auth
            <x-responsive-nav-link :href="route('live-sessions.index')" :active="request()->routeIs('live-sessions.*')" class="rounded-xl">Live Classes</x-responsive-nav-link>
            @endauth
        </div>
        @auth
        <div class="p-4 border-slate-200">
            <div class="flex items-center gap-3 px-3 mb-4">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3b67f5&color=fff&rounded=true&size=40" class="w-10 h-10 rounded-xl">
                <div>
                    <div class="font-bold text-slate-900">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-slate-500">{{ Auth::user()->email }}</div>
                </div>
            </div>
            <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl">Profile</x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="text-red-500 rounded-xl">Log Out</x-responsive-nav-link>
            </form>
        </div>
        @else
        <div class="p-4 border-slate-200">
            <a href="{{ route('login') }}" class="block px-3 py-2 text-sm font-medium text-slate-700 rounded-xl hover:bg-slate-100">Login</a>
        </div>
        @endauth
    </div>
</nav>
