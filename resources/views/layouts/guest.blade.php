<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Live School') }} - Authentication</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 antialiased selection:bg-sky-200 selection:text-sky-900 bg-white">
        
        <div class="min-h-screen flex w-full">
            
            <!-- Left Side: Graphic / Branding (Hidden on mobile) -->
            <div class="hidden lg:flex w-1/2 bg-slate-900 relative items-center justify-center overflow-hidden">
                <!-- Abstract Background Elements -->
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(56,189,248,0.15),_transparent_40%),radial-gradient(circle_at_bottom_right,_rgba(129,140,248,0.15),_transparent_40%)]"></div>
                <div class="absolute w-[600px] h-[600px] bg-sky-500/20 rounded-full blur-[100px] -top-20 -left-20"></div>
                <div class="absolute w-[500px] h-[500px] bg-indigo-500/20 rounded-full blur-[100px] bottom-0 right-0"></div>
                
                <div class="relative z-10 px-16 text-left max-w-xl">
                    <a href="/" class="flex items-center gap-3 mb-12 hover:opacity-80 transition">
                        <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-indigo-600 rounded-xl flex items-center justify-center text-white font-bold shadow-lg shadow-sky-500/30">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="font-black text-2xl tracking-tight text-white">LiveSchool</span>
                    </a>

                    <h1 class="text-5xl font-black text-white leading-tight mb-6">
                        Unlock your <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 to-indigo-400">potential.</span>
                    </h1>
                    <p class="text-lg text-slate-400 leading-relaxed">
                        Join thousands of learners accessing premium education, live classes, and interactive quizzes from anywhere in the world.
                    </p>
                    
                    <div class="mt-12 flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=1" alt="Student">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=2" alt="Student">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=3" alt="Student">
                        <img class="w-10 h-10 rounded-full border-2 border-slate-900" src="https://i.pravatar.cc/100?img=4" alt="Student">
                        <div class="w-10 h-10 rounded-full border-2 border-slate-900 bg-slate-800 flex items-center justify-center text-xs font-bold text-slate-300">+2k</div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="w-full lg:w-1/2 flex items-center justify-center px-6 py-12 lg:px-16 bg-white relative">
                
                <!-- Mobile Logo -->
                <a href="/" class="absolute top-8 left-6 lg:hidden flex items-center gap-2">
                    <div class="w-8 h-8 bg-gradient-to-br from-sky-400 to-indigo-600 rounded-lg flex items-center justify-center text-white font-bold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <span class="font-black text-xl text-slate-900">LiveSchool</span>
                </a>

                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>
            
        </div>
    </body>
</html>
