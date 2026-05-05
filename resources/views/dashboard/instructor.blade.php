<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 lg:py-16">
        {{-- Header & Quick Actions --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
            <div>
                <h1 class="text-4xl font-display font-black text-white tracking-tighter">Instructor <span class="text-brand-500">Workspace</span></h1>
                <p class="mt-2 text-slate-500 font-medium text-lg">Manage curriculum, monitor growth, and mentor your scholars.</p>
            </div>
            <div class="flex flex-wrap items-center gap-4">
                <a href="{{ route('instructor.courses.create') }}" class="btn-primary py-3 px-8 text-xs group">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    New Course
                </a>
                <a href="{{ route('instructor.live-sessions.create') }}" class="px-8 py-3 bg-white/5 border border-white/10 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-white/10 transition-all flex items-center gap-3">
                    <span class="flex h-2 w-2 relative">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-500"></span>
                    </span>
                    Schedule Live
                </a>
            </div>
        </div>

        {{-- Bento Grid Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-12 gap-8 mb-8">
            
            {{-- Analytics Chart (col-span-8) --}}
            <div class="md:col-span-8 bg-dark-card border border-white/5 rounded-[2.5rem] p-10 flex flex-col justify-between group hover:border-brand-500/30 transition-colors">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1">Growth Matrix</h3>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest">Revenue & Enrollment Analytics</p>
                    </div>
                    <select class="bg-white/5 border border-white/10 text-[10px] font-black text-slate-400 rounded-xl px-4 py-2 focus:ring-0 cursor-pointer uppercase tracking-widest outline-none">
                        <option>H1 Analysis</option>
                        <option>Annual Report</option>
                    </select>
                </div>
                
                <div class="grid grid-cols-3 gap-6 mb-10">
                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Net Revenue</p>
                        <h4 class="text-3xl font-black text-white tracking-tighter">${{ number_format($stats['totalRevenue']) }}</h4>
                    </div>
                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Global Scholars</p>
                        <h4 class="text-3xl font-black text-white tracking-tighter">{{ number_format($stats['totalStudents']) }}</h4>
                    </div>
                    <div class="p-6 bg-white/5 rounded-3xl border border-white/5">
                        <p class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">Active Assets</p>
                        <h4 class="text-3xl font-black text-white tracking-tighter">{{ $stats['publishedCourses'] }}</h4>
                    </div>
                </div>

                <div class="flex-1 relative min-h-[300px]">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            {{-- Sidebar Bento (col-span-4) --}}
            <div class="md:col-span-4 grid grid-rows-2 gap-8">
                
                {{-- Needs Attention --}}
                <div class="bg-brand-500 rounded-[2.5rem] p-10 relative overflow-hidden group">
                    <div class="relative z-10">
                        <h3 class="text-xl font-black text-white mb-8 flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                            Task Radar
                        </h3>
                        <div class="space-y-4">
                            @if($drafts->count() > 0)
                                <div class="bg-white/10 backdrop-blur-xl border border-white/10 p-5 rounded-[1.5rem]">
                                    <p class="text-xs font-black text-white uppercase tracking-widest mb-1">Unpublished</p>
                                    <p class="text-sm font-medium text-brand-100">You have {{ $drafts->count() }} drafts pending review.</p>
                                </div>
                            @endif
                            <div class="bg-white/10 backdrop-blur-xl border border-white/10 p-5 rounded-[1.5rem]">
                                <p class="text-xs font-black text-white uppercase tracking-widest mb-1">Scholar Queries</p>
                                <p class="text-sm font-medium text-brand-100">3 priority questions awaiting response.</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -right-20 -top-20 w-64 h-64 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-1000"></div>
                </div>

                {{-- Top Performer Spotlight --}}
                @if($topCourse)
                    <div class="bg-dark-card border border-white/5 rounded-[2.5rem] p-1 relative overflow-hidden group">
                        <img src="{{ $topCourse->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($topCourse->title).'&background=121217&color=fff&size=600' }}" 
                             class="absolute inset-0 w-full h-full object-cover rounded-[2.4rem] opacity-20 group-hover:scale-110 group-hover:opacity-40 transition-all duration-1000">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-bg via-dark-bg/50 to-transparent"></div>
                        <div class="relative z-20 p-10 flex flex-col justify-end h-full">
                            <span class="inline-block px-3 py-1 bg-white/10 backdrop-blur-md border border-white/10 text-[10px] font-black text-white rounded-lg uppercase tracking-widest mb-4 w-fit">Top Performer</span>
                            <h4 class="text-2xl font-black text-white tracking-tighter leading-tight mb-4 group-hover:text-brand-500 transition-colors">{{ $topCourse->title }}</h4>
                            <div class="flex items-center gap-4 text-slate-400">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-brand-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                                    <span class="text-xs font-bold">{{ $topCourse->student_count }} Scholars</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- Lower Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            
            {{-- Live Control --}}
            <div class="bg-dark-card border border-white/5 rounded-[2.5rem] p-10 flex flex-col group hover:border-brand-500/30 transition-colors">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/5 text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white leading-none mb-1">Live Control</h3>
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Broadcast Hub</p>
                    </div>
                </div>
                
                @php
                    $liveSession = \App\Models\LiveSession::where('instructor_id', auth()->id())->whereIn('status', ['scheduled', 'live'])->orderBy('scheduled_at')->first();
                @endphp

                <div class="flex-1">
                    @if($liveSession)
                        <div class="bg-white/5 border border-white/5 rounded-3xl p-6 mb-8">
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Upcoming Stream</p>
                            <p class="text-white font-bold mb-2 line-clamp-1 text-lg">{{ $liveSession->topic }}</p>
                            <div class="flex items-center gap-2 text-brand-500 text-xs font-bold">
                                <span class="w-1.5 h-1.5 rounded-full bg-brand-500 animate-pulse"></span>
                                {{ $liveSession->scheduled_at->diffForHumans() }}
                            </div>
                        </div>
                        <a href="{{ route('instructor.live-sessions.show', $liveSession) }}" class="btn-primary w-full py-4 text-xs">
                            {{ $liveSession->status === 'live' ? 'Manage Broadcast' : 'Prepare Room' }}
                        </a>
                    @else
                        <p class="text-slate-500 font-medium mb-10">Launch a live session to interact with your scholars in real-time.</p>
                        <a href="{{ route('instructor.live-sessions.create') }}" class="w-full py-4 bg-white/5 border border-white/10 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-white/10 transition-all text-center">
                            Schedule New Session
                        </a>
                    @endif
                </div>
            </div>

            {{-- Reviews Feed --}}
            <div class="bg-dark-card border border-white/5 rounded-[2.5rem] p-10 flex flex-col group hover:border-brand-500/30 transition-colors">
                <h3 class="text-lg font-bold text-white mb-10 flex items-center justify-between">
                    Scholar Insights
                    <span class="text-[10px] font-black text-brand-500 uppercase tracking-widest">Recent Feedback</span>
                </h3>
                
                <div class="flex-1 overflow-y-auto space-y-6 max-h-[250px] hide-scrollbar pr-2">
                    @forelse($reviews as $review)
                        <div class="p-6 bg-white/5 border border-white/5 rounded-3xl">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-black text-white">{{ $review->user->name }}</span>
                                <div class="flex gap-1 text-brand-500">
                                    @for($i=0; $i<$review->rating; $i++)
                                        <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                            </div>
                            <p class="text-xs text-slate-500 leading-relaxed italic">"{{ $review->review ?? 'Exceptional learning experience.' }}"</p>
                        </div>
                    @empty
                        <div class="h-full flex items-center justify-center text-slate-600 font-bold uppercase tracking-widest text-xs">No Insights Found</div>
                    @endforelse
                </div>
            </div>

            {{-- Engagement Metrics --}}
            <div class="bg-white rounded-[2.5rem] p-10 flex flex-col group relative overflow-hidden">
                <div class="relative z-10 h-full flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-dark-bg leading-none mb-1">Scholar Intensity</h3>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Engagement Heatmap</p>
                    </div>

                    <div class="flex gap-2 justify-between">
                        @foreach(['M', 'T', 'W', 'T', 'F', 'S', 'S'] as $day)
                            <div class="flex flex-col items-center gap-2">
                                @for($i=0; $i<5; $i++)
                                    @php $op = [10, 20, 40, 60, 80, 100][rand(0, 5)]; @endphp
                                    <div class="w-5 h-5 rounded-md bg-dark-bg/{{ $op }}"></div>
                                @endfor
                                <span class="text-[10px] font-black text-slate-400 mt-2">{{ $day }}</span>
                            </div>
                        @endforeach
                    </div>

                    <div class="pt-8 border-t border-slate-100 mt-8">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Infrastructure</p>
                        <div class="flex items-end gap-2 mb-3">
                            <span class="text-4xl font-black text-dark-bg tracking-tighter">1.2</span>
                            <span class="text-slate-400 font-black text-[10px] uppercase mb-1.5 tracking-widest">GB Used</span>
                        </div>
                        <div class="w-full flex h-2 rounded-full overflow-hidden bg-slate-100">
                            <div class="bg-brand-500 w-[24%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Portfolio Table --}}
        <div class="bg-dark-card border border-white/5 rounded-[3rem] overflow-hidden group hover:border-brand-500/30 transition-colors">
            <div class="p-10 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 class="text-xl font-black text-white">Course Portfolio</h3>
                <a href="{{ route('instructor.courses.index') }}" class="text-[10px] font-black text-brand-500 hover:text-brand-400 uppercase tracking-widest transition-colors">Manage All Assets</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="text-[10px] uppercase tracking-[0.2em] text-slate-500 border-b border-white/5">
                            <th class="p-8 font-black">Asset</th>
                            <th class="p-8 font-black text-center">Status</th>
                            <th class="p-8 font-black text-right">Scholars</th>
                            <th class="p-8 font-black text-center">Rating</th>
                            <th class="p-8 font-black text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @foreach($drafts as $draft)
                            <tr class="hover:bg-white/5 transition bg-white/[0.02]">
                                <td class="p-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-white/5 border border-dashed border-brand-500/50 flex items-center justify-center text-brand-500">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                                        </div>
                                        <p class="font-bold text-white text-lg tracking-tight">{{ $draft->title }}</p>
                                    </div>
                                </td>
                                <td class="p-8 text-center">
                                    <span class="px-4 py-1 bg-white/10 text-white text-[10px] font-black rounded-lg uppercase tracking-widest">Draft</span>
                                </td>
                                <td class="p-8 text-right text-slate-600 font-black">-</td>
                                <td class="p-8 text-center text-slate-600 font-black">-</td>
                                <td class="p-8 text-right">
                                    <a href="{{ route('instructor.courses.edit', $draft) }}" class="text-[10px] font-black text-brand-500 hover:text-brand-400 uppercase tracking-widest transition-colors">Continue &rarr;</a>
                                </td>
                            </tr>
                        @endforeach

                        @foreach($courses as $course)
                            <tr class="hover:bg-white/5 transition group/row">
                                <td class="p-8">
                                    <div class="flex items-center gap-4">
                                        <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=121217&color=fff&size=100' }}" class="w-12 h-12 rounded-2xl object-cover border border-white/5 group-hover/row:border-brand-500 transition-colors">
                                        <p class="font-bold text-white text-lg tracking-tight group-hover/row:text-brand-500 transition-colors">{{ $course->title }}</p>
                                    </div>
                                </td>
                                <td class="p-8 text-center">
                                    <span class="px-4 py-1 bg-brand-500/10 text-brand-500 text-[10px] font-black rounded-lg uppercase tracking-widest border border-brand-500/20">Active</span>
                                </td>
                                <td class="p-8 text-right font-black text-white tracking-tighter text-xl">{{ number_format($course->student_count) }}</td>
                                <td class="p-8 text-center">
                                    <div class="flex items-center justify-center gap-2 text-brand-500">
                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        <span class="font-black text-lg tracking-tighter text-white">{{ number_format($course->rating, 1) }}</span>
                                    </div>
                                </td>
                                <td class="p-8 text-right">
                                    <a href="{{ route('instructor.courses.edit', $course) }}" class="text-[10px] font-black text-slate-500 hover:text-white uppercase tracking-widest transition-colors">Manage Asset</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($courses->hasPages())
                <div class="p-8 border-t border-white/5">
                    {{ $courses->links() }}
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            
            let gradient = ctx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Net Revenue',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: gradient,
                        borderWidth: 4,
                        pointBackgroundColor: '#09090b',
                        pointBorderColor: '#3b82f6',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
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
                            backgroundColor: '#18181b',
                            titleFont: { family: 'Outfit', size: 14, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 14, weight: '600' },
                            padding: 16,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) { return ' $' + context.parsed.y.toLocaleString(); }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { family: 'Inter', size: 12, weight: 'bold' }, color: '#71717a' }
                        },
                        y: {
                            grid: { color: 'rgba(255, 255, 255, 0.05)', drawBorder: false },
                            ticks: {
                                font: { family: 'Inter', size: 12, weight: 'bold' }, color: '#71717a',
                                callback: function(value) { return '$' + value.toLocaleString(); }
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
