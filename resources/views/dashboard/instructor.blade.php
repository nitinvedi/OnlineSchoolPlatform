<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        {{-- Header & Quick Actions --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Instructor Workspace</h1>
                <p class="mt-1 text-slate-500 font-medium">Manage your courses, interact with students, and track your performance.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('instructor.courses.create') }}" class="inline-flex items-center gap-2 bg-slate-900 text-white px-5 py-2.5 rounded-xl font-bold shadow-sm hover:bg-slate-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    New Course
                </a>
                <a href="{{ route('instructor.live-sessions.create') }}" class="inline-flex items-center gap-2 bg-sky-50 text-sky-600 px-5 py-2.5 rounded-xl font-bold border border-sky-200 hover:bg-sky-100 transition">
                    <span class="flex h-3 w-3 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-sky-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-sky-500"></span>
                    </span>
                    Schedule Live
                </a>
            </div>
        </div>

        {{-- Bento Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 mb-10">
            
            {{-- Analytics Chart & Top KPIs (col-span-8) --}}
            <div class="md:col-span-8 lg:col-span-8 bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-slate-800">Revenue & Enrollment Growth</h3>
                    <select class="bg-slate-50 border-none text-sm font-bold text-slate-600 rounded-lg focus:ring-0 cursor-pointer">
                        <option>Last 6 Months</option>
                        <option>This Year</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Revenue</p>
                        <h4 class="text-2xl font-black text-slate-800">${{ number_format($stats['totalRevenue']) }}</h4>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Students</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ number_format($stats['totalStudents']) }}</h4>
                    </div>
                    <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Published</p>
                        <h4 class="text-2xl font-black text-slate-800">{{ $stats['publishedCourses'] }}</h4>
                    </div>
                </div>

                <div class="flex-1 relative min-h-[250px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- "Needs Attention" & Top Course Spotlight (col-span-4) --}}
            <div class="md:col-span-4 lg:col-span-4 grid grid-rows-2 gap-6">
                
                {{-- Needs Attention Command Center --}}
                <div class="bg-amber-50 rounded-3xl p-6 shadow-sm border border-amber-100 relative overflow-hidden">
                    <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-200 rounded-full opacity-50 blur-2xl"></div>
                    <h3 class="text-lg font-bold text-amber-900 mb-4 flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Needs Attention
                    </h3>
                    <ul class="space-y-3 relative z-10">
                        @if($drafts->count() > 0)
                            <li class="flex items-start gap-3 bg-white/60 p-3 rounded-xl border border-amber-200/50">
                                <span class="w-2 h-2 rounded-full bg-amber-500 mt-1.5 flex-shrink-0"></span>
                                <div>
                                    <p class="text-sm font-bold text-amber-900">Finish Drafts</p>
                                    <p class="text-xs text-amber-700">You have {{ $drafts->count() }} unpublished courses.</p>
                                </div>
                            </li>
                        @endif
                        <li class="flex items-start gap-3 bg-white/60 p-3 rounded-xl border border-amber-200/50">
                            <span class="w-2 h-2 rounded-full bg-amber-500 mt-1.5 flex-shrink-0"></span>
                            <div>
                                <p class="text-sm font-bold text-amber-900">Q&A Backlog</p>
                                <p class="text-xs text-amber-700">3 student questions awaiting your reply.</p>
                            </div>
                        </li>
                    </ul>
                </div>

                {{-- Hero Metric Spotlight --}}
                @if($topCourse)
                    <div class="bg-slate-900 rounded-3xl p-1 shadow-md relative overflow-hidden group">
                        <div class="absolute inset-0 bg-slate-900/60 z-10 rounded-[1.4rem]"></div>
                        <img src="{{ $topCourse->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($topCourse->title).'&background=334155&color=fff&size=400' }}" class="absolute inset-0 w-full h-full object-cover rounded-[1.4rem] filter blur-sm group-hover:blur-0 group-hover:scale-105 transition-all duration-700">
                        <div class="relative z-20 p-6 flex flex-col justify-between h-full">
                            <span class="self-start px-2.5 py-1 bg-amber-500 text-white text-[10px] font-black tracking-widest uppercase rounded-lg shadow-sm">Top Performer</span>
                            <div>
                                <h4 class="text-white font-bold text-lg leading-tight mb-1 drop-shadow-md line-clamp-2">{{ $topCourse->title }}</h4>
                                <p class="text-slate-300 text-sm font-medium flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                                    {{ $topCourse->student_count }} Students
                                </p>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-slate-50 rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-center items-center text-center">
                        <p class="text-slate-400 font-bold">Publish a course to see your top performer here.</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- Interactive Grid: Live Room, Storage, Reviews --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
            
            {{-- Live Control Room Widget --}}
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-3xl p-6 shadow-md text-white">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
                        Live Control Room
                    </h3>
                </div>
                
                @php
                    $liveSession = \App\Models\LiveSession::where('instructor_id', auth()->id())->whereIn('status', ['scheduled', 'live'])->orderBy('scheduled_at')->first();
                @endphp

                @if($liveSession)
                    <div class="bg-slate-700/50 border border-slate-600 rounded-2xl p-4 mb-4">
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Up Next</p>
                        <p class="font-bold text-white mb-1 line-clamp-1">{{ $liveSession->topic }}</p>
                        <p class="text-sm text-sky-400">{{ $liveSession->scheduled_at->diffForHumans() }}</p>
                    </div>
                    <a href="{{ route('instructor.live-sessions.show', $liveSession) }}" class="w-full block text-center px-4 py-3 {{ $liveSession->status === 'live' ? 'bg-red-500 hover:bg-red-600' : 'bg-sky-500 hover:bg-sky-600' }} text-white font-bold rounded-xl transition shadow-lg">
                        {{ $liveSession->status === 'live' ? 'Manage Live Broadcast' : 'Prepare Room' }}
                    </a>
                @else
                    <p class="text-slate-400 text-sm mb-6">No upcoming sessions. Schedule one to engage with your students in real-time.</p>
                    <a href="{{ route('instructor.live-sessions.create') }}" class="w-full block text-center px-4 py-3 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition border border-white/10">
                        Schedule Session
                    </a>
                @endif
            </div>

            {{-- Sentiment Reviews Feed --}}
            <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">Recent Reviews</h3>
                </div>
                
                <div class="flex-1 overflow-y-auto pr-2 space-y-4 max-h-[250px] hide-scrollbar">
                    @forelse($reviews as $review)
                        @php
                            $sentimentClass = 'bg-slate-50 border-slate-100';
                            $iconClass = 'text-amber-400';
                            if ($review->rating >= 4) { $sentimentClass = 'bg-emerald-50 border-emerald-100'; $iconClass = 'text-emerald-500'; }
                            elseif ($review->rating <= 2) { $sentimentClass = 'bg-rose-50 border-rose-100'; $iconClass = 'text-rose-500'; }
                        @endphp
                        <div class="p-3 rounded-xl border {{ $sentimentClass }}">
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-bold text-slate-700">{{ $review->user->name }}</span>
                                <div class="flex gap-0.5">
                                    @for($i=0; $i<$review->rating; $i++)
                                        <svg class="w-3 h-3 {{ $iconClass }} fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 line-clamp-2 italic">"{{ $review->review ?? 'No written feedback.' }}"</p>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-4">No reviews yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Storage & Heatmap --}}
            <div class="grid grid-rows-2 gap-6">
                {{-- Storage Quota --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-center">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-2">Media Storage</h3>
                    <div class="flex items-end gap-2 mb-2">
                        <span class="text-3xl font-extrabold text-slate-800">1.2</span>
                        <span class="text-slate-400 font-medium mb-1">GB / 5.0 GB</span>
                    </div>
                    <div class="w-full flex h-2 rounded-full overflow-hidden bg-slate-100 gap-0.5">
                        <div class="bg-indigo-500 w-[15%]"></div>
                        <div class="bg-sky-400 w-[9%]"></div>
                        <div class="bg-emerald-400 w-[5%]"></div>
                    </div>
                    <div class="flex gap-4 mt-3 text-xs font-bold text-slate-400">
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-indigo-500"></div> Videos</span>
                        <span class="flex items-center gap-1"><div class="w-2 h-2 rounded-full bg-sky-400"></div> Assets</span>
                    </div>
                </div>

                {{-- Student Engagement Heatmap (Mock) --}}
                <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100 flex flex-col justify-center">
                    <h3 class="text-sm font-bold text-slate-500 uppercase tracking-wider mb-4">Engagement Heatmap</h3>
                    <div class="flex gap-1 justify-between">
                        @foreach(['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $day)
                            <div class="flex flex-col items-center gap-1">
                                @for($i=0; $i<4; $i++)
                                    @php $op = [10, 20, 40, 60, 80, 100][rand(0, 5)]; @endphp
                                    <div class="w-4 h-4 rounded-sm bg-sky-500/{{ $op }}"></div>
                                @endfor
                                <span class="text-[10px] font-bold text-slate-400 mt-1">{{ $day }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- Course Performance Table with Inline Sparklines & Draft Wireframes --}}
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">Course Portfolio</h3>
                <a href="{{ route('instructor.courses.index') }}" class="text-sm font-bold text-sky-500 hover:text-sky-600">Manage All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-white text-xs uppercase tracking-wider text-slate-400 border-b border-slate-100">
                            <th class="p-4 font-bold">Course</th>
                            <th class="p-4 font-bold text-center">Status</th>
                            <th class="p-4 font-bold text-right">Students</th>
                            <th class="p-4 font-bold text-center">Rating</th>
                            <th class="p-4 font-bold text-center hidden md:table-cell">Momentum</th>
                            <th class="p-4 font-bold text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        {{-- Drafts (Wireframe Aesthetic) --}}
                        @foreach($drafts as $draft)
                            <tr class="hover:bg-slate-50 transition border-l-4 border-l-amber-300 bg-amber-50/30">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-white border border-dashed border-amber-300 flex items-center justify-center text-amber-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                        <p class="font-bold text-slate-600 border-b border-dashed border-slate-300">{{ $draft->title }}</p>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2.5 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-md uppercase tracking-wide">Draft</span>
                                </td>
                                <td class="p-4 text-right text-slate-400 font-medium">-</td>
                                <td class="p-4 text-center text-slate-400 font-medium">-</td>
                                <td class="p-4 text-center hidden md:table-cell">
                                    <div class="w-16 h-4 bg-slate-100 rounded-sm inline-block opacity-50"></div>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('instructor.courses.edit', $draft) }}" class="text-sm font-bold text-sky-500 hover:text-sky-700">Continue &rarr;</a>
                                </td>
                            </tr>
                        @endforeach

                        {{-- Published Courses --}}
                        @foreach($courses as $course)
                            <tr class="hover:bg-slate-50 transition group">
                                <td class="p-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=e2e8f0&color=475569&size=100' }}" class="w-10 h-10 rounded-lg object-cover shadow-sm">
                                        <p class="font-bold text-slate-800 group-hover:text-sky-600 transition">{{ $course->title }}</p>
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-xs font-bold rounded-md uppercase tracking-wide">Live</span>
                                </td>
                                <td class="p-4 text-right font-bold text-slate-700">{{ number_format($course->student_count) }}</td>
                                <td class="p-4 text-center font-bold text-amber-500">{{ number_format($course->rating, 1) }}</td>
                                <td class="p-4 text-center hidden md:table-cell">
                                    {{-- Mock Inline Sparkline --}}
                                    <svg class="w-16 h-6 inline-block" viewBox="0 0 100 30" preserveAspectRatio="none">
                                        <path d="M0,25 Q10,20 20,25 T40,15 T60,20 T80,5 T100,10" fill="none" stroke="#0ea5e9" stroke-width="2" stroke-linecap="round"/>
                                        <circle cx="100" cy="10" r="2.5" fill="#0ea5e9"/>
                                    </svg>
                                </td>
                                <td class="p-4 text-right">
                                    <a href="{{ route('instructor.courses.edit', $course) }}" class="text-sm font-bold text-slate-400 hover:text-slate-700 transition">Manage</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-slate-100">
                {{ $courses->links() }}
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            // Create gradient
            let gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(14, 165, 233, 0.2)'); // sky-500
            gradient.addColorStop(1, 'rgba(14, 165, 233, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Revenue ($)',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#0ea5e9',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0ea5e9',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Inter', size: 13 },
                            bodyFont: { family: 'Inter', size: 14, weight: 'bold' },
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) { return '$' + context.parsed.y; }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: 'Inter', size: 12 }, color: '#94a3b8' }
                        },
                        y: {
                            grid: { color: '#f1f5f9', drawBorder: false },
                            ticks: {
                                font: { family: 'Inter', size: 12 }, color: '#94a3b8',
                                callback: function(value) { return '$' + value; }
                            },
                            beginAtZero: true
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        });
    </script>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
