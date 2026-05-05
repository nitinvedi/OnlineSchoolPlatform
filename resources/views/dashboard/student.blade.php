<x-app-layout>
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

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        {{-- Dynamic Hero Greeting --}}
        <div class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
            <div>
                <h1 class="text-4xl md:text-6xl font-display font-black text-white tracking-tighter leading-tight">
                    {{ $greeting }}, <span class="text-brand-500">{{ explode(' ', auth()->user()->name)[0] }}</span>
                </h1>
                <p class="mt-4 text-slate-500 font-medium text-lg max-w-2xl italic opacity-80">"{{ $quote }}"</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('courses.index') }}" class="btn-primary">Explore Courses</a>
            </div>
        </div>

        {{-- Bento Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-16">
            
            {{-- "Jump Back In" Hero Card --}}
            <div class="md:col-span-8 bg-dark-card rounded-[3rem] p-2 border border-white/5 overflow-hidden relative group h-[400px]">
                @if($jumpBackIn)
                    @php
                        $nextLesson = null;
                        foreach($jumpBackIn->course->lessons as $lesson) {
                            if (!$jumpBackIn->completedLessons->contains('id', $lesson->id)) { $nextLesson = $lesson; break; }
                        }
                    @endphp
                    <div class="absolute inset-0 z-10 bg-gradient-to-t from-dark-bg/90 via-dark-bg/40 to-transparent"></div>
                    <img src="{{ $jumpBackIn->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($jumpBackIn->course->title).'&background=121217&color=fff&size=800' }}" 
                         class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000 opacity-60">
                    
                    <div class="relative z-20 p-10 h-full flex flex-col justify-end">
                        <div class="max-w-xl">
                            <span class="inline-block px-4 py-1.5 bg-brand-500 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-lg shadow-lg mb-6">Continue Learning</span>
                            <h2 class="text-4xl font-display font-black text-white mb-4 tracking-tighter leading-tight">{{ $jumpBackIn->course->title }}</h2>
                            <p class="text-slate-300 font-medium mb-8 opacity-80">{{ $nextLesson ? 'Up next: ' . $nextLesson->title : 'Course completed! Review the materials.' }}</p>
                            
                            <div class="flex items-center gap-8">
                                <a href="{{ $nextLesson ? route('lessons.show', [$jumpBackIn->course, $nextLesson]) : route('courses.show', $jumpBackIn->course) }}" 
                                   class="w-16 h-16 rounded-2xl bg-white text-brand-500 flex items-center justify-center shadow-2xl hover:scale-110 transition-transform duration-300">
                                    <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                </a>
                                <div class="flex-1">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Course Progress</span>
                                        <span class="text-sm font-black text-white">{{ $jumpBackIn->progress_percent }}%</span>
                                    </div>
                                    <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                                        <div class="h-full bg-brand-500 rounded-full transition-all duration-1000" style="width: {{ $jumpBackIn->progress_percent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="p-12 h-full flex flex-col justify-center items-center text-center bg-dark-surface/50">
                        <div class="w-20 h-20 bg-white/5 rounded-[2rem] flex items-center justify-center border border-white/10 mb-8">
                            <svg class="w-10 h-10 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        </div>
                        <h2 class="text-3xl font-display font-black text-white mb-4">Start Your Journey</h2>
                        <p class="text-slate-500 font-medium max-w-md mb-10 leading-relaxed">Enroll in your first course to begin mastering premium industry-standard skills.</p>
                        <a href="{{ route('courses.index') }}" class="btn-primary">Browse Catalog</a>
                    </div>
                @endif
            </div>

            {{-- Stats Cards --}}
            <div class="md:col-span-4 grid grid-rows-2 gap-8">
                {{-- Progress Ring Card --}}
                <div class="bg-dark-card rounded-[2.5rem] p-10 border border-white/5 flex items-center justify-between relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-brand-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Completed</p>
                        <h3 class="text-5xl font-display font-black text-white">{{ $stats['completedCourses'] }}<span class="text-xl text-slate-600 ml-1">/{{ $stats['enrolledCourses'] }}</span></h3>
                    </div>
                    <div class="relative w-24 h-24 z-10">
                        @php
                            $percentage = $stats['enrolledCourses'] > 0 ? round(($stats['completedCourses'] / $stats['enrolledCourses']) * 100) : 0;
                            $circumference = 2 * pi() * 36;
                            $offset = $circumference - ($percentage / 100) * $circumference;
                        @endphp
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 80 80">
                            <circle cx="40" cy="40" r="36" fill="transparent" stroke="rgba(255,255,255,0.05)" stroke-width="8"></circle>
                            <circle cx="40" cy="40" r="36" fill="transparent" stroke="currentColor" stroke-width="8" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" class="text-brand-500 transition-all duration-1000 ease-out"></circle>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <span class="text-xs font-black text-white">{{ $percentage }}%</span>
                        </div>
                    </div>
                </div>

                {{-- Streak Card --}}
                <div class="bg-brand-500 rounded-[2.5rem] p-10 shadow-2xl text-white relative overflow-hidden group">
                    <svg class="absolute -right-10 -bottom-10 w-48 h-48 text-white/10 group-hover:scale-110 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    <div class="relative z-10">
                        <p class="text-[10px] font-black text-brand-50 uppercase tracking-[0.2em] mb-4 opacity-80">Daily Streak</p>
                        <div class="flex items-baseline gap-2">
                            <h3 class="text-6xl font-display font-black">5</h3>
                            <span class="text-lg font-black uppercase opacity-60">Days</span>
                        </div>
                        <p class="text-sm font-bold mt-4 opacity-90">Keep the momentum going! 🔥</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- Row 2: Live Sessions & Activity --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12 mb-16">
            
            {{-- Upcoming Live Sessions --}}
            <div class="lg:col-span-2">
                <div class="flex items-center justify-between mb-10">
                    <h3 class="text-2xl font-display font-black text-white">Live Masterclasses</h3>
                    <a href="{{ route('live-sessions.index') }}" class="text-[10px] font-black text-brand-500 uppercase tracking-widest hover:text-brand-400 transition-colors">View Schedule &rarr;</a>
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

                <div class="space-y-6">
                    @forelse($upcomingSessions as $session)
                        <div class="flex bg-dark-card rounded-[2.5rem] border border-white/5 overflow-hidden group hover:border-brand-500/50 transition-all duration-500">
                            <div class="w-16 bg-white/5 flex flex-col items-center justify-center border-r border-white/5">
                                <span class="transform -rotate-90 text-[10px] font-black text-slate-500 uppercase tracking-[0.3em] whitespace-nowrap">Session</span>
                            </div>
                            
                            <div class="flex-1 p-8 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div>
                                    <div class="flex items-center gap-3 mb-3">
                                        @if($session->status === 'live')
                                            <span class="px-3 py-1 bg-rose-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg animate-pulse">Live Now</span>
                                        @else
                                            <span class="text-[10px] font-black text-brand-500 uppercase tracking-widest">{{ $session->scheduled_at->format('M d, Y') }} • {{ $session->scheduled_at->format('h:i A') }}</span>
                                        @endif
                                    </div>
                                    <h4 class="text-xl font-bold text-white mb-1">{{ $session->topic }}</h4>
                                    <p class="text-sm text-slate-500 font-medium opacity-80">{{ $session->course->title }}</p>
                                </div>
                                
                                <a href="{{ route('live-sessions.show', $session) }}" class="btn-primary py-3 px-8 text-xs whitespace-nowrap">
                                    Join Now
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-dark-surface/30 border-2 border-dashed border-white/5 rounded-[2.5rem] p-16 text-center">
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px]">No upcoming live sessions</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="bg-dark-card rounded-[3rem] p-10 border border-white/5">
                <h3 class="text-2xl font-display font-black text-white mb-10">Activity</h3>
                <div class="space-y-10 relative before:absolute before:left-[17px] before:top-2 before:bottom-2 before:w-px before:bg-white/5">
                    @foreach($enrollments->take(3) as $enrollment)
                        <div class="relative pl-12">
                            <div class="absolute left-0 top-0 w-9 h-9 rounded-2xl bg-dark-surface border border-white/5 flex items-center justify-center z-10 group">
                                <div class="w-2 h-2 rounded-full {{ $enrollment->completed_at ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 'bg-brand-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]' }}"></div>
                            </div>
                            <div>
                                <p class="text-xs font-black text-white uppercase tracking-widest mb-1">{{ $enrollment->completed_at ? 'Course Finished' : 'Continued Learning' }}</p>
                                <p class="text-sm text-slate-400 font-medium line-clamp-1 mb-2">{{ $enrollment->course->title }}</p>
                                <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">{{ $enrollment->updated_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Your Courses Grid --}}
        <div>
            <div class="flex items-center justify-between mb-10">
                <h3 class="text-2xl font-display font-black text-white">Your Workspace</h3>
                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $enrollments->count() }} Active Enrollments</span>
            </div>
            
            @if($enrollments->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($enrollments as $enrollment)
                        <a href="{{ route('courses.show', $enrollment->course) }}" class="bg-dark-card border border-white/5 rounded-[2.5rem] p-8 hover:border-brand-500/50 transition-all duration-500 group relative">
                            <div class="flex gap-6 items-start mb-8">
                                <div class="w-20 h-20 rounded-2xl overflow-hidden flex-shrink-0 border border-white/5">
                                    <img src="{{ $enrollment->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($enrollment->course->title).'&background=121217&color=fff&size=200' }}" 
                                         class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-80">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-white text-lg line-clamp-2 leading-tight mb-2 group-hover:text-brand-500 transition-colors">{{ $enrollment->course->title }}</h4>
                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $enrollment->course->instructor->name }}</p>
                                </div>
                            </div>
                            
                            <div class="pt-6 border-t border-white/5">
                                <div class="flex justify-between items-center mb-3">
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Progress</span>
                                    <span class="text-xs font-black text-white">{{ $enrollment->progress_percent ?? 0 }}%</span>
                                </div>
                                <div class="h-1.5 bg-white/5 rounded-full overflow-hidden">
                                    <div class="h-full bg-brand-500 rounded-full transition-all duration-1000" style="width: {{ $enrollment->progress_percent ?? 0 }}%"></div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-12">
                    {{ $enrollments->links() }}
                </div>
            @else
                <div class="bg-dark-card border border-white/5 rounded-[3rem] p-20 text-center">
                    <h4 class="text-2xl font-display font-black text-white mb-4">Your Library is Empty</h4>
                    <p class="text-slate-500 font-medium mb-10">Start your first course today and build your professional portfolio.</p>
                    <a href="{{ route('courses.index') }}" class="btn-primary">Browse Catalog</a>
                </div>
            @endif
        </div>

    </div>
</x-app-layout>
