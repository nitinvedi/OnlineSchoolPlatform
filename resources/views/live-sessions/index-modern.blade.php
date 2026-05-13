<x-app-layout>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">

        <!-- HERO HEADER -->
        <div class="relative overflow-hidden">
            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-red-600 via-red-500 to-red-700"></div>
            <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
            
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-red-400/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-red-300/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-8 py-16 sm:py-20">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold text-white/80 border border-white/20">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M23 7l-7 5v-4L23 7zM1 6h14v12H1z"/></svg>
                            <span class="animate-pulse">🔴 Live Classes</span>
                        </div>

                        <h1 class="text-5xl lg:text-6xl font-display font-black tracking-tight leading-tight text-white mt-4">
                            Live Learning Sessions
                        </h1>

                        <p class="text-xl text-white/90 mt-4 max-w-2xl">
                            Join interactive live classes with expert instructors. Learn in real-time and connect with fellow students.
                        </p>
                    </div>

                    <div class="hidden lg:flex items-center justify-center w-20 h-20 rounded-2xl bg-white/10 backdrop-blur border border-white/20">
                        <svg class="w-10 h-10 text-white animate-pulse" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23 7l-7 5v-4L23 7zM1 6h14v12H1z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN CONTENT -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">

            @if($sessions->count() > 0)

                <!-- SESSIONS GRID -->
                <div class="space-y-8">

                    @php
                        $liveSessions = $sessions->filter(fn($s) => $s->isLive())->values();
                        $upcomingSessions = $sessions->filter(fn($s) => !$s->isLive())->values();
                    @endphp

                    <!-- LIVE NOW SECTION -->
                    @if($liveSessions->count() > 0)
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                </div>
                                <h2 class="text-2xl font-black text-slate-900">🔴 Live Now</h2>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($liveSessions as $session)
                                    <div class="group relative overflow-hidden rounded-2xl bg-white border-2 border-red-500 hover:border-red-600 shadow-lg shadow-red-500/20 hover:shadow-xl hover:shadow-red-500/30 transition-all duration-300">

                                        <!-- Live Badge -->
                                        <div class="absolute top-4 left-4 z-10 flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-full text-xs font-bold animate-pulse">
                                            <div class="flex h-2 w-2">
                                                <span class="animate-ping absolute inline-flex h-2 w-2 rounded-full bg-white opacity-75"></span>
                                                <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                                            </div>
                                            LIVE NOW
                                        </div>

                                        <!-- Course Badge -->
                                        <div class="absolute top-4 right-4 z-10">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/90 backdrop-blur text-slate-900 text-xs font-bold">
                                                {{ $session->course->category->name ?? 'Course' }}
                                            </span>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-6 h-full flex flex-col justify-between">

                                            <!-- Title & Course -->
                                            <div class="mb-6">
                                                <h3 class="text-xl font-black text-slate-900 mb-2 line-clamp-2">
                                                    {{ $session->title }}
                                                </h3>
                                                <a href="{{ route('courses.show', $session->course) }}" class="text-sm font-bold text-red-600 hover:text-red-700 hover:underline">
                                                    {{ $session->course->title }}
                                                </a>
                                            </div>

                                            <!-- Instructor Info -->
                                            <div class="flex items-center gap-3 mb-6">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($session->course->instructor->name) }}&background=EF4444&color=fff" 
                                                     alt="{{ $session->course->instructor->name }}" 
                                                     class="w-10 h-10 rounded-full border-2 border-red-200">
                                                <div>
                                                    <p class="text-sm font-bold text-slate-900">{{ $session->course->instructor->name }}</p>
                                                    <p class="text-xs text-slate-600">Instructor</p>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            @if($session->description)
                                                <p class="text-sm text-slate-600 mb-6 line-clamp-2">
                                                    {{ $session->description }}
                                                </p>
                                            @endif

                                            <!-- Time Info -->
                                            <div class="flex items-center gap-2 text-sm text-slate-600 mb-6 pb-6 border-b border-slate-200">
                                                <svg class="w-4 h-4 text-red-500" viewBox="0 0 24 24" fill="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                <span class="font-semibold">Started {{ $session->scheduled_at->diffForHumans() }}</span>
                                            </div>

                                            <!-- CTA -->
                                            <a href="{{ route('live-sessions.show', $session) }}" 
                                               class="block w-full text-center py-3 rounded-lg bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold text-sm transition-all duration-200 hover:shadow-lg hover:shadow-red-500/30 hover:scale-105">
                                                <span class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                                    Join Live Class
                                                </span>
                                            </a>

                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- UPCOMING SECTION -->
                    @if($upcomingSessions->count() > 0)
                        <div>
                            <h2 class="text-2xl font-black text-slate-900 mb-6 flex items-center gap-3">
                                <svg class="w-6 h-6 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                Upcoming Sessions
                            </h2>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($upcomingSessions as $session)
                                    <div class="group rounded-2xl bg-white border border-slate-200 hover:border-brand-300 hover:shadow-md transition-all duration-300 overflow-hidden">

                                        <!-- Status Badge -->
                                        <div class="absolute top-4 left-4 z-10">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-bold">
                                                Scheduled
                                            </span>
                                        </div>

                                        <!-- Course Category -->
                                        <div class="absolute top-4 right-4 z-10">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full bg-white/90 backdrop-blur text-slate-900 text-xs font-bold">
                                                {{ $session->course->category->name ?? 'Course' }}
                                            </span>
                                        </div>

                                        <!-- Content -->
                                        <div class="p-6 h-full flex flex-col justify-between relative">

                                            <!-- Title & Course -->
                                            <div class="mb-6 mt-6">
                                                <h3 class="text-lg font-black text-slate-900 mb-2 line-clamp-2 group-hover:text-brand-600 transition-colors">
                                                    {{ $session->title }}
                                                </h3>
                                                <a href="{{ route('courses.show', $session->course) }}" class="text-sm font-bold text-brand-600 hover:text-brand-700 hover:underline">
                                                    {{ $session->course->title }}
                                                </a>
                                            </div>

                                            <!-- Instructor Info -->
                                            <div class="flex items-center gap-3 mb-6">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($session->course->instructor->name) }}&background=2255FF&color=fff" 
                                                     alt="{{ $session->course->instructor->name }}" 
                                                     class="w-10 h-10 rounded-full border-2 border-brand-200">
                                                <div>
                                                    <p class="text-sm font-bold text-slate-900">{{ $session->course->instructor->name }}</p>
                                                    <p class="text-xs text-slate-600">Instructor</p>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            @if($session->description)
                                                <p class="text-sm text-slate-600 mb-6 line-clamp-2">
                                                    {{ $session->description }}
                                                </p>
                                            @endif

                                            <!-- Time Info -->
                                            <div class="flex flex-col gap-3 mb-6 pb-6 border-b border-slate-200">
                                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                                    <svg class="w-4 h-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                    {{ $session->scheduled_at->format('M d, Y') }}
                                                </div>
                                                <div class="flex items-center gap-2 text-sm font-semibold text-slate-900">
                                                    <svg class="w-4 h-4 text-brand-600" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                                                    {{ $session->scheduled_at->format('h:i A') }}
                                                </div>
                                                <p class="text-xs text-brand-600 font-semibold">{{ $session->scheduled_at->diffForHumans() }}</p>
                                            </div>

                                            <!-- CTA -->
                                            <button disabled 
                                                    class="w-full py-3 rounded-lg bg-slate-100 text-slate-400 font-bold text-sm cursor-not-allowed border-2 border-slate-200">
                                                <span class="flex items-center justify-center gap-2">
                                                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                                    Waiting to Start
                                                </span>
                                            </button>

                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                </div>

            @else

                <!-- EMPTY STATE -->
                <div class="rounded-2xl bg-white border border-slate-200 p-16 text-center">
                    <div class="flex items-center justify-center w-24 h-24 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-50 border border-slate-200 mx-auto mb-6">
                        <svg class="w-12 h-12 text-slate-400" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M23 7l-7 5v-4L23 7zM1 6h14v12H1z"/>
                        </svg>
                    </div>
                    
                    <h2 class="text-3xl font-black text-slate-900 mb-3">No Live Classes</h2>
                    
                    <p class="text-lg text-slate-600 max-w-md mx-auto mb-8">
                        There are no upcoming live classes for your enrolled courses at the moment. Check back soon!
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                        <a href="{{ route('dashboard') }}" 
                           class="inline-flex items-center h-12 px-6 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-900 font-bold transition-colors">
                            ← Back to Dashboard
                        </a>
                        
                        <a href="{{ route('courses.index') }}" 
                           class="inline-flex items-center h-12 px-6 rounded-lg bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 text-white font-bold transition-colors">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Explore Courses
                        </a>
                    </div>
                </div>

            @endif

            <!-- INFO SECTION -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16">

                <div class="rounded-2xl bg-white border border-slate-200 p-6">
                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-blue-100 text-blue-600 mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                    </div>
                    <h3 class="font-black text-slate-900 mb-2">Interactive Learning</h3>
                    <p class="text-sm text-slate-600">Engage with instructors and classmates in real-time Q&A sessions.</p>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 p-6">
                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-green-100 text-green-600 mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z"/></svg>
                    </div>
                    <h3 class="font-black text-slate-900 mb-2">Join Anytime</h3>
                    <p class="text-sm text-slate-600">Connect from anywhere. All you need is an internet connection.</p>
                </div>

                <div class="rounded-2xl bg-white border border-slate-200 p-6">
                    <div class="flex items-center justify-center w-12 h-12 rounded-lg bg-purple-100 text-purple-600 mb-4">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                    </div>
                    <h3 class="font-black text-slate-900 mb-2">Learn Together</h3>
                    <p class="text-sm text-slate-600">Connect with fellow students and grow your network.</p>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>
