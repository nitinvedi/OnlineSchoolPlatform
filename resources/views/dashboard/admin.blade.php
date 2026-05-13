<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 lg:py-16">

        {{-- Header --}}
        <div class="mb-10">
            <h1 class="text-4xl font-display font-black text-slate-900 tracking-tighter">
                Command <span class="text-[#2255FF]">Center</span>
            </h1>
            <p class="mt-2 text-slate-500 font-medium text-base">Platform metrics and management overview.</p>
        </div>

        {{-- KPI Cards --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-7">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Global Users</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['totalUsers']) }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-semibold">{{ $mom['users'] }} this month</p>
            </div>
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-7">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Net Revenue</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">${{ number_format($stats['totalRevenue']) }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-semibold">{{ $mom['revenue'] }} vs last month</p>
            </div>
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-7">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Active Courses</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['totalCourses']) }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-semibold">{{ $mom['courses'] }} vs last month</p>
            </div>
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-7">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Enrollments</p>
                <h3 class="text-4xl font-black text-slate-900 tracking-tighter">{{ number_format($stats['totalEnrollments']) }}</h3>
                <p class="text-xs text-slate-400 mt-1 font-semibold">{{ $mom['enrollments'] }} vs last month</p>
            </div>
        </div>

        {{-- Main Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">

            {{-- Revenue Chart (real data) --}}
            <div class="lg:col-span-2 bg-white shadow-sm border border-slate-200 rounded-3xl p-8 min-h-[380px] flex flex-col">
                <div class="mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Financial Trajectory</h3>
                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Platform-wide Revenue — Last 6 Months</p>
                </div>
                <div class="flex-1 relative">
                    <canvas id="adminRevenueChart"></canvas>
                </div>
            </div>

            {{-- User Topology (real data) --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-8 flex flex-col">
                <h3 class="text-lg font-bold text-slate-900 mb-6">User Breakdown</h3>
                <div class="relative flex-1 flex justify-center items-center" style="max-height:200px;">
                    <canvas id="roleDistributionChart"></canvas>
                </div>
                <div class="mt-8 space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-[#2255FF]"></div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Students</span>
                        </div>
                        <span class="text-slate-900 font-black">{{ number_format($demographics['students']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-indigo-500"></div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Instructors</span>
                        </div>
                        <span class="text-slate-900 font-black">{{ number_format($demographics['instructors']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Admins</span>
                        </div>
                        <span class="text-slate-900 font-black">{{ number_format($demographics['admins']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lower Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- Pending Approvals (real data) --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-8">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-slate-900">Pending Approvals</h3>
                    <span class="px-3 py-1 bg-[#2255FF] text-white text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $pendingApprovals->count() }}</span>
                </div>
                <div class="space-y-3">
                    @forelse($pendingApprovals as $user)
                        <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#2255FF] flex items-center justify-center text-white font-black text-xs">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 text-sm">{{ $user->name }}</p>
                                    <p class="text-[10px] font-black text-[#2255FF] uppercase tracking-widest">Instructor Application</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-10 text-center text-slate-400">
                            <p class="text-xs font-black uppercase tracking-widest">Queue Empty</p>
                        </div>
                    @endforelse
                </div>
                @if($pendingApprovals->count() > 0)
                    <a href="{{ route('admin.courses.index', ['status' => 'pending']) }}" class="mt-4 flex items-center justify-center w-full py-2.5 text-xs font-bold text-[#2255FF] hover:bg-blue-50 border border-[#2255FF]/20 rounded-xl transition">
                        Review All &rarr;
                    </a>
                @endif
            </div>

            {{-- Real Activity (real data from DB) --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Recent Enrollments</h3>
                <div class="space-y-4">
                    @forelse($recentActivity as $enrollment)
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 text-[#2255FF] flex items-center justify-center text-xs font-black flex-shrink-0">
                                {{ strtoupper(substr($enrollment->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $enrollment->user->name ?? 'Unknown' }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $enrollment->course->title ?? 'Unknown Course' }}</p>
                            </div>
                            <span class="text-xs text-slate-400 whitespace-nowrap">{{ $enrollment->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-slate-400 text-center py-8">No enrollments yet.</p>
                    @endforelse
                </div>
            </div>

            {{-- Course Categories (real data) --}}
            <div class="bg-white shadow-sm border border-slate-200 rounded-3xl p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-6">Course Categories</h3>
                <div class="space-y-5">
                    @foreach($categories as $category)
                        <div>
                            <div class="flex justify-between mb-1.5">
                                <span class="text-sm font-bold text-slate-900">{{ $category->name }}</span>
                                <span class="text-xs font-black text-slate-500">{{ $category->courses_count }}</span>
                            </div>
                            @php $percent = $stats['totalCourses'] > 0 ? ($category->courses_count / $stats['totalCourses']) * 100 : 0; @endphp
                            <div class="w-full bg-slate-100 rounded-full h-1.5">
                                <div class="bg-[#2255FF] h-1.5 rounded-full transition-all duration-700" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const revCtx = document.getElementById('adminRevenueChart').getContext('2d');
            let gradRev = revCtx.createLinearGradient(0, 0, 0, 300);
            gradRev.addColorStop(0, 'rgba(34, 85, 255, 0.15)');
            gradRev.addColorStop(1, 'rgba(34, 85, 255, 0)');

            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Revenue',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#2255FF',
                        backgroundColor: gradRev,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#2255FF',
                        pointBorderWidth: 2,
                        pointRadius: 5,
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
                            callbacks: { label: ctx => ' $' + ctx.parsed.y.toLocaleString() }
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: '#94a3b8', font: { weight: 'bold', size: 11 } } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { color: '#94a3b8', callback: v => '$' + v.toLocaleString() } }
                    }
                }
            });

            const roleCtx = document.getElementById('roleDistributionChart').getContext('2d');
            new Chart(roleCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Students', 'Instructors', 'Admins'],
                    datasets: [{
                        data: [{!! $demographics['students'] !!}, {!! $demographics['instructors'] !!}, {!! $demographics['admins'] !!}],
                        backgroundColor: ['#2255FF', '#6366f1', '#e2e8f0'],
                        borderWidth: 0,
                        hoverOffset: 12
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '78%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>
