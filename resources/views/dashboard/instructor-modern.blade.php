<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 py-6 sm:py-10">

            {{-- Header with Actions --}}
            <div class="mb-10 sm:mb-12">
                <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-8">

                    <div>
                        <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 bg-brand-100 rounded-full text-xs font-semibold text-brand-700">
                            <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            <span>Instructor Dashboard</span>
                        </div>

                        <h1 class="text-5xl lg:text-6xl font-display font-black tracking-tight leading-tight text-slate-900 mb-3">
                            Welcome back, <span class="text-brand-600">{{ auth()->user()->name }}</span>
                        </h1>

                        <p class="text-lg text-slate-600 max-w-2xl">
                            Track your courses, revenue, and student progress all in one place.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">

                        <a href="{{ route('instructor.courses.create') }}" 
                           class="h-12 px-6 rounded-xl bg-gradient-to-r from-brand-600 to-brand-700 hover:from-brand-700 hover:to-brand-800 transition-all duration-200 text-sm font-bold flex items-center text-white shadow-lg shadow-brand-500/20 hover:shadow-xl hover:shadow-brand-500/30 hover:scale-105">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M12 5v14m7-7H5"/></svg>
                            New Course
                        </a>

                        <a href="{{ route('instructor.live-sessions.create') }}" 
                           class="h-12 px-6 rounded-xl border-2 border-brand-200 bg-white hover:bg-brand-50 transition-all duration-200 text-sm font-bold flex items-center text-brand-700 hover:border-brand-400">
                            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24" fill="currentColor"><path d="M23 7l-7 5v-4L23 7zM1 6h14v12H1z"/></svg>
                            Live Session
                        </a>

                    </div>

                </div>
            </div>

            {{-- Top Metrics Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                {{-- Total Revenue --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide mb-1">Total Revenue</p>
                            <h3 class="text-4xl font-black text-slate-900">
                                ${{ number_format($stats['totalRevenue']) }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-100 to-green-50 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-green-600 font-semibold">↑ 12% from last month</p>
                </div>

                {{-- Active Students --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide mb-1">Active Students</p>
                            <h3 class="text-4xl font-black text-slate-900">
                                {{ number_format($stats['totalStudents']) }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-100 to-blue-50 flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M9 13.75c-2.67 0-8 1.34-8 4v2.25h16v-2.25c0-2.66-5.33-4-8-4zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm7.5-2c1.66 0 2.99-1.34 2.99-3S18.16 5 16.5 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm1.5 5.1c2.5 0 4.69 1.26 6 3.16V19h-15v-2.74c1.31-1.9 3.5-3.16 6-3.16z"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-blue-600 font-semibold">↑ 8 new this week</p>
                </div>

                {{-- Published Courses --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide mb-1">Published Courses</p>
                            <h3 class="text-4xl font-black text-slate-900">
                                {{ $stats['publishedCourses'] }}
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-100 to-purple-50 flex items-center justify-center text-purple-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M19 2H9a2 2 0 00-2 2v14a2 2 0 002 2h10V2zM7 6H5a2 2 0 00-2 2v12a2 2 0 002 2h2V6z"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-purple-600 font-semibold">2 in draft</p>
                </div>

                {{-- Avg Rating --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow group">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide mb-1">Avg Rating</p>
                            <h3 class="text-4xl font-black text-slate-900">
                                4.8
                            </h3>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-100 to-yellow-50 flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                        </div>
                    </div>
                    <p class="text-xs text-yellow-600 font-semibold">⭐ From 248 reviews</p>
                </div>

            </div>

            {{-- Main Content Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                {{-- Revenue Chart --}}
                <div class="lg:col-span-2 rounded-2xl bg-white shadow-sm border border-slate-200 p-8 hover:shadow-md transition-shadow">

                    <div class="flex items-center justify-between mb-8">

                        <div>
                            <p class="text-xs text-slate-600 font-semibold uppercase tracking-wide mb-1">Revenue Overview</p>
                            <h2 class="text-2xl font-black text-slate-900">
                                Revenue & Growth
                            </h2>
                        </div>

                        <select class="bg-white border-2 border-slate-200 rounded-lg h-10 px-4 text-sm outline-none hover:border-brand-300 transition-colors font-semibold text-slate-700">
                            <option>Last 6 Months</option>
                            <option>Last Year</option>
                            <option>All Time</option>
                        </select>

                    </div>

                    <div class="h-[320px]">
                        <canvas id="revenueChart"></canvas>
                    </div>

                </div>

                {{-- Quick Stats --}}
                <div class="space-y-6">

                    {{-- Pending Tasks --}}
                    <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">

                        <div class="flex items-center justify-between mb-6">

                            <h3 class="text-lg font-black text-slate-900">
                                📋 Todo
                            </h3>

                            @if($drafts->count() > 0)
                                <span class="inline-flex items-center justify-center w-6 h-6 bg-red-500 text-white text-xs font-black rounded-full">
                                    {{ $drafts->count() }}
                                </span>
                            @endif

                        </div>

                        <div class="space-y-2">

                            @if($drafts->count() > 0)
                                <div class="p-4 rounded-xl bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 hover:border-amber-300 transition-colors">

                                    <div class="flex items-start justify-between gap-2">

                                        <div class="flex-1">
                                            <p class="font-bold text-amber-900 text-sm">
                                                {{ $drafts->count() }} Draft {{ Str::plural('Course', $drafts->count()) }}
                                            </p>

                                            <p class="text-xs text-amber-700 mt-1">
                                                Ready to publish
                                            </p>
                                        </div>

                                        <span class="px-2 py-1 bg-amber-200 text-amber-800 text-[10px] font-bold rounded">
                                            PENDING
                                        </span>

                                    </div>

                                </div>
                            @endif

                            <div class="p-4 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 hover:border-blue-300 transition-colors">

                                <div class="flex items-start justify-between gap-2">

                                    <div class="flex-1">
                                        <p class="font-bold text-blue-900 text-sm">
                                            Pending Work
                                        </p>

                                        <p class="text-xs text-blue-700 mt-1">
                                            @if(isset($pendingWork) && $pendingWork > 0)
                                                {{ $pendingWork }} item{{ $pendingWork !== 1 ? 's' : '' }} awaiting your attention
                                            @else
                                                All caught up!
                                            @endif
                                        </p>
                                    </div>

                                    @if(isset($pendingWork) && $pendingWork > 0)
                                        <span class="px-2 py-1 bg-blue-200 text-blue-800 text-[10px] font-bold rounded">
                                            {{ $pendingWork }}
                                        </span>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- Top Course --}}
                    @if($topCourse)
                        <div class="rounded-2xl bg-gradient-to-br from-slate-900 to-slate-800 shadow-sm border border-slate-700 overflow-hidden hover:shadow-md transition-shadow">

                            <img
                                src="{{ $topCourse->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($topCourse->title).'&background=111827&color=fff&size=800' }}"
                                class="h-40 w-full object-cover opacity-40"
                            >

                            <div class="relative -mt-32 mx-4 p-6 rounded-xl bg-white shadow-lg">

                                <div class="inline-flex items-center px-3 py-1 rounded-full bg-brand-100 text-brand-700 text-xs font-bold mb-3">
                                    🏆 Top Performer
                                </div>

                                <h3 class="text-lg font-black leading-tight mb-3 text-slate-900 line-clamp-2">
                                    {{ $topCourse->title }}
                                </h3>

                                <div class="flex items-center gap-4 text-sm text-slate-600">

                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-blue-500" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/></svg>
                                        <span class="font-semibold">{{ number_format($topCourse->student_count ?? 0) }} students</span>
                                    </div>

                                </div>

                            </div>

                        </div>
                    @endif

                </div>

            </div>

            {{-- Bottom Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Live Sessions --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">

                    <div class="flex items-center justify-between mb-6">

                        <h2 class="text-lg font-black text-slate-900">
                            🔴 Live Sessions
                        </h2>

                        <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-red-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M23 7l-7 5v-4L23 7zM1 6h14v12H1z"/></svg>
                        </div>

                    </div>

                    @php
                        $liveSession = \App\Models\LiveSession::where('instructor_id', auth()->id())
                            ->active()
                            ->orderBy('scheduled_at')
                            ->first();
                    @endphp

                    @if($liveSession)

                        <div class="p-5 rounded-xl bg-gradient-to-br from-red-50 to-red-100 border border-red-200 mb-4">

                            <p class="text-xs text-red-700 font-bold uppercase tracking-wide mb-2">
                                Upcoming
                            </p>

                            <h3 class="font-bold text-lg text-red-900 mb-2">
                                {{ $liveSession->title }}
                            </h3>

                            <p class="text-sm text-red-700 mb-4">
                                {{ $liveSession->scheduled_at->format('M d, h:i A') }}
                            </p>

                            <a href="{{ route('instructor.live-sessions.edit', $liveSession) }}" 
                               class="inline-flex items-center h-9 px-4 rounded-lg bg-red-600 hover:bg-red-700 transition text-white text-xs font-bold">
                                Manage
                            </a>

                        </div>

                    @else

                        <div class="p-5 rounded-xl bg-slate-50 border border-slate-200 text-center">
                            <p class="text-sm text-slate-600">No live sessions scheduled yet</p>
                        </div>

                    @endif

                    <a href="{{ route('instructor.live-sessions.create') }}" 
                       class="block w-full mt-4 h-10 rounded-lg border-2 border-dashed border-slate-300 hover:border-brand-400 text-slate-700 hover:text-brand-600 font-bold text-sm transition-colors flex items-center justify-center">
                        + Schedule New
                    </a>

                </div>

                {{-- Recent Enrollments --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">

                    <div class="flex items-center justify-between mb-6">

                        <h2 class="text-lg font-black text-slate-900">
                            👥 Recent Activity
                        </h2>

                        <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/></svg>
                        </div>

                    </div>

                    <div class="space-y-3">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-green-300 transition-colors">
                            <p class="font-bold text-slate-900 text-sm">New Enrollment</p>
                            <p class="text-xs text-slate-600 mt-1">John Doe joined Web Dev</p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-green-300 transition-colors">
                            <p class="font-bold text-slate-900 text-sm">Course Completed</p>
                            <p class="text-xs text-slate-600 mt-1">Jane Smith finished UI Design</p>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100 hover:border-green-300 transition-colors">
                            <p class="font-bold text-slate-900 text-sm">New Review</p>
                            <p class="text-xs text-slate-600 mt-1">5 star review on React Basics</p>
                        </div>
                    </div>

                </div>

                {{-- Course Status --}}
                <div class="rounded-2xl bg-white shadow-sm border border-slate-200 p-6 hover:shadow-md transition-shadow">

                    <div class="flex items-center justify-between mb-6">

                        <h2 class="text-lg font-black text-slate-900">
                            📊 Course Status
                        </h2>

                        <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zM9 17H7v-7h2v7zm4 0h-2V7h2v10zm4 0h-2v-4h2v4z"/></svg>
                        </div>

                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                            <span class="text-sm font-semibold text-slate-700">Published</span>
                            <span class="text-lg font-black text-slate-900">{{ $stats['publishedCourses'] }}</span>
                        </div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                            <span class="text-sm font-semibold text-slate-700">In Draft</span>
                            <span class="text-lg font-black text-slate-900">{{ $drafts->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-slate-700">Total Students</span>
                            <span class="text-lg font-black text-slate-900">{{ number_format($stats['totalStudents']) }}</span>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>

    {{-- Chart Script --}}
    <script>
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            const gradient = revenueCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(37, 99, 235, 0.1)');
            gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

            new Chart(revenueCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [2500, 3200, 2800, 4100, 3900, 4600],
                        borderColor: 'rgb(37, 99, 235)',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: 'rgb(37, 99, 235)',
                        pointBorderColor: 'white',
                        pointBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            border: { display: false },
                            grid: { color: 'rgba(0, 0, 0, 0.05)' },
                            ticks: { font: { weight: 'bold', size: 12 } }
                        },
                        x: {
                            border: { display: false },
                            grid: { display: false },
                            ticks: { font: { weight: 'bold', size: 12 } }
                        }
                    }
                }
            });
        }
    </script>

</x-app-layout>
