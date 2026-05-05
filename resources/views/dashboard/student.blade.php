<x-app-layout>
    {{-- Quick Action FAB --}}
    <button class="fixed bottom-8 right-8 z-50 bg-sky-500 hover:bg-sky-600 text-white rounded-full p-4 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
    </button>

    @php
        $hour = date('H');
        $greeting = 'Good evening';
        if ($hour < 12) $greeting = 'Good morning';
        elseif ($hour < 17) $greeting = 'Good afternoon';
        
        $quotes = [
            "Learning is a treasure that will follow its owner everywhere.",
            "The beautiful thing about learning is that no one can take it away from you.",
            "Education is the passport to the future.",
            "Develop a passion for learning. If you do, you will never cease to grow."
        ];
        $quote = $quotes[array_rand($quotes)];
    @endphp

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        {{-- Dynamic Hero Greeting --}}
        <div class="mb-10">
            <h1 class="text-3xl md:text-4xl font-extrabold text-slate-900 tracking-tight">
                {{ $greeting }}, <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-500 to-indigo-500">{{ explode(' ', auth()->user()->name)[0] }}</span>!
            </h1>
            <p class="mt-2 text-slate-500 font-medium text-lg italic">"{{ $quote }}"</p>
        </div>

        {{-- Bento Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-10">
            
            {{-- "Jump Back In" Hero Card (col-span-8) --}}
            <div class="md:col-span-8 lg:col-span-8 bg-white rounded-3xl p-1 shadow-sm border border-slate-100 overflow-hidden relative group">
                @if($jumpBackIn)
                    @php
                        $nextLesson = null;
                        foreach($jumpBackIn->course->lessons as $lesson) {
                            if (!$jumpBackIn->completedLessons->contains('id', $lesson->id)) {
                                $nextLesson = $lesson;
                                break;
                            }
                        }
                    @endphp
                    <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 to-slate-900/40 z-10 rounded-[1.4rem] pointer-events-none"></div>
                    <img src="{{ $jumpBackIn->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($jumpBackIn->course->title).'&background=0ea5e9&color=fff&size=800' }}" alt="Course Thumbnail" class="absolute inset-0 w-full h-full object-cover rounded-[1.4rem] transform group-hover:scale-105 transition-transform duration-700">
                    
                    <div class="relative z-20 p-8 h-full flex flex-col justify-between min-h-[300px]">
                        <div>
                            <span class="inline-block px-3 py-1 bg-sky-500/20 text-sky-300 text-xs font-bold tracking-wider uppercase rounded-full backdrop-blur-md mb-4 border border-sky-400/30">Jump Back In</span>
                            <h2 class="text-3xl font-bold text-white mb-2 leading-tight drop-shadow-md">{{ $jumpBackIn->course->title }}</h2>
                            <p class="text-slate-300 font-medium line-clamp-2 max-w-lg">{{ $nextLesson ? 'Up Next: ' . $nextLesson->title : 'Course Completed! Review the material.' }}</p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-8">
                            <div class="flex items-center gap-4">
                                <div class="w-16 h-16 rounded-full flex items-center justify-center bg-white/20 backdrop-blur-md border border-white/30 text-white cursor-pointer hover:bg-white hover:text-sky-600 transition-colors duration-300">
                                    <a href="{{ $nextLesson ? route('lessons.show', [$jumpBackIn->course, $nextLesson]) : route('courses.show', $jumpBackIn->course) }}" class="flex items-center justify-center w-full h-full">
                                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    </a>
                                </div>
                                <div>
                                    <p class="text-white font-bold text-lg">{{ $jumpBackIn->progress_percent }}% Complete</p>
                                    <div class="w-32 h-1.5 bg-white/30 rounded-full mt-1 overflow-hidden">
                                        <div class="h-full bg-sky-400 rounded-full" style="width: {{ $jumpBackIn->progress_percent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-8 h-full flex flex-col justify-center items-center text-center bg-gradient-to-br from-sky-50 to-indigo-50 rounded-[1.4rem]">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 text-sky-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-slate-800 mb-2">Ready to start?</h2>
                        <p class="text-slate-500 mb-6">You aren't enrolled in any courses yet. Explore our catalog to begin your journey.</p>
                        <a href="{{ route('courses.index') }}" class="px-6 py-3 bg-sky-500 text-white font-bold rounded-xl shadow-sm hover:bg-sky-600 transition hover:-translate-y-0.5">Browse Catalog</a>
                    </div>
                @endif
            </div>

            {{-- Stats & SVG Progress Rings (col-span-4) --}}
            <div class="md:col-span-4 lg:col-span-4 grid grid-rows-2 gap-6">
                {{-- Stat 1: Bespoke Progress Ring --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Courses Completed</p>
                        <h3 class="text-4xl font-extrabold text-slate-800">{{ $stats['completedCourses'] }}<span class="text-lg text-slate-400 font-medium">/{{ $stats['enrolledCourses'] }}</span></h3>
                    </div>
                    <div class="relative w-20 h-20">
                        @php
                            $percentage = $stats['enrolledCourses'] > 0 ? round(($stats['completedCourses'] / $stats['enrolledCourses']) * 100) : 0;
                            $circumference = 2 * pi() * 36; // r=36
                            $offset = $circumference - ($percentage / 100) * $circumference;
                        @endphp
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="36" fill="transparent" stroke="#f1f5f9" stroke-width="8"></circle>
                            <circle cx="40" cy="40" r="36" fill="transparent" stroke="#0ea5e9" stroke-width="8" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" class="transition-all duration-1000 ease-out stroke-current text-sky-500"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-sm font-bold text-slate-700">{{ $percentage }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Stat 2: Learning Streak --}}
                <div class="bg-gradient-to-br from-indigo-500 to-purple-600 rounded-3xl p-6 shadow-md text-white flex flex-col justify-center relative overflow-hidden">
                    <svg class="absolute right-0 bottom-0 w-32 h-32 text-white/10 transform translate-x-8 translate-y-8" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <p class="text-sm font-bold text-indigo-100 uppercase tracking-wider mb-1">Current Streak</p>
                    <div class="flex items-baseline gap-2">
                        <h3 class="text-5xl font-extrabold">5</h3>
                        <span class="text-lg text-indigo-200 font-medium">Days</span>
                    </div>
                    <p class="text-sm text-indigo-100 mt-2 font-medium">You're on fire! Keep it up. 🔥</p>
                </div>
            </div>

        </div>

        {{-- Row 2: Live Sessions & Activity Timeline --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            
            {{-- Interactive Live Session Tickets --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Upcoming Live Sessions</h3>
                    <a href="{{ route('live-sessions.index') }}" class="text-sm font-bold text-sky-500 hover:text-sky-600">View All &rarr;</a>
                </div>
                
                @php
                    $enrolledCourseIds = auth()->user()->enrollments()->pluck('course_id');
                    $upcomingSessions = \App\Models\LiveSession::whereIn('course_id', $enrolledCourseIds)
                        ->with('course')
                        ->whereIn('status', ['scheduled', 'live'])
                        ->orderBy('scheduled_at')
                        ->take(2)
                        ->get();
                @endphp

                @forelse($upcomingSessions as $session)
                    <div class="flex bg-white rounded-2xl shadow-sm border border-slate-100 mb-4 overflow-hidden group hover:shadow-md transition-shadow relative">
                        <!-- Perforated Edge -->
                        <div class="w-12 bg-sky-50 border-r border-dashed border-sky-200 flex flex-col items-center justify-center relative">
                            <div class="w-4 h-4 bg-slate-50 rounded-full absolute -top-2"></div>
                            <div class="w-4 h-4 bg-slate-50 rounded-full absolute -bottom-2"></div>
                            <span class="transform -rotate-90 text-xs font-bold text-sky-600 tracking-widest uppercase whitespace-nowrap">Ticket</span>
                        </div>
                        
                        <div class="flex-1 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    @if($session->status === 'live')
                                        <span class="flex h-3 w-3 relative">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-3 w-3 bg-red-500"></span>
                                        </span>
                                        <span class="text-xs font-bold text-red-600 uppercase tracking-wider">Live Now</span>
                                    @else
                                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">{{ $session->scheduled_at->format('M d, Y') }} • {{ $session->scheduled_at->format('h:i A') }}</span>
                                    @endif
                                </div>
                                <h4 class="text-lg font-bold text-slate-800 mb-1">{{ $session->topic }}</h4>
                                <p class="text-sm text-slate-500 font-medium">via {{ $session->course->title }}</p>
                            </div>
                            
                            <a href="{{ route('live-sessions.show', $session) }}" class="px-5 py-2.5 bg-slate-900 text-white text-sm font-bold rounded-xl hover:bg-slate-800 transition shadow-sm self-start sm:self-auto whitespace-nowrap">
                                Join Session
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="bg-slate-50 border border-slate-100 border-dashed rounded-2xl p-8 text-center">
                        <p class="text-slate-500 font-medium">No upcoming live sessions scheduled.</p>
                    </div>
                @endforelse
            </div>

            {{-- Activity Timeline --}}
            <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100">
                <h3 class="text-xl font-bold text-slate-800 mb-6">Recent Activity</h3>
                <div class="relative border-l-2 border-slate-100 ml-3 space-y-8">
                    @php
                        // Mocking recent activity based on enrollments for visual aesthetic
                        $activities = $enrollments->take(3)->map(function($e) {
                            return [
                                'title' => $e->completed_at ? 'Completed Course' : 'Started Learning',
                                'desc' => $e->course->title,
                                'time' => $e->updated_at->diffForHumans(),
                                'icon' => $e->completed_at ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z',
                                'color' => $e->completed_at ? 'text-green-500 bg-green-50' : 'text-sky-500 bg-sky-50'
                            ];
                        });
                    @endphp
                    
                    @foreach($activities as $act)
                        <div class="relative pl-6">
                            <div class="absolute -left-3.5 top-0 w-7 h-7 {{ $act['color'] }} rounded-full flex items-center justify-center ring-4 ring-white">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $act['icon'] }}"></path></svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-800">{{ $act['title'] }}</p>
                                <p class="text-sm text-slate-500">{{ $act['desc'] }}</p>
                                <p class="text-xs text-slate-400 font-medium mt-1">{{ $act['time'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Achievement Badges & Learning Streak (GitHub style) --}}
        <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 mb-12">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-xl font-bold text-slate-800">Your Achievements</h3>
                <span class="px-3 py-1 bg-amber-50 text-amber-600 text-xs font-bold uppercase tracking-wider rounded-full border border-amber-200">Level 4 Scholar</span>
            </div>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                <div class="flex flex-col items-center group cursor-default">
                    <div class="w-20 h-20 bg-gradient-to-br from-amber-300 to-orange-500 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/30 mb-3 transform group-hover:scale-110 transition duration-300 ring-4 ring-white">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">First Steps</h4>
                    <p class="text-xs text-slate-500 mt-1">Joined the platform</p>
                </div>
                
                <div class="flex flex-col items-center group cursor-default">
                    <div class="w-20 h-20 bg-gradient-to-br from-sky-400 to-indigo-500 rounded-full flex items-center justify-center shadow-lg shadow-sky-500/30 mb-3 transform group-hover:scale-110 transition duration-300 ring-4 ring-white">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Bookworm</h4>
                    <p class="text-xs text-slate-500 mt-1">Completed 5 lessons</p>
                </div>

                <div class="flex flex-col items-center group cursor-default opacity-50 grayscale hover:grayscale-0 transition duration-500">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center shadow-lg mb-3 ring-4 ring-white">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Perfectionist</h4>
                    <p class="text-xs text-slate-500 mt-1">100% on a Quiz</p>
                </div>

                <div class="flex flex-col items-center group cursor-default opacity-50 grayscale hover:grayscale-0 transition duration-500">
                    <div class="w-20 h-20 bg-gradient-to-br from-pink-400 to-rose-500 rounded-full flex items-center justify-center shadow-lg mb-3 ring-4 ring-white">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Social Butterfly</h4>
                    <p class="text-xs text-slate-500 mt-1">Attended 3 Live Sessions</p>
                </div>
            </div>
            
            {{-- Contribution Calendar Mockup --}}
            <div class="mt-10 pt-8 border-t border-slate-100">
                <h4 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Learning Consistency (Last 30 Days)</h4>
                <div class="flex flex-wrap gap-2">
                    @for($i=0; $i<30; $i++)
                        @php
                            // Mock active days
                            $isActive = rand(1, 10) > 4;
                            $intensity = rand(1, 4);
                            $color = 'bg-slate-100';
                            if ($isActive) {
                                if ($intensity == 1) $color = 'bg-sky-200';
                                elseif ($intensity == 2) $color = 'bg-sky-300';
                                elseif ($intensity == 3) $color = 'bg-sky-400';
                                else $color = 'bg-sky-500';
                            }
                        @endphp
                        <div class="w-6 h-6 {{ $color }} rounded-md hover:ring-2 hover:ring-offset-1 hover:ring-sky-300 transition-all cursor-pointer" title="{{ rand(0, 5) }} lessons completed"></div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- Horizontal Recommendation Carousel --}}
        @if($recommendations->count() > 0)
            <div class="mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-slate-800">Recommended for You</h3>
                    <a href="{{ route('courses.index') }}" class="text-sm font-bold text-sky-500 hover:text-sky-600">Browse All &rarr;</a>
                </div>
                
                <div class="flex overflow-x-auto pb-6 -mx-4 px-4 sm:mx-0 sm:px-0 gap-6 snap-x hide-scrollbar">
                    @foreach($recommendations as $course)
                        <a href="{{ route('courses.show', $course) }}" class="min-w-[280px] max-w-[280px] sm:min-w-[320px] sm:max-w-[320px] flex-none bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-100 overflow-hidden group snap-start block">
                            <div class="aspect-video overflow-hidden relative">
                                <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=e2e8f0&color=475569&size=600' }}" alt="{{ $course->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute top-3 left-3 px-2 py-1 bg-white/90 backdrop-blur text-xs font-bold text-slate-800 rounded-md">
                                    {{ $course->category->name }}
                                </div>
                            </div>
                            <div class="p-5">
                                <h4 class="font-bold text-slate-800 text-lg mb-1 group-hover:text-sky-600 transition-colors line-clamp-1">{{ $course->title }}</h4>
                                <p class="text-slate-500 text-sm font-medium mb-4">{{ $course->instructor->name }}</p>
                                
                                <div class="flex items-center gap-1 text-amber-400">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-sm font-bold text-slate-700 ml-1">{{ number_format($course->rating, 1) }}</span>
                                    <span class="text-xs text-slate-400 font-medium ml-1">({{ number_format($course->student_count) }} students)</span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- All Enrollments Grid --}}
        <div>
            <h3 class="text-2xl font-bold text-slate-800 mb-6">Your Courses</h3>
            @if($enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($enrollments as $enrollment)
                        <a href="{{ route('courses.show', $enrollment->course) }}" class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 hover:shadow-lg transition-all group block">
                            <div class="flex gap-4">
                                <div class="w-24 h-24 rounded-xl overflow-hidden flex-shrink-0 relative">
                                    <img src="{{ $enrollment->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($enrollment->course->title).'&background=f8fafc&color=64748b&size=200' }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                </div>
                                <div class="flex-1 min-w-0 flex flex-col justify-center">
                                    <h4 class="font-bold text-slate-800 text-base line-clamp-2 group-hover:text-sky-600 transition-colors mb-1">{{ $enrollment->course->title }}</h4>
                                    <p class="text-sm text-slate-500 font-medium truncate">{{ $enrollment->course->instructor->name }}</p>
                                </div>
                            </div>
                            
                            <div class="mt-5">
                                <div class="flex justify-between text-xs font-bold text-slate-500 mb-2 uppercase tracking-wider">
                                    <span>Progress</span>
                                    <span class="text-sky-600">{{ $enrollment->progress_percent ?? 0 }}%</span>
                                </div>
                                <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-sky-500 h-1.5 rounded-full" style="width: {{ $enrollment->progress_percent ?? 0 }}%"></div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-8">
                    {{ $enrollments->links() }}
                </div>
            @else
                <p class="text-slate-500">You haven't enrolled in any courses yet.</p>
            @endif
        </div>

    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
