<div class="fixed top-4 inset-x-0 z-[100] flex justify-center px-4">
    <nav x-data="{ open: false }" class="w-full max-w-6xl pointer-events-auto">
        {{-- Elegant Minimalist Glass Container --}}
        <div class="bg-white/80 backdrop-blur-md border border-slate-200/60 rounded-full shadow-sm px-6 py-2.5 transition-all relative z-10">
            <div class="flex items-center justify-between h-11 w-full">
                
                {{-- Left Side: Premium Solid Brand Identity --}}
                <div class="flex items-center shrink-0">
                    <a href="{{ Auth::check() ? route('dashboard') : '/' }}" class="flex items-center gap-2.5 focus:outline-none group relative z-20">
                        {{-- Clean minimalist solid geometric shape instead of AI gradient borders --}}
                        <div class="w-8 h-8 rounded-lg bg-[#2255FF] flex items-center justify-center text-white font-black font-display text-sm tracking-tighter shadow-sm group-hover:opacity-90 transition-opacity">
                            B
                        </div>
                        <span class="font-display font-black text-xl tracking-tight text-slate-900">
                            Bea<span class="text-[#2255FF]">con</span>
                        </span>
                    </a>
                </div>

                {{-- Center Navigation Links: Clean, Authentic Human Design --}}
                <div class="hidden md:flex items-center gap-8 relative z-20">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-xs font-bold uppercase tracking-widest transition-colors block py-1 {{ request()->routeIs('dashboard') ? 'text-[#2255FF]' : 'text-slate-500 hover:text-slate-900' }}">
                            Dashboard
                        </a>
                    @endauth

                    <a href="{{ route('courses.index') }}" class="text-xs font-bold uppercase tracking-widest transition-colors block py-1 {{ request()->routeIs('courses.*') ? 'text-[#2255FF]' : 'text-slate-500 hover:text-slate-900' }}">
                        Catalog
                    </a>

                    @auth
                        <a href="{{ route('live-sessions.index') }}" class="text-xs font-bold uppercase tracking-widest transition-colors block py-1 {{ request()->routeIs('live-sessions.*') ? 'text-[#2255FF]' : 'text-slate-500 hover:text-slate-900' }}">
                            Live Classes
                        </a>

                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold uppercase tracking-widest text-amber-600 hover:text-amber-700 transition-colors flex items-center gap-1 py-1">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                Admin
                            </a>
                        @endif
                    @endauth
                </div>

                {{-- Right Side: User Controls / Solid CTA Actions --}}
                <div class="hidden sm:flex items-center gap-4 shrink-0 relative z-20">
                    @auth
                        @if(Auth::user()->isInstructor())
                            <span class="px-2.5 py-1 rounded-md bg-slate-100 text-slate-700 font-mono text-[10px] font-bold uppercase tracking-widest">
                                Instructor
                            </span>
                        @endif

                        {{-- Human-Designed Profile Dropdown --}}
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center gap-2 pl-2 pr-1 py-1 rounded-full hover:bg-slate-50 transition-colors focus:outline-none cursor-pointer">
                                    <span class="text-xs font-bold text-slate-800 max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0F172A&color=fff&rounded=true&size=32" class="w-7 h-7 rounded-full">
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <div class="px-4 py-2.5 border-b border-slate-100">
                                    <p class="text-[10px] font-mono text-slate-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                
                                <div class="py-1">
                                    <x-dropdown-link :href="route('profile.edit')" class="text-xs font-medium text-slate-700 hover:text-slate-900">
                                        {{ __('Profile Settings') }}
                                    </x-dropdown-link>

                                    @if(Auth::user()->isAdmin())
                                        <x-dropdown-link :href="route('admin.dashboard')" class="text-xs font-medium text-amber-700 hover:text-amber-900">
                                            {{ __('Admin Dashboard') }}
                                        </x-dropdown-link>
                                    @endif
                                    
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                                onclick="event.preventDefault(); this.closest('form').submit();"
                                                class="text-xs font-medium text-red-600 hover:text-red-700">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </x-slot>
                        </x-dropdown>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-slate-600 hover:text-slate-900 uppercase tracking-wider transition-colors px-2 py-1 block">
                            Sign In
                        </a>
                        <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-[#2255FF] hover:bg-[#1a3fb3] text-white text-xs font-bold uppercase tracking-widest transition-all shadow-sm block">
                            Get Started
                        </a>
                    @endauth
                </div>

                {{-- Mobile Menu Trigger Button --}}
                <div class="flex items-center sm:hidden relative z-20">
                    <button @click="open = !open" class="p-1.5 text-slate-600 hover:text-slate-900 focus:outline-none cursor-pointer">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Mobile Dropdown Drawer --}}
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-150"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-100"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             style="display: none;"
             class="absolute top-full left-0 right-0 mt-2 sm:hidden border border-slate-200 bg-white rounded-2xl p-4 shadow-lg z-[101]">
            
            <div class="flex flex-col space-y-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-xs font-bold uppercase tracking-widest px-2 py-1 block {{ request()->routeIs('dashboard') ? 'text-[#2255FF]' : 'text-slate-700' }}">
                        Dashboard
                    </a>
                @endauth

                <a href="{{ route('courses.index') }}" class="text-xs font-bold uppercase tracking-widest px-2 py-1 block {{ request()->routeIs('courses.*') ? 'text-[#2255FF]' : 'text-slate-700' }}">
                    Catalog
                </a>

                @auth
                    <a href="{{ route('live-sessions.index') }}" class="text-xs font-bold uppercase tracking-widest px-2 py-1 block {{ request()->routeIs('live-sessions.*') ? 'text-[#2255FF]' : 'text-slate-700' }}">
                        Live Classes
                    </a>

                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.dashboard') }}" class="text-xs font-bold uppercase tracking-widest px-2 py-1 block text-amber-600">
                            Admin Center
                        </a>
                    @endif
                    
                    <div class="h-px bg-slate-100 my-2"></div>
                    
                    <div class="px-2 py-1">
                        <div class="text-xs font-bold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-[10px] text-slate-400">{{ Auth::user()->email }}</div>
                    </div>

                    <a href="{{ route('profile.edit') }}" class="text-xs font-medium text-slate-600 px-2 py-1 block">
                        Profile Settings
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left text-xs font-medium text-red-600 px-2 py-1 block">
                            Log Out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-xs font-bold text-slate-700 text-center py-2 border border-slate-200 rounded-xl block">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-white bg-[#2255FF] text-center py-2 rounded-xl block">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </nav>
</div>
