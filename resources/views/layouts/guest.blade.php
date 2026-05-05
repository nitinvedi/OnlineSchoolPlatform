<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Live School') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased selection:bg-brand-500/30 selection:text-white bg-dark-bg">
        
        <div class="min-h-screen flex w-full">
            
            <!-- Left Side: Graphic / Branding (Hidden on mobile) -->
            <div class="hidden lg:flex w-1/2 bg-[#08080a] relative items-center justify-center overflow-hidden border-r border-white/5">
                <!-- Abstract Background Elements -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(79,70,229,0.1),_transparent_50%),radial-gradient(circle_at_bottom_right,_rgba(79,70,229,0.1),_transparent_50%)]"></div>
                
                {{-- Decorative Grid --}}
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20 brightness-100 contrast-150"></div>
                <div class="absolute inset-0" style="background-image: radial-gradient(rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
                
                <div class="relative z-10 px-20 text-left max-w-2xl">
                    <a href="/" class="flex items-center gap-4 mb-16 hover:opacity-80 transition group">
                        <div class="w-12 h-12 bg-white text-dark-bg rounded-2xl flex items-center justify-center font-black shadow-2xl group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="font-display font-black text-3xl tracking-tighter text-white">LiveSchool</span>
                    </a>

                    <h1 class="text-6xl font-display font-black text-white leading-[1.1] mb-8 tracking-tighter">
                        Build the <span class="text-brand-500">future</span> of learning.
                    </h1>
                    <p class="text-xl text-slate-400 leading-relaxed font-medium mb-12 max-w-lg opacity-80">
                        Join an elite community of builders and scholars. Access world-class curriculum and interactive live environments.
                    </p>
                    
                    <div class="flex items-center gap-6">
                        <div class="flex -space-x-4">
                            @foreach([1, 2, 3, 4] as $i)
                                <img class="w-12 h-12 rounded-2xl border-4 border-[#08080a] shadow-xl" src="https://i.pravatar.cc/150?u={{ $i }}" alt="Student">
                            @endforeach
                        </div>
                        <div>
                            <p class="text-sm font-black text-white uppercase tracking-widest">+12k Learners</p>
                            <p class="text-xs font-bold text-slate-500 mt-0.5">Trusting LiveSchool today</p>
                        </div>
                    </div>
                </div>

                {{-- Bottom Badge --}}
                <div class="absolute bottom-12 left-20">
                    <div class="flex items-center gap-3 px-4 py-2 bg-white/5 backdrop-blur-xl border border-white/10 rounded-xl">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.5)]"></div>
                        <span class="text-[10px] font-black text-white uppercase tracking-widest opacity-80">Platform Status: Optimal</span>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 lg:px-20 bg-dark-bg relative">
                
                <!-- Mobile Logo -->
                <a href="/" class="absolute top-10 left-8 lg:hidden flex items-center gap-3">
                    <div class="w-10 h-10 bg-white text-dark-bg rounded-xl flex items-center justify-center font-black">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-display font-black text-2xl tracking-tighter text-white">LiveSchool</span>
                </a>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
            
        </div>
    </body>
</html>
