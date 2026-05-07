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

    <div class="min-h-screen bg-[#0f172a] text-white">

        <div class="max-w-[1500px] mx-auto px-6 py-8">

            {{-- Welcome Header --}}
            <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8 mb-10">

                <div>

                    <h1 class="text-4xl lg:text-5xl font-semibold tracking-tight leading-tight">
                        {{ $greeting }},
                        <span class="text-violet-400">
                            {{ explode(' ', auth()->user()->name)[0] }}
                        </span>
                    </h1>

                    <div class="flex items-center gap-4 mt-4 text-slate-400">
                        <p class="text-base">{{ $todayDate }}</p>
                        <span class="text-2xl">🔥 {{ $stats['streak'] }}-day streak</span>
                    </div>

                    <p class="text-slate-400 mt-4 text-base max-w-2xl">
                        {{ $quote }}
                    </p>

                </div>

                <div class="flex items-center gap-3">

                    <a
                        href="{{ route('courses.index') }}"
                        class="h-11 px-5 rounded-xl bg-violet-600 hover:bg-violet-500 transition flex items-center justify-center text-sm font-medium"
                    >
                        Explore Courses
                    </a>

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

                        <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-transparent min-h-[420px]">

                            {{-- Background --}}
                            <img
                                src="{{ $jumpBackIn->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($jumpBackIn->course->title).'&background=111827&color=fff&size=1200' }}"
                                class="absolute inset-0 w-full h-full object-cover opacity-30"
                            >

                            <div class="absolute inset-0 bg-gradient-to-t from-[#0f172a] via-[#0f172a]/70 to-transparent"></div>

                            {{-- Content --}}
                            <div class="relative z-10 h-full flex flex-col justify-end p-8 lg:p-10">

                                <div class="max-w-2xl">

                                    <div class="inline-flex items-center px-3 py-1 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-xs font-medium mb-6">
                                        Continue Learning
                                    </div>

                                    <h2 class="text-4xl font-semibold tracking-tight leading-tight mb-4">
                                        {{ $jumpBackIn->course->title }}
                                    </h2>

                                    <p class="text-slate-300 mb-8 text-base leading-relaxed">
                                        {{ $nextLesson ? 'Up next: ' . $nextLesson->title : 'You’ve completed this course. Review lessons anytime.' }}
                                    </p>

                                    <div class="flex flex-col sm:flex-row sm:items-center gap-6">

                                        <a
                                            href="{{ $nextLesson ? route('lessons.show', [$jumpBackIn->course, $nextLesson]) : route('courses.show', $jumpBackIn->course) }}"
                                            class="h-12 px-6 rounded-xl bg-white text-slate-900 hover:bg-slate-100 transition flex items-center gap-2 text-sm font-medium w-fit"
                                        >
                                            ▶ Continue
                                        </a>

                                        <div class="flex-1 max-w-md">

                                            <div class="flex items-center justify-between mb-2">

                                                <span class="text-sm text-slate-400">
                                                    Progress
                                                </span>

                                                <span class="text-sm font-medium">
                                                    {{ $jumpBackIn->progress_percent }}%
                                                </span>

                                            </div>

                                            <div class="h-2 rounded-full bg-white/10 overflow-hidden">

                                                <div
                                                    class="h-full bg-violet-500 rounded-full transition-all duration-700"
                                                    style="width: {{ $jumpBackIn->progress_percent }}%"
                                                ></div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="rounded-3xl border border-white/10 bg-transparent min-h-[420px] flex flex-col items-center justify-center text-center p-10">

                            <div class="w-20 h-20 rounded-3xl bg-white/5 border border-white/10 flex items-center justify-center mb-8 text-4xl">
                                📚
                            </div>

                            <h2 class="text-3xl font-semibold mb-4">
                                Start Learning
                            </h2>

                            <p class="text-slate-400 max-w-lg leading-relaxed mb-8">
                                Explore courses and begin building skills through hands-on learning.
                            </p>

                            <a
                                href="{{ route('courses.index') }}"
                                class="h-11 px-5 rounded-xl bg-violet-600 hover:bg-violet-500 transition flex items-center justify-center text-sm font-medium"
                            >
                                Browse Courses
                            </a>

                        </div>

                    @endif

                </div>

                {{-- Side Stats --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- Completion Card --}}
                    <div class="rounded-3xl border border-white/10 bg-transparent p-6">

                        <div class="flex items-center justify-between">

                            <div>

                                <p class="text-sm text-slate-400 mb-3">
                                    Course Completion
                                </p>

                                <div class="flex items-end gap-2">

                                    <h3 class="text-5xl font-semibold tracking-tight">
                                        {{ $stats['completedCourses'] }}
                                    </h3>

                                    <span class="text-slate-500 mb-2">
                                        / {{ $stats['enrolledCourses'] }}
                                    </span>

                                </div>

                            </div>

                            <div class="relative w-24 h-24">

                                @php
                                    $percentage = $stats['enrolledCourses'] > 0
                                        ? round(($stats['completedCourses'] / $stats['enrolledCourses']) * 100)
                                        : 0;

                                    $circumference = 2 * pi() * 36;

                                    $offset = $circumference - ($percentage / 100) * $circumference;
                                @endphp

                                <svg
                                    class="w-full h-full -rotate-90"
                                    viewBox="0 0 80 80"
                                >

                                    <circle
                                        cx="40"
                                        cy="40"
                                        r="36"
                                        fill="transparent"
                                        stroke="rgba(255,255,255,0.08)"
                                        stroke-width="7"
                                    ></circle>

                                    <circle
                                        cx="40"
                                        cy="40"
                                        r="36"
                                        fill="transparent"
                                        stroke="#8b5cf6"
                                        stroke-width="7"
                                        stroke-dasharray="{{ $circumference }}"
                                        stroke-dashoffset="{{ $offset }}"
                                        stroke-linecap="round"
                                    ></circle>

                                </svg>

                                <div class="absolute inset-0 flex items-center justify-center">

                                    <span class="text-sm font-medium">
                                        {{ $percentage }}%
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Streak --}}
                    <div class="rounded-3xl border border-white/10 bg-transparent p-6">

                        <p class="text-sm text-slate-400 mb-4">This Month</p>

                        <div class="space-y-4">

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">Learning Hours</span>
                                    <span class="text-lg font-bold text-violet-400">{{ round($stats['learningHours'], 1) }}h</span>
                                </div>
                                <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                    <div class="h-full bg-blue-500 rounded-full" style="width: {{ min(round(($stats['learningHours'] / 100) * 100), 100) }}%"></div>
                                </div>
                            </div>

                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-medium">Streak</span>
                                    <span class="text-lg font-bold text-orange-400">{{ $stats['streak'] }} days 🔥</span>
                                </div>
                                <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                    <div class="h-full bg-orange-500 rounded-full" style="width: {{ min(($stats['streak'] / 30) * 100, 100) }}%"></div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>

            </div>

            {{-- Progress Overview & Live Sessions --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

                {{-- Weekly Activity Chart --}}
                <div class="lg:col-span-8 rounded-3xl border border-white/10 bg-transparent p-8">

                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold">Weekly Progress</h3>
                        <p class="text-sm text-slate-400 mt-1">Lessons completed per day</p>
                    </div>

                    <div class="flex items-end justify-between gap-3 h-48">

                        @foreach($weeklyActivity as $day)
                            <div class="flex-1 flex flex-col items-center gap-2">
                                <div class="w-full max-w-12 bg-gradient-to-t from-violet-500 to-violet-400 rounded-t-lg transition-all hover:from-violet-600 hover:to-violet-500"
                                    style="height: {{ $day['lessons'] > 0 ? ($day['lessons'] * 40) : 8 }}px">
                                </div>
                                <span class="text-xs text-slate-400">{{ $day['day'] }}</span>
                            </div>
                        @endforeach

                    </div>

                </div>

                {{-- Upcoming Live Sessions --}}
                <div class="lg:col-span-4 rounded-3xl border border-white/10 bg-transparent p-8">

                    <div class="mb-8">

                        <h3 class="text-2xl font-semibold">Live Sessions</h3>

                        <p class="text-sm text-slate-400 mt-1">Join your upcoming classes</p>

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

                    <div class="space-y-3">

                        @forelse($upcomingSessions as $session)

                            <div class="p-4 rounded-2xl border border-white/5 bg-white/[0.03] hover:bg-white/[0.05] transition">

                                <div class="flex items-start justify-between gap-3 mb-2">

                                    @if($session->status === 'live')
                                        <span class="px-2 py-1 rounded-full bg-red-500 text-white text-xs font-medium">Live</span>
                                    @else
                                        <span class="text-xs text-violet-400 font-medium">
                                            {{ $session->scheduled_at->format('M d • h:i A') }}
                                        </span>
                                    @endif

                                </div>

                                <h4 class="text-sm font-medium mb-1 line-clamp-1">
                                    {{ $session->topic ?? $session->title }}
                                </h4>

                                <p class="text-xs text-slate-400 mb-3 line-clamp-1">
                                    {{ $session->course->title }}
                                </p>

                                <a
                                    href="{{ route('live-sessions.show', $session) }}"
                                    class="text-xs text-violet-400 hover:text-violet-300 font-medium transition"
                                >
                                    Join →
                                </a>

                            </div>

                        @empty

                            <div class="h-32 rounded-2xl border border-dashed border-white/10 flex items-center justify-center text-slate-500 text-sm text-center p-4">
                                No upcoming live sessions
                            </div>

                        @endforelse

                    </div>

                </div>

            </div>

            {{-- Achievements & Certificates --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">

                {{-- Achievements/Badges --}}
                <div class="lg:col-span-6 rounded-3xl border border-white/10 bg-transparent p-8">

                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold">Achievements</h3>
                        <p class="text-sm text-slate-400 mt-1">Badges you've earned on your learning journey</p>
                    </div>

                    @if($badges->count() > 0)

                        <div class="grid grid-cols-3 gap-4">

                            @foreach($badges as $userBadge)

                                <div class="flex flex-col items-center text-center p-4 rounded-2xl border border-white/5 bg-white/[0.03] hover:bg-white/[0.05] transition">

                                    <div class="text-4xl mb-2">
                                        {{ $userBadge->badge->icon_url ?? '🏆' }}
                                    </div>

                                    <h4 class="text-xs font-bold mb-1">{{ $userBadge->badge->name }}</h4>

                                    <p class="text-[10px] text-slate-400 line-clamp-2">
                                        {{ $userBadge->badge->description }}
                                    </p>

                                    <p class="text-[10px] text-violet-400 mt-2">
                                        {{ $userBadge->earned_at->format('M d') }}
                                    </p>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-12">
                            <div class="text-4xl mb-4">🔓</div>
                            <p class="text-slate-400 text-sm">Complete courses, build streaks, and earn badges!</p>
                        </div>

                    @endif

                </div>

                {{-- Recent Certificates --}}
                <div class="lg:col-span-6 rounded-3xl border border-white/10 bg-transparent p-8">

                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold">Certificates</h3>
                        <p class="text-sm text-slate-400 mt-1">Your earned certificates</p>
                    </div>

                    @if($certificates->count() > 0)

                        <div class="space-y-4">

                            @foreach($certificates as $cert)

                                <div class="p-4 rounded-2xl border border-white/5 bg-white/[0.03] hover:bg-white/[0.05] transition flex items-center justify-between gap-4">

                                    <div class="flex items-center gap-4 flex-1 min-w-0">

                                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-lg flex-shrink-0">
                                            📜
                                        </div>

                                        <div class="min-w-0">
                                            <h4 class="font-medium line-clamp-1">{{ $cert->course->title }}</h4>
                                            <p class="text-xs text-slate-400">{{ $cert->issued_at->format('M d, Y') }}</p>
                                        </div>

                                    </div>

                                    <a
                                        href="{{ route('certificates.download', $cert) }}"
                                        class="px-3 py-2 rounded-lg bg-violet-600 hover:bg-violet-500 text-white text-xs font-medium transition flex-shrink-0"
                                    >
                                        Download
                                    </a>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="text-center py-12">
                            <div class="text-4xl mb-4">📜</div>
                            <p class="text-slate-400 text-sm">Complete courses to earn certificates</p>
                        </div>

                    @endif

                </div>

            </div>

            {{-- Upcoming Deadlines --}}
            @if($upcomingDeadlines->count() > 0)

                <div class="rounded-3xl border border-white/10 bg-transparent p-8 mb-8">

                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold">Upcoming Deadlines</h3>
                        <p class="text-sm text-slate-400 mt-1">Stay on top of your commitments</p>
                    </div>

                    <div class="space-y-3">

                        @foreach($upcomingDeadlines as $deadline)

                            <div class="p-4 rounded-2xl border border-white/5 bg-white/[0.03] hover:bg-white/[0.05] transition">

                                <div class="flex items-start justify-between gap-4">

                                    <div>

                                        <div class="flex items-center gap-2 mb-1">

                                            @if($deadline['status'] === 'live')
                                                <span class="px-2 py-1 rounded-full bg-red-500 text-white text-xs font-bold">LIVE NOW</span>
                                            @else
                                                <span class="text-xs text-amber-400 font-medium">
                                                    {{ $deadline['date']->diffForHumans() }}
                                                </span>
                                            @endif

                                        </div>

                                        <h4 class="font-medium">{{ $deadline['title'] }}</h4>

                                        <p class="text-sm text-slate-400">{{ $deadline['course'] }}</p>

                                    </div>

                                    @if($deadline['type'] === 'live_session')
                                        <a
                                            href="{{ route('live-sessions.show', $deadline['id']) }}"
                                            class="px-4 py-2 rounded-lg bg-violet-600 hover:bg-violet-500 text-white text-sm font-medium transition flex-shrink-0"
                                        >
                                            Join
                                        </a>
                                    @endif

                                </div>

                            </div>

                        @endforeach

                    </div>

                </div>

            @endif

            {{-- My Courses Section --}}
            <div>

                <div class="flex items-center justify-between mb-8">

                    <div>
                        <h3 class="text-2xl font-semibold">Your Courses</h3>
                        <p class="text-sm text-slate-400 mt-1">Continue learning from where you left off</p>
                    </div>

                    <span class="text-sm text-slate-500">
                        {{ $enrollments->total() }} enrolled
                    </span>

                </div>

                {{-- Filter Tabs --}}
                @if($enrollments->total() > 0)

                    <div x-data="{ activeFilter: 'all' }" class="mb-8">

                        <div class="flex gap-2 border-b border-white/10 pb-4">

                            <button
                                @click="activeFilter = 'all'"
                                :class="{ 'text-violet-400 border-b-2 border-violet-500': activeFilter === 'all', 'text-slate-400': activeFilter !== 'all' }"
                                class="px-4 py-2 text-sm font-medium transition border-b-2 border-transparent"
                            >
                                All ({{ $enrollments->total() }})
                            </button>

                            <button
                                @click="activeFilter = 'inprogress'"
                                :class="{ 'text-violet-400 border-b-2 border-violet-500': activeFilter === 'inprogress', 'text-slate-400': activeFilter !== 'inprogress' }"
                                class="px-4 py-2 text-sm font-medium transition border-b-2 border-transparent"
                            >
                                In Progress ({{ $enrollmentsInProgress->count() }})
                            </button>

                            <button
                                @click="activeFilter = 'completed'"
                                :class="{ 'text-violet-400 border-b-2 border-violet-500': activeFilter === 'completed', 'text-slate-400': activeFilter !== 'completed' }"
                                class="px-4 py-2 text-sm font-medium transition border-b-2 border-transparent"
                            >
                                Completed ({{ $enrollmentsCompleted->count() }})
                            </button>

                        </div>

                        {{-- All Courses --}}
                        <div x-show="activeFilter === 'all'" class="mt-8">

                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                                @foreach($enrollments as $enrollment)

                                    <a
                                        href="{{ route('courses.show', $enrollment->course) }}"
                                        class="rounded-3xl border border-white/10 bg-transparent overflow-hidden hover:border-violet-500/30 transition group"
                                    >

                                        <div class="aspect-[16/9] overflow-hidden relative">

                                            <img
                                                src="{{ $enrollment->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($enrollment->course->title).'&background=111827&color=fff&size=800' }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                            >

                                            @if($enrollment->completed_at)
                                                <div class="absolute inset-0 bg-emerald-500/20 flex items-center justify-center">
                                                    <span class="text-3xl">✓</span>
                                                </div>
                                            @endif

                                        </div>

                                        <div class="p-6">

                                            <h4 class="text-xl font-medium leading-tight mb-3 group-hover:text-violet-400 transition line-clamp-2">
                                                {{ $enrollment->course->title }}
                                            </h4>

                                            <p class="text-sm text-slate-400 mb-4">
                                                {{ $enrollment->course->instructor->name }}
                                            </p>

                                            <div class="mb-4">

                                                <div class="flex items-center justify-between mb-2">
                                                    <span class="text-sm text-slate-400">Progress</span>
                                                    <span class="text-sm font-medium">{{ $enrollment->progress_percent ?? 0 }}%</span>
                                                </div>

                                                <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                                    <div
                                                        class="h-full bg-violet-500 rounded-full transition-all duration-700"
                                                        style="width: {{ $enrollment->progress_percent ?? 0 }}%"
                                                    ></div>
                                                </div>

                                            </div>

                                            <div class="flex items-center justify-between text-xs text-slate-400">
                                                <span>{{ $enrollment->updated_at->diffForHumans() }}</span>
                                                @if($enrollment->completed_at)
                                                    <span class="text-emerald-400">Completed</span>
                                                @endif
                                            </div>

                                        </div>

                                    </a>

                                @endforeach

                            </div>

                        </div>

                        {{-- In Progress --}}
                        <div x-show="activeFilter === 'inprogress'" class="mt-8">

                            @if($enrollmentsInProgress->count() > 0)

                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                                    @foreach($enrollmentsInProgress as $enrollment)

                                        <a
                                            href="{{ route('courses.show', $enrollment->course) }}"
                                            class="rounded-3xl border border-white/10 bg-transparent overflow-hidden hover:border-violet-500/30 transition group"
                                        >

                                            <div class="aspect-[16/9] overflow-hidden">
                                                <img
                                                    src="{{ $enrollment->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($enrollment->course->title).'&background=111827&color=fff&size=800' }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                                >
                                            </div>

                                            <div class="p-6">

                                                <h4 class="text-xl font-medium leading-tight mb-3 group-hover:text-violet-400 transition line-clamp-2">
                                                    {{ $enrollment->course->title }}
                                                </h4>

                                                <p class="text-sm text-slate-400 mb-4">
                                                    {{ $enrollment->course->instructor->name }}
                                                </p>

                                                <div class="mb-4">

                                                    <div class="flex items-center justify-between mb-2">
                                                        <span class="text-sm text-slate-400">Progress</span>
                                                        <span class="text-sm font-medium">{{ $enrollment->progress_percent ?? 0 }}%</span>
                                                    </div>

                                                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                                        <div
                                                            class="h-full bg-violet-500 rounded-full transition-all duration-700"
                                                            style="width: {{ $enrollment->progress_percent ?? 0 }}%"
                                                        ></div>
                                                    </div>

                                                </div>

                                                <span class="text-xs text-slate-400">
                                                    Last accessed {{ $enrollment->updated_at->diffForHumans() }}
                                                </span>

                                            </div>

                                        </a>

                                    @endforeach

                                </div>

                            @else

                                <div class="text-center py-12">
                                    <p class="text-slate-400">No courses in progress</p>
                                </div>

                            @endif

                        </div>

                        {{-- Completed --}}
                        <div x-show="activeFilter === 'completed'" class="mt-8">

                            @if($enrollmentsCompleted->count() > 0)

                                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                                    @foreach($enrollmentsCompleted as $enrollment)

                                        <a
                                            href="{{ route('courses.show', $enrollment->course) }}"
                                            class="rounded-3xl border border-white/10 bg-transparent overflow-hidden hover:border-violet-500/30 transition group"
                                        >

                                            <div class="aspect-[16/9] overflow-hidden relative">
                                                <img
                                                    src="{{ $enrollment->course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($enrollment->course->title).'&background=111827&color=fff&size=800' }}"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                                >
                                                <div class="absolute inset-0 bg-emerald-500/20 flex items-center justify-center">
                                                    <span class="text-3xl">✓</span>
                                                </div>
                                            </div>

                                            <div class="p-6">

                                                <h4 class="text-xl font-medium leading-tight mb-3 group-hover:text-violet-400 transition line-clamp-2">
                                                    {{ $enrollment->course->title }}
                                                </h4>

                                                <p class="text-sm text-slate-400 mb-4">
                                                    {{ $enrollment->course->instructor->name }}
                                                </p>

                                                <div class="mb-4">
                                                    <div class="h-2 rounded-full bg-white/10 overflow-hidden">
                                                        <div class="h-full bg-emerald-500 rounded-full w-full"></div>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-slate-400">Completed</span>
                                                    <span class="text-emerald-400 font-medium">100%</span>
                                                </div>

                                            </div>

                                        </a>

                                    @endforeach

                                </div>

                            @else

                                <div class="text-center py-12">
                                    <p class="text-slate-400">No completed courses yet</p>
                                </div>

                            @endif

                        </div>

                    </div>

                    <div class="mt-10">
                        {{ $enrollments->links() }}
                    </div>

                @else

                    <div class="rounded-3xl border border-white/10 bg-transparent p-20 text-center">

                        <h3 class="text-3xl font-semibold mb-4">
                            No courses yet
                        </h3>

                        <p class="text-slate-400 mb-8 max-w-xl mx-auto">
                            Start exploring courses and build your learning path.
                        </p>

                        <a
                            href="{{ route('courses.index') }}"
                            class="h-11 px-5 rounded-xl bg-violet-600 hover:bg-violet-500 transition inline-flex items-center justify-center text-sm font-medium"
                        >
                            Explore Courses
                        </a>

                    </div>

                @endif

            </div>

            {{-- Recommended For You --}}
            @if($recommendations->count() > 0)

                <div class="mt-12">

                    <div class="mb-8">
                        <h3 class="text-2xl font-semibold">Recommended For You</h3>
                        <p class="text-sm text-slate-400 mt-1">Courses tailored to your interests</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">

                        @foreach($recommendations as $course)

                            <a
                                href="{{ route('courses.show', $course) }}"
                                class="rounded-3xl border border-white/10 bg-transparent overflow-hidden hover:border-violet-500/30 transition group"
                            >

                                <div class="aspect-[16/9] overflow-hidden">
                                    <img
                                        src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=111827&color=fff&size=800' }}"
                                        class="w-full h-full object-cover group-hover:scale-105 transition duration-700"
                                    >
                                </div>

                                <div class="p-6">

                                    @if($course->category)
                                        <div class="inline-block px-2 py-1 rounded-full bg-violet-500/10 border border-violet-500/20 text-violet-300 text-[10px] font-bold mb-3">
                                            {{ $course->category->name }}
                                        </div>
                                    @endif

                                    <h4 class="text-xl font-medium leading-tight mb-3 group-hover:text-violet-400 transition line-clamp-2">
                                        {{ $course->title }}
                                    </h4>

                                    <p class="text-sm text-slate-400">
                                        {{ $course->instructor->name }}
                                    </p>

                                </div>

                            </a>

                        @endforeach

                    </div>

                </div>

            @endif

        </div>

    </div>

</x-app-layout>