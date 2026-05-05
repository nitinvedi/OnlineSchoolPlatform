<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-12 lg:py-16">
        
        {{-- Header & Global Actions --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
            <div>
                <h1 class="text-4xl font-display font-black text-white tracking-tighter flex items-center gap-4">
                    <div class="w-10 h-10 bg-brand-500 rounded-2xl flex items-center justify-center shadow-lg shadow-brand-500/20">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    Command <span class="text-brand-500">Center</span>
                </h1>
                <p class="mt-2 text-slate-500 font-medium text-lg">System infrastructure, high-level metrics, and global governance.</p>
            </div>
            
            <div class="relative w-full md:w-96 group">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-slate-500 group-focus-within:text-brand-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" placeholder="Search system resources..." class="block w-full pl-12 pr-16 py-4 bg-dark-card border border-white/5 rounded-2xl text-slate-300 placeholder-slate-600 focus:outline-none focus:ring-1 focus:ring-brand-500 focus:border-brand-500 transition-all text-sm font-bold uppercase tracking-widest">
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                    <kbd class="inline-flex items-center border border-white/10 rounded-lg px-2 py-1 text-[10px] font-black text-slate-500 bg-white/5 uppercase tracking-widest">Ctrl K</kbd>
                </div>
            </div>
        </div>

        {{-- System Health & Quick Config --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 mb-8">
            <div class="lg:col-span-3 bg-dark-card border border-white/5 rounded-[2.5rem] p-8 flex flex-wrap md:flex-nowrap items-center gap-8 group hover:border-brand-500/30 transition-colors">
                <div class="flex items-center gap-4 border-r border-white/5 pr-8">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-500 shadow-lg shadow-emerald-500/5">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest mb-1">Infrastructure</p>
                        <p class="text-emerald-500 font-black text-lg">OPERATIONAL</p>
                    </div>
                </div>
                
                <div class="flex-1 min-w-[150px]">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-3"><span class="text-slate-500">CPU Load</span><span class="text-white">24%</span></div>
                    <div class="w-full bg-white/5 rounded-full h-1.5"><div class="bg-brand-500 h-1.5 rounded-full" style="width: 24%"></div></div>
                </div>
                
                <div class="flex-1 min-w-[150px]">
                    <div class="flex justify-between text-[10px] font-black uppercase tracking-widest mb-3"><span class="text-slate-500">Node Memory</span><span class="text-white">4.2 / 16 GB</span></div>
                    <div class="w-full bg-white/5 rounded-full h-1.5"><div class="bg-brand-500 h-1.5 rounded-full" style="width: 26%"></div></div>
                </div>
            </div>

            <div class="bg-dark-card border border-white/5 rounded-[2.5rem] p-8 flex flex-col justify-center gap-4 group hover:border-brand-500/30 transition-colors">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Registrations</span>
                    <button class="w-10 h-5 bg-brand-500 rounded-full relative transition-colors duration-300">
                        <div class="w-3.5 h-3.5 bg-white rounded-full absolute right-0.5 top-0.5 shadow-sm"></div>
                    </button>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-xs font-black text-slate-500 uppercase tracking-widest">Maintenance</span>
                    <button class="w-10 h-5 bg-white/10 rounded-full relative transition-colors duration-300">
                        <div class="w-3.5 h-3.5 bg-white rounded-full absolute left-0.5 top-0.5 shadow-sm"></div>
                    </button>
                </div>
            </div>
        </div>

        {{-- Metrics Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
            @php
                $metrics = [
                    ['label' => 'Global Users', 'value' => $stats['totalUsers'], 'growth' => $mom['users'], 'color' => 'brand'],
                    ['label' => 'Net Revenue', 'value' => '$'.number_format($stats['totalRevenue']), 'growth' => $mom['revenue'], 'color' => 'brand'],
                    ['label' => 'Active Courses', 'value' => $stats['totalCourses'], 'growth' => $mom['courses'], 'color' => 'brand'],
                    ['label' => 'Enrollments', 'value' => $stats['totalEnrollments'], 'growth' => $mom['enrollments'], 'color' => 'brand'],
                ];
            @endphp
            
            @foreach($metrics as $metric)
                <div class="bg-dark-card border border-white/5 rounded-[2.5rem] p-8 relative overflow-hidden group hover:border-brand-500/30 transition-colors">
                    <p class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] mb-4">{{ $metric['label'] }}</p>
                    <div class="flex items-end gap-3 mb-2">
                        <h3 class="text-4xl font-black text-white tracking-tighter">{{ $metric['value'] }}</h3>
                        <span class="px-2 py-1 bg-emerald-500/10 text-emerald-500 text-[10px] font-black rounded-lg border border-emerald-500/20 mb-1">{{ $metric['growth'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Analytics Area --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <div class="lg:col-span-2 bg-dark-card border border-white/5 rounded-[3rem] p-10 flex flex-col min-h-[450px] group hover:border-brand-500/30 transition-colors">
                <div class="flex justify-between items-center mb-10">
                    <div>
                        <h3 class="text-xl font-bold text-white mb-1">Financial Trajectory</h3>
                        <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest">Platform-wide Revenue Aggregate</p>
                    </div>
                </div>
                <div class="flex-1 relative">
                    <canvas id="adminRevenueChart"></canvas>
                </div>
            </div>

            <div class="bg-dark-card border border-white/5 rounded-[3rem] p-10 flex flex-col justify-between min-h-[450px] group hover:border-brand-500/30 transition-colors">
                <h3 class="text-xl font-bold text-white mb-8">User Topology</h3>
                <div class="relative flex-1 flex justify-center items-center">
                    <canvas id="roleDistributionChart"></canvas>
                </div>
                <div class="mt-10 space-y-4">
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3"><div class="w-2 h-2 rounded-full bg-brand-500"></div><span class="text-xs font-black text-slate-500 uppercase tracking-widest">Students</span></div>
                        <span class="text-white font-black">{{ number_format($demographics['students']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3"><div class="w-2 h-2 rounded-full bg-indigo-500"></div><span class="text-xs font-black text-slate-500 uppercase tracking-widest">Instructors</span></div>
                        <span class="text-white font-black">{{ number_format($demographics['instructors']) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex items-center gap-3"><div class="w-2 h-2 rounded-full bg-white/20"></div><span class="text-xs font-black text-slate-500 uppercase tracking-widest">Admins</span></div>
                        <span class="text-white font-black">{{ number_format($demographics['admins']) }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lower Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- Approvals --}}
            <div class="bg-dark-card border border-white/5 rounded-[3rem] p-10 group hover:border-brand-500/30 transition-colors">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-xl font-bold text-white">Governance</h3>
                    <span class="px-3 py-1 bg-brand-500 text-white text-[10px] font-black rounded-lg uppercase tracking-widest">{{ $pendingApprovals->count() }} PENDING</span>
                </div>
                
                <div class="space-y-4">
                    @forelse($pendingApprovals as $user)
                        <div class="bg-white/5 rounded-[1.5rem] p-6 border border-white/5 flex justify-between items-center group/item hover:bg-white/10 transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-brand-500 flex items-center justify-center text-white font-black text-xs">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-white text-sm">{{ $user->name }}</p>
                                    <p class="text-[10px] font-black text-brand-500 uppercase tracking-widest">Instructor Application</p>
                                </div>
                            </div>
                            <button class="w-8 h-8 bg-emerald-500 rounded-lg text-white flex items-center justify-center hover:bg-emerald-600 transition-colors shadow-lg shadow-emerald-500/20">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            </button>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-10 opacity-40">
                            <svg class="w-12 h-12 text-slate-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-xs font-black uppercase tracking-widest">Queue Empty</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Activity --}}
            <div class="bg-dark-card border border-white/5 rounded-[3rem] p-10 group hover:border-brand-500/30 transition-colors">
                <h3 class="text-xl font-bold text-white mb-10 flex items-center gap-3">
                    <span class="flex h-2 w-2 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Real-time Signal
                </h3>
                
                <div class="relative border-l-2 border-white/5 ml-2 space-y-8">
                    @php
                        $activities = [
                            ['msg' => 'New student onboarding', 'time' => '2m ago', 'color' => 'bg-brand-500'],
                            ['msg' => 'Advanced PHP course published', 'time' => '15m ago', 'color' => 'bg-emerald-500'],
                            ['msg' => 'Premium enrollment spike', 'time' => '1h ago', 'color' => 'bg-indigo-500'],
                            ['msg' => 'Cloud backup synchronized', 'time' => '3h ago', 'color' => 'bg-white/20'],
                        ];
                    @endphp
                    
                    @foreach($activities as $act)
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-0 w-4 h-4 {{ $act['color'] }} rounded-full border-4 border-dark-bg"></div>
                            <p class="text-sm font-bold text-white leading-tight">{{ $act['msg'] }}</p>
                            <p class="text-[10px] font-black text-slate-600 uppercase tracking-widest mt-1">{{ $act['time'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Categories --}}
            <div class="bg-dark-card border border-white/5 rounded-[3rem] p-10 group hover:border-brand-500/30 transition-colors">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-xl font-bold text-white">Domain Focus</h3>
                    <a href="#" class="text-[10px] font-black text-brand-500 hover:text-brand-400 uppercase tracking-widest transition-colors">All Spheres</a>
                </div>
                
                <div class="space-y-6">
                    @foreach($categories as $category)
                        <div class="group/cat cursor-pointer">
                            <div class="flex justify-between mb-2">
                                <h4 class="text-sm font-bold text-white group-hover/cat:text-brand-500 transition-colors">{{ $category->name }}</h4>
                                <span class="text-[10px] font-black text-slate-600 uppercase tracking-widest">{{ $category->courses_count }} courses</span>
                            </div>
                            @php $percent = $stats['totalCourses'] > 0 ? ($category->courses_count / $stats['totalCourses']) * 100 : 0; @endphp
                            <div class="w-full bg-white/5 rounded-full h-1">
                                <div class="bg-brand-500 h-1 rounded-full group-hover/cat:bg-white transition-all duration-700" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const revCtx = document.getElementById('adminRevenueChart').getContext('2d');
            let gradRev = revCtx.createLinearGradient(0, 0, 0, 400);
            gradRev.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
            gradRev.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Revenue',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: gradRev,
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

            const roleCtx = document.getElementById('roleDistributionChart').getContext('2d');
            new Chart(roleCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Students', 'Instructors', 'Admins'],
                    datasets: [{
                        data: [{!! $demographics['students'] !!}, {!! $demographics['instructors'] !!}, {!! $demographics['admins'] !!}],
                        backgroundColor: ['#3b82f6', '#6366f1', '#27272a'],
                        borderWidth: 0,
                        hoverOffset: 20
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '80%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>
