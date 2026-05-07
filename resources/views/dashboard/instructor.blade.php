<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="min-h-screen bg-[#0f172a] text-white">
        <div class="max-w-[1600px] mx-auto px-6 py-6">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">

                <div>
                    <h1 class="text-3xl font-semibold tracking-tight">
                        Good evening, {{ auth()->user()->name }} 👋
                    </h1>

                    <p class="text-slate-400 mt-2 text-sm">
                        Here's what's happening with your courses today.
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    <button class="h-11 px-5 rounded-xl bg-violet-600 hover:bg-violet-500 transition text-sm font-medium">
                        Create Course
                    </button>

                    <button class="h-11 px-5 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition text-sm font-medium">
                        Schedule Live
                    </button>

                </div>

            </div>

            {{-- Top Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

                {{-- Revenue Card --}}
                <div class="lg:col-span-8 bg-[#111827]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6">

                    <div class="flex items-center justify-between mb-8">

                        <div>
                            <h2 class="text-xl font-semibold">
                                Revenue Overview
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                Revenue and student growth over time
                            </p>
                        </div>

                        <select class="bg-white/5 border border-white/10 rounded-xl h-10 px-4 text-sm outline-none">
                            <option>Last 6 Months</option>
                            <option>Last Year</option>
                        </select>

                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-3 gap-4 mb-8">

                        <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                            <p class="text-sm text-slate-400 mb-2">
                                Revenue
                            </p>

                            <h3 class="text-2xl font-semibold">
                                ${{ number_format($stats['totalRevenue']) }}
                            </h3>
                        </div>

                        <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                            <p class="text-sm text-slate-400 mb-2">
                                Students
                            </p>

                            <h3 class="text-2xl font-semibold">
                                {{ number_format($stats['totalStudents']) }}
                            </h3>
                        </div>

                        <div class="bg-white/[0.03] border border-white/5 rounded-2xl p-5">
                            <p class="text-sm text-slate-400 mb-2">
                                Published Courses
                            </p>

                            <h3 class="text-2xl font-semibold">
                                {{ $stats['publishedCourses'] }}
                            </h3>
                        </div>

                    </div>

                    {{-- Chart --}}
                    <div class="h-[320px]">
                        <canvas id="revenueChart"></canvas>
                    </div>

                </div>

                {{-- Side Column --}}
                <div class="lg:col-span-4 space-y-6">

                    {{-- Tasks --}}
                    <div class="bg-[#111827]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6">

                        <div class="flex items-center justify-between mb-6">

                            <div>
                                <h2 class="text-xl font-semibold">
                                    Pending Tasks
                                </h2>

                                <p class="text-sm text-slate-400 mt-1">
                                    Things that need attention
                                </p>
                            </div>

                            <span class="w-2 h-2 rounded-full bg-orange-400"></span>

                        </div>

                        <div class="space-y-4">

                            @if($drafts->count() > 0)
                                <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">

                                    <div class="flex items-start justify-between gap-4">

                                        <div>
                                            <p class="font-medium mb-1">
                                                Course Drafts
                                            </p>

                                            <p class="text-sm text-slate-400">
                                                {{ $drafts->count() }} drafts are waiting to be published.
                                            </p>
                                        </div>

                                        <span class="text-xs text-orange-400 font-medium">
                                            Pending
                                        </span>

                                    </div>

                                </div>
                            @endif

                            <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/5">

                                <div class="flex items-start justify-between gap-4">

                                    <div>
                                        <p class="font-medium mb-1">
                                            Student Questions
                                        </p>

                                        <p class="text-sm text-slate-400">
                                            3 unanswered questions from students.
                                        </p>
                                    </div>

                                    <span class="text-xs text-violet-400 font-medium">
                                        New
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Top Course --}}
                    @if($topCourse)
                        <div class="bg-[#111827]/80 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden">

                            <img
                                src="{{ $topCourse->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($topCourse->title).'&background=111827&color=fff&size=800' }}"
                                class="h-52 w-full object-cover"
                            >

                            <div class="p-6">

                                <div class="inline-flex items-center px-3 py-1 rounded-full bg-violet-500/10 text-violet-400 text-xs font-medium mb-4">
                                    Top Performing Course
                                </div>

                                <h3 class="text-2xl font-semibold leading-tight mb-3">
                                    {{ $topCourse->title }}
                                </h3>

                                <div class="flex items-center gap-4 text-sm text-slate-400">

                                    <div class="flex items-center gap-2">
                                        <span>👨‍🎓</span>
                                        <span>{{ number_format($topCourse->student_count) }} students</span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    @endif

                </div>

            </div>

            {{-- Bottom Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

                {{-- Live Sessions --}}
                <div class="bg-[#111827]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6">

                    <div class="flex items-center justify-between mb-8">

                        <div>
                            <h2 class="text-xl font-semibold">
                                Live Sessions
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                Manage your upcoming sessions
                            </p>
                        </div>

                        <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center">
                            📹
                        </div>

                    </div>

                    @php
                        $liveSession = \App\Models\LiveSession::where('instructor_id', auth()->id())
                            ->whereIn('status', ['scheduled', 'live'])
                            ->orderBy('scheduled_at')
                            ->first();
                    @endphp

                    @if($liveSession)

                        <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/5 mb-6">

                            <p class="text-sm text-slate-400 mb-2">
                                Upcoming Session
                            </p>

                            <h3 class="font-medium text-lg mb-3">
                                {{ $liveSession->topic }}
                            </h3>

                            <div class="flex items-center gap-2 text-sm text-violet-400">
                                <span class="w-2 h-2 rounded-full bg-violet-400 animate-pulse"></span>
                                {{ $liveSession->scheduled_at->diffForHumans() }}
                            </div>

                        </div>

                        <a
                            href="{{ route('instructor.live-sessions.show', $liveSession) }}"
                            class="h-11 rounded-xl bg-violet-600 hover:bg-violet-500 transition flex items-center justify-center text-sm font-medium"
                        >
                            {{ $liveSession->status === 'live' ? 'Manage Session' : 'Open Session Room' }}
                        </a>

                    @else

                        <div class="flex flex-col h-full justify-between">

                            <p class="text-slate-400 text-sm mb-6">
                                You don't have any upcoming live sessions.
                            </p>

                            <a
                                href="{{ route('instructor.live-sessions.create') }}"
                                class="h-11 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 transition flex items-center justify-center text-sm font-medium"
                            >
                                Schedule Session
                            </a>

                        </div>

                    @endif

                </div>

                {{-- Reviews --}}
                <div class="bg-[#111827]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6">

                    <div class="flex items-center justify-between mb-8">

                        <div>
                            <h2 class="text-xl font-semibold">
                                Student Reviews
                            </h2>

                            <p class="text-sm text-slate-400 mt-1">
                                Recent feedback from students
                            </p>
                        </div>

                    </div>

                    <div class="space-y-4 max-h-[320px] overflow-y-auto pr-2">

                        @forelse($reviews as $review)

                            <div class="p-5 rounded-2xl bg-white/[0.03] border border-white/5">

                                <div class="flex items-center justify-between mb-3">

                                    <div>
                                        <p class="font-medium">
                                            {{ $review->user->name }}
                                        </p>
                                    </div>

                                    <div class="flex items-center gap-1 text-yellow-400">

                                        @for($i = 0; $i < $review->rating; $i++)
                                            ⭐
                                        @endfor

                                    </div>

                                </div>

                                <p class="text-sm text-slate-400 leading-relaxed">
                                    "{{ $review->review ?? 'Great course experience.' }}"
                                </p>

                            </div>

                        @empty

                            <div class="h-[200px] flex items-center justify-center text-slate-500 text-sm">
                                No reviews available.
                            </div>

                        @endforelse

                    </div>

                </div>

                {{-- Engagement --}}
                <div class="bg-[#111827]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-6">

                    <div class="mb-8">

                        <h2 class="text-xl font-semibold">
                            Student Engagement
                        </h2>

                        <p class="text-sm text-slate-400 mt-1">
                            Weekly learning activity
                        </p>

                    </div>

                    <div class="grid grid-cols-7 gap-2 mb-8">

                        @foreach(['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $day)

                            <div class="space-y-2 text-center">

                                @for($i=0; $i<5; $i++)

                                    @php
                                        $opacity = [10,20,40,60,80][rand(0,4)];
                                    @endphp

                                    <div
                                        class="w-full aspect-square rounded-md bg-violet-500/{{ $opacity }}">
                                    </div>

                                @endfor

                                <p class="text-xs text-slate-500 mt-2">
                                    {{ $day }}
                                </p>

                            </div>

                        @endforeach

                    </div>

                    <div class="pt-6 border-t border-white/5">

                        <div class="flex items-end gap-3 mb-3">

                            <h3 class="text-4xl font-semibold">
                                84%
                            </h3>

                            <p class="text-sm text-emerald-400 mb-1">
                                Engagement Rate
                            </p>

                        </div>

                        <div class="h-2 rounded-full bg-white/5 overflow-hidden">
                            <div class="h-full w-[84%] bg-violet-500 rounded-full"></div>
                        </div>

                    </div>

                </div>

            </div>

            {{-- Courses Table --}}
            <div class="bg-[#111827]/80 backdrop-blur-xl border border-white/10 rounded-3xl overflow-hidden">

                {{-- Table Header --}}
                <div class="flex items-center justify-between px-6 py-5 border-b border-white/5">

                    <div>
                        <h2 class="text-xl font-semibold">
                            Courses
                        </h2>

                        <p class="text-sm text-slate-400 mt-1">
                            Manage and monitor your courses
                        </p>
                    </div>

                    <a
                        href="{{ route('instructor.courses.index') }}"
                        class="text-sm text-violet-400 hover:text-violet-300 transition"
                    >
                        View All
                    </a>

                </div>

                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="w-full">

                        <thead class="border-b border-white/5">

                            <tr class="text-left text-sm text-slate-500">

                                <th class="px-6 py-4 font-medium">Course</th>
                                <th class="px-6 py-4 font-medium">Status</th>
                                <th class="px-6 py-4 font-medium">Students</th>
                                <th class="px-6 py-4 font-medium">Rating</th>
                                <th class="px-6 py-4 font-medium text-right">Action</th>

                            </tr>

                        </thead>

                        <tbody>

                            {{-- Drafts --}}
                            @foreach($drafts as $draft)

                                <tr class="border-b border-white/[0.03] hover:bg-white/[0.02] transition">

                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-4">

                                            <div class="w-12 h-12 rounded-xl border border-dashed border-violet-500/30 bg-white/[0.03] flex items-center justify-center">
                                                ✏️
                                            </div>

                                            <div>
                                                <p class="font-medium">
                                                    {{ $draft->title }}
                                                </p>
                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-6 py-5">
                                        <span class="px-3 py-1 rounded-full bg-orange-500/10 text-orange-400 text-xs font-medium">
                                            Draft
                                        </span>
                                    </td>

                                    <td class="px-6 py-5 text-slate-500">
                                        —
                                    </td>

                                    <td class="px-6 py-5 text-slate-500">
                                        —
                                    </td>

                                    <td class="px-6 py-5 text-right">

                                        <a
                                            href="{{ route('instructor.courses.edit', $draft) }}"
                                            class="text-sm text-violet-400 hover:text-violet-300 transition"
                                        >
                                            Continue
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                            {{-- Published Courses --}}
                            @foreach($courses as $course)

                                <tr class="border-b border-white/[0.03] hover:bg-white/[0.02] transition">

                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-4">

                                            <img
                                                src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=111827&color=fff&size=100' }}"
                                                class="w-12 h-12 rounded-xl object-cover"
                                            >

                                            <div>
                                                <p class="font-medium">
                                                    {{ $course->title }}
                                                </p>
                                            </div>

                                        </div>

                                    </td>

                                    <td class="px-6 py-5">

                                        <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-medium">
                                            Published
                                        </span>

                                    </td>

                                    <td class="px-6 py-5">
                                        {{ number_format($course->student_count) }}
                                    </td>

                                    <td class="px-6 py-5">

                                        <div class="flex items-center gap-2">
                                            ⭐
                                            <span>{{ number_format($course->rating, 1) }}</span>
                                        </div>

                                    </td>

                                    <td class="px-6 py-5 text-right">

                                        <a
                                            href="{{ route('instructor.courses.edit', $course) }}"
                                            class="text-sm text-violet-400 hover:text-violet-300 transition"
                                        >
                                            Manage
                                        </a>

                                    </td>

                                </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                @if($courses->hasPages())

                    <div class="px-6 py-5 border-t border-white/5">
                        {{ $courses->links() }}
                    </div>

                @endif

            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const ctx = document.getElementById('revenueChart').getContext('2d');

            const gradient = ctx.createLinearGradient(0, 0, 0, 400);

            gradient.addColorStop(0, 'rgba(139, 92, 246, 0.3)');
            gradient.addColorStop(1, 'rgba(139, 92, 246, 0)');

            new Chart(ctx, {
                type: 'line',

                data: {
                    labels: {!! json_encode($chartLabels) !!},

                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#8b5cf6',
                        backgroundColor: gradient,
                        fill: true,
                        tension: 0.4,
                        borderWidth: 3,
                        pointRadius: 0,
                        pointHoverRadius: 5,
                    }]
                },

                options: {
                    responsive: true,
                    maintainAspectRatio: false,

                    plugins: {

                        legend: {
                            display: false
                        },

                        tooltip: {
                            backgroundColor: '#111827',
                            borderColor: 'rgba(255,255,255,0.08)',
                            borderWidth: 1,
                            padding: 12,
                            displayColors: false,
                        }

                    },

                    scales: {

                        x: {

                            grid: {
                                display: false
                            },

                            ticks: {
                                color: '#64748b'
                            }

                        },

                        y: {

                            grid: {
                                color: 'rgba(255,255,255,0.04)'
                            },

                            ticks: {
                                color: '#64748b',
                                callback: function(value) {
                                    return '$' + value.toLocaleString();
                                }
                            },

                            beginAtZero: true

                        }

                    }

                }

            });

        });
    </script>

</x-app-layout>