<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Live School') }} - Premium Learning</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800,900&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .hero-gradient { background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 50%, #eff6ff 100%); }
        .mesh-gradient { background-color: #0ea5e9; background-image: radial-gradient(at 40% 20%, hsla(228,100%,74%,1) 0px, transparent 50%), radial-gradient(at 80% 0%, hsla(189,100%,56%,1) 0px, transparent 50%), radial-gradient(at 0% 50%, hsla(355,100%,93%,1) 0px, transparent 50%); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        @keyframes marquee { 0% { transform: translateX(0%); } 100% { transform: translateX(-100%); } }
        .animate-marquee { animation: marquee 25s linear infinite; }
        .glass-nav { background: rgba(255, 255, 255, 0.7); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800">

    @php
        $stats = [
            'students' => \App\Models\User::where('role', 'student')->count() + 1500, // +1500 mock for visual weight
            'courses' => \App\Models\Course::where('status', 'published')->count() + 45,
            'instructors' => \App\Models\User::where('role', 'instructor')->count() + 20
        ];
        $topCourses = \App\Models\Course::where('status', 'published')->with('instructor', 'category')->orderByDesc('student_count')->take(4)->get();
        $instructors = \App\Models\User::where('role', 'instructor')->take(5)->get();
    @endphp

    {{-- Glassmorphic Navigation --}}
    <nav x-data="{ scrolled: false, mobileMenu: false }" @scroll.window="scrolled = (window.pageYOffset > 20)" :class="{'glass-nav border-b border-white/20 shadow-sm': scrolled, 'bg-transparent': !scrolled}" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-400 to-indigo-500 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-sky-500/30">L</div>
                    <span class="font-extrabold text-2xl tracking-tight text-slate-900">Live<span class="text-sky-500">School</span></span>
                </div>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('courses.index') }}" class="font-bold text-slate-600 hover:text-sky-500 transition">Explore Courses</a>
                    <a href="#features" class="font-bold text-slate-600 hover:text-sky-500 transition">Features</a>
                    <a href="#instructors" class="font-bold text-slate-600 hover:text-sky-500 transition">Instructors</a>
                </div>
                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2.5 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition shadow-sm">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-sky-500 transition">Log in</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 bg-sky-500 text-white font-bold rounded-xl hover:bg-sky-600 transition shadow-lg shadow-sky-500/30 transform hover:-translate-y-0.5">Start Learning</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    {{-- Dynamic Hero Section --}}
    <div class="relative hero-gradient pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        {{-- Floating 3D/Abstract Shapes (CSS constructed) --}}
        <div class="absolute top-20 right-10 w-64 h-64 bg-indigo-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob"></div>
        <div class="absolute top-40 left-10 w-72 h-72 bg-sky-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-8 left-1/2 w-64 h-64 bg-pink-300 rounded-full mix-blend-multiply filter blur-3xl opacity-50 animate-blob animation-delay-4000"></div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <span class="inline-block py-1 px-3 rounded-full bg-sky-100 text-sky-600 font-bold text-sm mb-6 border border-sky-200 tracking-wide uppercase">The Future of E-Learning</span>
            <h1 class="text-5xl md:text-7xl font-black text-slate-900 tracking-tight leading-tight mb-8">
                Master New Skills with <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-indigo-600">World-Class Experts</span>
            </h1>
            <p class="mt-4 text-xl text-slate-600 max-w-2xl mx-auto mb-10 font-medium leading-relaxed">
                Join our premium platform to access interactive courses, live sessions, and a community of learners dedicated to growth.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('courses.index') }}" class="px-8 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-xl shadow-slate-900/20 text-lg flex items-center justify-center gap-2 group">
                    Explore Catalog
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
                <a href="#stats" class="px-8 py-4 bg-white text-slate-700 font-bold rounded-2xl hover:bg-slate-50 border border-slate-200 transition shadow-sm text-lg flex items-center justify-center gap-2">
                    <svg class="w-5 h-5 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path></svg>
                    See How It Works
                </a>
            </div>
        </div>
    </div>

    {{-- "Trusted By" Marquee --}}
    <div class="py-10 bg-white border-y border-slate-100 overflow-hidden">
        <p class="text-center text-sm font-bold text-slate-400 uppercase tracking-widest mb-6">Trusted by innovative teams worldwide</p>
        <div class="relative w-full max-w-7xl mx-auto overflow-hidden">
            <div class="absolute left-0 top-0 bottom-0 w-24 bg-gradient-to-r from-white to-transparent z-10"></div>
            <div class="absolute right-0 top-0 bottom-0 w-24 bg-gradient-to-l from-white to-transparent z-10"></div>
            <div class="flex animate-marquee whitespace-nowrap gap-16 items-center">
                {{-- Mock Logos (using font-black text for demo) --}}
                @for($i=0; $i<3; $i++)
                    <div class="text-2xl font-black text-slate-300">Google</div>
                    <div class="text-2xl font-black text-slate-300 italic">Microsoft</div>
                    <div class="text-2xl font-black text-slate-300 tracking-tighter">Spotify</div>
                    <div class="text-2xl font-black text-slate-300">Amazon</div>
                    <div class="text-2xl font-black text-slate-300 font-serif">Netflix</div>
                @endfor
            </div>
        </div>
    </div>

    {{-- Animated Statistics Counter --}}
    <div id="stats" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center transform hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 mx-auto bg-sky-50 rounded-2xl flex items-center justify-center text-sky-500 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-4xl font-black text-slate-900 mb-2">{{ number_format($stats['students']) }}+</h3>
                    <p class="text-slate-500 font-bold uppercase tracking-wide text-sm">Active Learners</p>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center transform hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 mx-auto bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h3 class="text-4xl font-black text-slate-900 mb-2">{{ number_format($stats['courses']) }}+</h3>
                    <p class="text-slate-500 font-bold uppercase tracking-wide text-sm">Premium Courses</p>
                </div>
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center transform hover:-translate-y-1 transition duration-300">
                    <div class="w-16 h-16 mx-auto bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                    </div>
                    <h3 class="text-4xl font-black text-slate-900 mb-2">4.9/5</h3>
                    <p class="text-slate-500 font-bold uppercase tracking-wide text-sm">Average Rating</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Interactive Course Preview Cards --}}
    <div class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Learn from the Best</h2>
                <p class="text-xl text-slate-500 font-medium">Discover our highest-rated courses, crafted by industry experts.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($topCourses as $course)
                    <div class="group relative bg-white rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 overflow-hidden transform hover:-translate-y-2">
                        <div class="aspect-[4/3] overflow-hidden relative bg-slate-900">
                            <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=1e293b&color=fff&size=600' }}" class="w-full h-full object-cover group-hover:opacity-50 transition-opacity duration-300">
                            {{-- Hover Play Overlay --}}
                            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <div class="w-16 h-16 bg-sky-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-sky-500/50">
                                    <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                </div>
                            </div>
                            <div class="absolute top-4 left-4">
                                <span class="px-3 py-1 bg-white/90 backdrop-blur text-xs font-bold text-slate-800 rounded-lg shadow-sm">{{ $course->category->name }}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-slate-900 mb-2 line-clamp-2 group-hover:text-sky-600 transition">{{ $course->title }}</h3>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-8 h-8 rounded-full bg-slate-200 overflow-hidden">
                                    <img src="{{ $course->instructor->avatar_url ? Storage::url($course->instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=e2e8f0' }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-medium text-slate-600">{{ $course->instructor->name }}</span>
                            </div>
                            <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                                <div class="flex items-center gap-1 text-amber-400">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="font-bold text-slate-700">{{ number_format($course->rating, 1) }}</span>
                                </div>
                                <a href="{{ route('courses.show', $course) }}" class="text-sm font-bold text-sky-500 group-hover:text-sky-600">View Course &rarr;</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-12 text-center">
                <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-100 text-slate-800 font-bold rounded-xl hover:bg-slate-200 transition">
                    View All Courses
                </a>
            </div>
        </div>
    </div>

    {{-- Bento Feature Grid --}}
    <div id="features" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16">
                <h2 class="text-4xl font-black text-slate-900 mb-4 tracking-tight">Everything You Need to Succeed</h2>
                <p class="text-xl text-slate-500 font-medium">A platform designed specifically to accelerate your learning journey.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 auto-rows-[300px]">
                {{-- Box 1 --}}
                <div class="md:col-span-2 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-[2rem] p-10 text-white relative overflow-hidden group">
                    <svg class="absolute -right-10 -bottom-10 w-64 h-64 text-white/10 transform group-hover:scale-110 transition duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
                    <div class="relative z-10">
                        <div class="w-14 h-14 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black mb-3">Cinematic Video Lessons</h3>
                        <p class="text-indigo-100 font-medium text-lg max-w-md">Experience crystal clear 4K video playback with our custom distraction-free theater mode.</p>
                    </div>
                </div>

                {{-- Box 2 --}}
                <div class="bg-white rounded-[2rem] p-10 border border-slate-100 shadow-sm relative overflow-hidden group hover:shadow-lg transition duration-300">
                    <div class="w-14 h-14 bg-sky-50 rounded-xl flex items-center justify-center text-sky-500 mb-6 group-hover:bg-sky-500 group-hover:text-white transition">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-900 mb-3">Interactive Quizzes</h3>
                    <p class="text-slate-500 font-medium">Test your knowledge with built-in assessments and instant feedback.</p>
                </div>

                {{-- Box 3 --}}
                <div class="bg-slate-900 rounded-[2rem] p-10 text-white relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
                    <div class="w-14 h-14 bg-white/10 rounded-xl flex items-center justify-center mb-6 relative z-10">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="text-2xl font-black mb-3 relative z-10">Live Sessions</h3>
                    <p class="text-slate-400 font-medium relative z-10">Connect directly with instructors through integrated live video classrooms.</p>
                </div>

                {{-- Box 4 --}}
                <div class="md:col-span-2 bg-white rounded-[2rem] p-10 border border-slate-100 shadow-sm flex flex-col justify-center overflow-hidden relative">
                    <div class="absolute right-0 top-0 bottom-0 w-1/2 bg-sky-50 transform skew-x-12 translate-x-10"></div>
                    <div class="relative z-10 max-w-md">
                        <div class="w-14 h-14 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 mb-6">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        </div>
                        <h3 class="text-3xl font-black text-slate-900 mb-3">Earn Certificates</h3>
                        <p class="text-slate-500 font-medium text-lg">Receive verifiable completion certificates to showcase on your LinkedIn profile.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Gradient CTA --}}
    <div class="mesh-gradient py-32 text-center text-white relative overflow-hidden">
        <div class="max-w-4xl mx-auto px-4 relative z-10">
            <h2 class="text-5xl md:text-6xl font-black mb-8 tracking-tight drop-shadow-lg">Ready to transform your career?</h2>
            <p class="text-xl md:text-2xl font-medium mb-12 text-sky-50 drop-shadow">Join thousands of students already learning on LiveSchool.</p>
            <a href="{{ route('register') }}" class="inline-block px-10 py-5 bg-white text-sky-600 font-black text-xl rounded-2xl shadow-2xl hover:shadow-xl hover:scale-105 transition-all duration-300">
                Create Free Account
            </a>
            <p class="mt-6 text-sky-100 font-medium">No credit card required. Cancel anytime.</p>
        </div>
    </div>

    {{-- Custom Footer Geometry --}}
    <div class="relative bg-slate-900 pt-20">
        {{-- Wavy Top Border --}}
        <div class="absolute top-0 left-0 w-full overflow-hidden leading-none transform -translate-y-full">
            <svg class="relative block w-full h-[50px]" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M321.39,56.44c58-10.79,114.16-30.13,172-41.86,82.39-16.72,168.19-17.73,250.45-.39C823.78,31,906.67,72,985.66,92.83c70.05,18.48,146.53,26.09,214.34,3V120H0V95.8C59.71,118.08,130.83,119.78,197.6,108.64Z" fill="#0f172a"></path>
            </svg>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12 border-b border-slate-800 pb-12">
                <div class="md:col-span-1">
                    <span class="font-extrabold text-2xl tracking-tight text-white mb-6 block">Live<span class="text-sky-500">School</span></span>
                    <p class="text-slate-400 font-medium">The premium platform for interactive online education and skill development.</p>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 tracking-wider uppercase text-sm">Platform</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">Browse Courses</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">Live Sessions</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">Pricing</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 tracking-wider uppercase text-sm">Company</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">About Us</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">Careers</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-bold mb-6 tracking-wider uppercase text-sm">Legal</h4>
                    <ul class="space-y-4">
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">Terms of Service</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-sky-400 transition font-medium">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="pt-8 text-center md:text-left text-slate-500 font-medium flex flex-col md:flex-row justify-between items-center">
                <p>&copy; {{ date('Y') }} LiveSchool Inc. All rights reserved.</p>
                <div class="flex gap-4 mt-4 md:mt-0">
                    <a href="#" class="text-slate-400 hover:text-white transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                    <a href="#" class="text-slate-400 hover:text-white transition"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3.444 15.908c-.19.043-1.636.196-3.328.196-1.666 0-3.036-.145-3.238-.19-.481-.107-.847-.464-.959-.942-.14-3.111-.14-6.326 0-9.437.111-.478.478-.835.959-.942.202-.045 1.572-.19 3.238-.19 1.692 0 3.138.153 3.328.196.481.107.847.464.959.942.14 3.111.14 6.326 0 9.437-.112.478-.478.835-.959.942zm-4.444-6.331l3.24 1.838-3.24 1.838v-3.676z"/></svg></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
