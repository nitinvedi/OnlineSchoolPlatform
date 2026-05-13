<x-app-layout>

    @php
        $hour = date('H');
        $greeting = 'Good evening';
        if ($hour < 12) {
            $greeting = 'Good morning';
        } elseif ($hour < 17) {
            $greeting = 'Good afternoon';
        }

        $quotes = [
            "Learning compounds over time.",
            "Small progress every day adds up.",
            "Consistency creates mastery.",
            "Focus on progress, not perfection."
        ];
        $quote = $quotes[array_rand($quotes)];
        
        $todayDate = now()->format('l, F j, Y');
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 text-[#0F172A]">

        <div class="max-w-[1500px] mx-auto px-4 sm:px-6 py-6 sm:py-10">

            {{-- Welcome Header --}}
            <div class="relative overflow-hidden rounded-3xl mb-10 sm:mb-12">
                <!-- Gradient Background -->
                <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-500 to-brand-700"></div>
                <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
                
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-96 h-96 bg-brand-400/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-brand-300/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>

                <div class="relative z-10 flex flex-col lg:flex-row lg:items-end justify-between gap-8 p-8 sm:p-10 lg:p-12">

                    <div>

                        <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 bg-white/20 backdrop-blur-md rounded-full text-xs font-semibold text-white/80 border border-white/20">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1120 0 10 10 0 01-20 0z"/></svg>
                            <span>Learning Dashboard</span>
                        </div>

                        <h1 class="text-4xl lg:text-6xl font-display font-black tracking-tight leading-tight text-white">
                            {{ $greeting }}, <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-white to-white/80">{{ explode(' ', auth()->user()->name)[0] }}</span>
                        </h1>

                        <div class="flex flex-wrap items-center gap-4 mt-6 text-white/90">
                            <p class="text-sm font-medium flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ $todayDate }}
                            </p>
                            <span class="w-1 h-1 rounded-full bg-white/40"></span>
                            <div class="inline-flex items-center gap-2 px-3 py-1 bg-white/20 backdrop-blur rounded-full text-xs font-semibold border border-white/30">
                                <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2s4 3 4 7-2 6-4 8-4-3-4-7 2-8 4-8z"/></svg>
                                <span>{{ $stats['streak'] }}-day streak</span>
                            </div>
                        </div>

                        <p class="text-lg text-white/90 mt-6 font-medium leading-relaxed max-w-2xl">
                            {{ $quote }}
                        </p>

                    </div>

                    <div class="flex items-center gap-3">

                        <a
                            href="{{ route('courses.index') }}"
                            class="h-12 px-6 rounded-xl bg-white text-brand-600 hover:bg-white/90 transition-all duration-200 flex items-center justify-center text-sm font-bold shadow-lg shadow-brand-900/20 hover:shadow-2xl hover:shadow-brand-900/30 hover:scale-105"
                        >
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            Explore Catalog
                        </a>

                    </div>

                </div>

            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

                {{-- Continue Learning --}}
                <div class="lg:col-span-8">

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

                        <div class="relative overflow-hidden rounded-2xl min-h-[420px] group">

                            {{-- Background Image --}}
                            <img
                                src="{{ $jumpBackIn->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($jumpBackIn->course->title).'&background=111827&color=fff&size=1200' }}"
                                class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >

                            {{-- Overlay Gradient --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-slate-900/80 via-slate-900/50 to-slate-900/70"></div>

                            {{-- Content --}}
                            <div class="relative z-10 h-full flex flex-col justify-between p-8 lg:p-10">

                                <div class="flex items-start justify-between">
                                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/10 backdrop-blur rounded-full text-xs font-semibold text-white/80 border border-white/20">
                                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        <span>In Progress</span>
                                    </div>
                                    <div class="w-12 h-12 rounded-xl bg-white/10 backdrop-blur border border-white/20 flex items-center justify-center text-white/80 group-hover:text-white transition-colors">
                                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                    </div>
                                </div>

                                <div class="max-w-2xl">

                                    <h2 class="text-3xl lg:text-4xl font-display font-black tracking-tight leading-tight mb-3 text-white">
                                        {{ $jumpBackIn->course->title }}
                                    </h2>

                                    <p class="text-white/80 mb-8 text-base leading-relaxed">
                                        {{ $nextLesson ? 'Next: ' . $nextLesson->title : 'You've completed this course!' }}
                                    </p>

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-6">

                                        <a
                                            href="{{ $nextLesson ? route('lessons.show', [$jumpBackIn->course, $nextLesson]) : route('courses.show', $jumpBackIn->course) }}"
                                            class="h-12 px-6 rounded-xl bg-white text-slate-900 hover:bg-white/90 transition-all duration-200 flex items-center gap-2 text-sm font-bold shadow-lg shadow-black/30 hover:shadow-xl w-fit hover:scale-105"
                                        >
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M8 5v14l11-7z"/></svg>
                                            {{ $nextLesson ? 'Continue Learning' : 'Review Course' }}
                                        </a>

                                        <div class="flex-1 max-w-md">
                                            <div class="flex items-center justify-between mb-3">
                                                <span class="text-xs text-white/70 font-semibold uppercase tracking-wide">Progress</span>
                                                <span class="text-sm font-bold text-white">{{ $jumpBackIn->progress_percent }}%</span>
                                            </div>
                                            <div class="h-2.5 rounded-full bg-white/20 border border-white/30 overflow-hidden">
                                                <div
                                                    class="h-full bg-gradient-to-r from-emerald-400 to-emerald-500 rounded-full transition-all duration-700"
                                                    style="width: {{ $jumpBackIn->progress_percent }}%"
                                                ></div>
                                            </div>
                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="relative overflow-hidden rounded-2xl min-h-[420px] flex flex-col items-center justify-center text-center p-10 bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 hover:border-brand-300 transition-colors">

                            <div class="absolute inset-0 opacity-5">
                                <div class="absolute top-0 right-0 w-96 h-96 bg-brand-500 rounded-full blur-3xl"></div>
                                <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500 rounded-full blur-3xl"></div>
                            </div>

                            <div class="relative z-10">
                                <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-brand-100 to-brand-50 border border-brand-200 flex items-center justify-center mb-8 mx-auto shadow-sm">
                                    <svg class="w-12 h-12 text-brand-600" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 2H9a2 2 0 00-2 2v14a2 2 0 002 2h10V2zM7 6H5a2 2 0 00-2 2v12a2 2 0 002 2h2V6z"/>
                                    </svg>
                                </div>

                                <h2 class="text-3xl font-display font-black mb-3 text-slate-900">
                                    Start Your Learning Journey
                                </h2>

                                <p class="text-slate-600 max-w-lg leading-relaxed mb-8 text-base">
                                    Explore our handpicked collection of courses from expert instructors and start learning today.
                                </p>

                                <a
                                    href="{{ route('courses.index') }}"
                                    class="inline-flex items-center h-12 px-6 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 transition-all duration-200 text-sm font-bold text-white shadow-lg shadow-brand-500/20 hover:shadow-xl hover:shadow-brand-500/30 hover:scale-105"
                                >
                                    <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                    Browse Courses
                                </a>
                            </div>

                        </div>

                    @endif

                </div>

                {{-- Side Stats --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- Completion Card --}}
                    <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">

                        <div class="mb-6">
                            <p class="text-xs text-gray-600 font-semibold uppercase tracking-wide mb-2">
                                📚 Course Completion
                            </p>
                            <div class="flex items-end gap-3">
                                <h3 class="text-5xl font-black tracking-tight text-gray-900">
                                    {{ $stats['completedCourses'] }}
                                </h3>
                                <span class="text-gray-600 mb-2 text-sm font-medium">
                                    out of {{ $stats['enrolledCourses'] }}
                                </span>
                            </div>
                        </div>

                        <div class="h-2 rounded-full bg-slate-100 overflow-hidden mb-6">
                            @php
                                $percentage = $stats['enrolledCourses'] > 0
                                    ? round(($stats['completedCourses'] / $stats['enrolledCourses']) * 100)
                                    : 0;
                            @endphp
                            <div
                                class="h-full bg-gradient-to-r from-blue-500 to-blue-600 transition-all duration-700"
                                style="width: {{ $percentage }}%"
                            ></div>
                        </div>

                        <div class="flex items-center justify-between text-xs">
                            <span class="text-gray-600 font-medium">{{ $percentage }}% Complete</span>
                            <span class="text-gray-400">{{ $stats['enrolledCourses'] - $stats['completedCourses'] }} remaining</span>
                        </div>

                    </div>

                    {{-- Stats Grid --}}
                    <div class="grid grid-cols-2 gap-4">

                        {{-- Hours Learned --}}
                        <div class="rounded-2xl bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 p-5 hover:shadow-md transition-shadow">
                            <p class="text-xs text-purple-700 font-semibold uppercase tracking-wide mb-3">⏱️ Hours Learned</p>
                            <h3 class="text-3xl font-black text-purple-900">{{ $stats['hoursLearned'] ?? 0 }}</h3>
                            <p class="text-xs text-purple-600 mt-2">{{ $stats['hoursThisWeek'] ?? 0 }} this week</p>
                        </div>

                        {{-- Lessons Done --}}
                        <div class="rounded-2xl bg-gradient-to-br from-emerald-50 to-emerald-100 border border-emerald-200 p-5 hover:shadow-md transition-shadow">
                            <p class="text-xs text-emerald-700 font-semibold uppercase tracking-wide mb-3">✓ Lessons Done</p>
                            <h3 class="text-3xl font-black text-emerald-900">{{ $stats['completedLessons'] ?? 0 }}</h3>
                            <p class="text-xs text-emerald-600 mt-2">Solid progress!</p>
                        </div>

                    </div>

                    {{-- Streak --}}
                    <div class="rounded-2xl bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-xs text-orange-700 font-semibold uppercase tracking-wide">🔥 Learning Streak</p>
                            <span class="text-2xl">{{ $stats['streak'] }}</span>
                        </div>
                        <p class="text-sm text-orange-800 font-medium mb-3">{{ $stats['streak'] }} days in a row!</p>
                        <div class="w-full h-2 rounded-full bg-orange-200 overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-orange-400 to-orange-500" style="width: {{ min(($stats['streak'] / 30) * 100, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-orange-600 mt-3">Keep it up! 30-day goal!</p>
                    </div>

                </div>

            </div>

            {{-- Bottom Section - Recent Activity --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Active Courses --}}
                <div class="lg:col-span-2 rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-black text-slate-900">📖 Active Courses</h3>
                        <a href="{{ route('courses.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">View All →</a>
                    </div>

                    <div class="space-y-3">
                        @forelse($jumpBackIn ? [$jumpBackIn] : [])
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-brand-300 hover:bg-brand-50/30 transition-all">
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-brand-500 to-brand-600 flex items-center justify-center text-white font-black text-lg">
                                        {{ substr($jumpBackIn->course->title ?? 'Course', 0, 1) }}
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-slate-900">{{ $jumpBackIn->course->title ?? 'Course' }}</p>
                                        <p class="text-xs text-slate-600">{{ $jumpBackIn->progress_percent }}% complete</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="w-12 h-1.5 rounded-full bg-slate-200 overflow-hidden">
                                        <div class="h-full bg-brand-600 transition-all" style="width: {{ $jumpBackIn->progress_percent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-center py-8 text-slate-500">No active courses. Start learning today!</p>
                        @endforelse
                    </div>
                </div>

                {{-- Recommendations --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">
                    <h3 class="text-lg font-black text-slate-900 mb-6">✨ Recommended</h3>
                    
                    <div class="space-y-3">
                        <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-50 to-indigo-100 border border-indigo-200 hover:border-indigo-300 transition-colors cursor-pointer">
                            <p class="font-bold text-indigo-900 text-sm mb-1">Master Web Development</p>
                            <p class="text-xs text-indigo-700">Start with HTML & CSS basics</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gradient-to-br from-pink-50 to-pink-100 border border-pink-200 hover:border-pink-300 transition-colors cursor-pointer">
                            <p class="font-bold text-pink-900 text-sm mb-1">Digital Marketing Pro</p>
                            <p class="text-xs text-pink-700">Learn from industry experts</p>
                        </div>
                        <div class="p-4 rounded-xl bg-gradient-to-br from-cyan-50 to-cyan-100 border border-cyan-200 hover:border-cyan-300 transition-colors cursor-pointer">
                            <p class="font-bold text-cyan-900 text-sm mb-1">Data Science Essentials</p>
                            <p class="text-xs text-cyan-700">Unlock data-driven decisions</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</x-app-layout>
