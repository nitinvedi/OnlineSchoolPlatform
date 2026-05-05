<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Dark Command Center Theme Override for Admin --}}
    <style>
        .admin-theme { background-color: #0f172a; color: #f8fafc; min-height: 100vh; }
        .admin-card { background-color: #1e293b; border-color: #334155; }
        .admin-text-muted { color: #94a3b8; }
        .admin-accent { color: #38bdf8; }
        .admin-bg-accent { background-color: #0284c7; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="admin-theme -mt-8 pt-8 pb-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            
            {{-- Header & Global Search Palette --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-white tracking-tight flex items-center gap-3">
                        <svg class="w-8 h-8 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        Command Center
                    </h1>
                    <p class="mt-1 admin-text-muted font-medium">System overview, metrics, and administration.</p>
                </div>
                
                {{-- Global Search Command Palette (Mock UI) --}}
                <div class="relative w-full md:w-96 group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 admin-text-muted group-focus-within:text-sky-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" placeholder="Search users, courses, transactions..." class="block w-full pl-10 pr-16 py-2.5 border border-slate-700 rounded-xl leading-5 bg-slate-800 text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-1 focus:ring-sky-500 focus:border-sky-500 sm:text-sm transition-all shadow-inner">
                    <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
                        <kbd class="inline-flex items-center border border-slate-600 rounded px-2 text-xs font-sans font-medium text-slate-400 bg-slate-700">Ctrl K</kbd>
                    </div>
                </div>
            </div>

            {{-- System Health & Quick Config --}}
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
                
                {{-- System Health Meters --}}
                <div class="lg:col-span-3 admin-card rounded-2xl p-5 border flex flex-wrap md:flex-nowrap items-center gap-6 shadow-lg shadow-black/20">
                    <div class="flex items-center gap-3 border-r border-slate-700 pr-6">
                        <div class="w-12 h-12 rounded-full bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs admin-text-muted font-bold uppercase tracking-wider mb-0.5">System Status</p>
                            <p class="text-emerald-400 font-bold">All Systems Operational</p>
                        </div>
                    </div>
                    
                    <div class="flex-1 min-w-[150px]">
                        <div class="flex justify-between text-xs font-bold mb-1"><span class="admin-text-muted">CPU Load</span><span class="text-white">24%</span></div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5"><div class="bg-sky-400 h-1.5 rounded-full" style="width: 24%"></div></div>
                    </div>
                    
                    <div class="flex-1 min-w-[150px]">
                        <div class="flex justify-between text-xs font-bold mb-1"><span class="admin-text-muted">Memory</span><span class="text-white">4.2 / 16 GB</span></div>
                        <div class="w-full bg-slate-800 rounded-full h-1.5"><div class="bg-indigo-400 h-1.5 rounded-full" style="width: 26%"></div></div>
                    </div>
                </div>

                {{-- Quick Config Toggles --}}
                <div class="admin-card rounded-2xl p-5 border shadow-lg shadow-black/20 flex flex-col justify-center">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-sm font-bold text-slate-300">Allow Registrations</span>
                        <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-sky-500 transition-colors duration-200 ease-in-out focus:outline-none" role="switch" aria-checked="true">
                            <span class="translate-x-5 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-bold text-slate-300">Maintenance Mode</span>
                        <button class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent bg-slate-600 transition-colors duration-200 ease-in-out focus:outline-none" role="switch" aria-checked="false">
                            <span class="translate-x-0 pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                        </button>
                    </div>
                </div>
            </div>

            {{-- KPI Pill Badges & Metrics Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="admin-card rounded-2xl p-6 border relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 transform group-hover:scale-110 transition duration-500">
                        <svg class="w-16 h-16 text-sky-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    </div>
                    <p class="text-xs admin-text-muted font-bold uppercase tracking-wider mb-2">Total Users</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-3xl font-black text-white">{{ number_format($stats['totalUsers']) }}</h3>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 mb-1">{{ $mom['users'] }}</span>
                    </div>
                </div>
                
                <div class="admin-card rounded-2xl p-6 border relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 transform group-hover:scale-110 transition duration-500">
                        <svg class="w-16 h-16 text-indigo-400" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <p class="text-xs admin-text-muted font-bold uppercase tracking-wider mb-2">Total Revenue</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-3xl font-black text-white">${{ number_format($stats['totalRevenue']) }}</h3>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 mb-1">{{ $mom['revenue'] }}</span>
                    </div>
                </div>

                <div class="admin-card rounded-2xl p-6 border relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 transform group-hover:scale-110 transition duration-500">
                        <svg class="w-16 h-16 text-amber-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"></path></svg>
                    </div>
                    <p class="text-xs admin-text-muted font-bold uppercase tracking-wider mb-2">Total Courses</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-3xl font-black text-white">{{ number_format($stats['totalCourses']) }}</h3>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 mb-1">{{ $mom['courses'] }}</span>
                    </div>
                </div>

                <div class="admin-card rounded-2xl p-6 border relative overflow-hidden group">
                    <div class="absolute top-0 right-0 p-4 opacity-10 transform group-hover:scale-110 transition duration-500">
                        <svg class="w-16 h-16 text-emerald-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    </div>
                    <p class="text-xs admin-text-muted font-bold uppercase tracking-wider mb-2">Enrollments</p>
                    <div class="flex items-end gap-3">
                        <h3 class="text-3xl font-black text-white">{{ number_format($stats['totalEnrollments']) }}</h3>
                        <span class="px-2 py-0.5 rounded text-xs font-bold bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 mb-1">{{ $mom['enrollments'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Main Charts Area --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- Stacked Area Revenue Chart --}}
                <div class="lg:col-span-2 admin-card rounded-2xl p-6 border flex flex-col min-h-[350px]">
                    <h3 class="text-lg font-bold text-white mb-6">Platform Revenue Over Time</h3>
                    <div class="flex-1 relative">
                        <canvas id="adminRevenueChart"></canvas>
                    </div>
                </div>

                {{-- Demographic / Role Distribution Doughnut --}}
                <div class="admin-card rounded-2xl p-6 border flex flex-col justify-between min-h-[350px]">
                    <h3 class="text-lg font-bold text-white mb-4">User Distribution</h3>
                    <div class="relative flex-1 flex justify-center items-center">
                        <canvas id="roleDistributionChart" class="max-h-[220px]"></canvas>
                    </div>
                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-sky-400"></div><span class="text-slate-300">Students</span></div>
                            <span class="text-white font-bold">{{ $demographics['students'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-indigo-500"></div><span class="text-slate-300">Instructors</span></div>
                            <span class="text-white font-bold">{{ $demographics['instructors'] }}</span>
                        </div>
                        <div class="flex justify-between items-center text-sm">
                            <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-slate-500"></div><span class="text-slate-300">Admins</span></div>
                            <span class="text-white font-bold">{{ $demographics['admins'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bottom Layout: Queues, Activities, Categories --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Swipe-Action Approval Queue --}}
                <div class="admin-card rounded-2xl p-6 border flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-white">Pending Approvals</h3>
                        <span class="px-2 py-0.5 bg-rose-500 text-white text-xs font-bold rounded-full">{{ $pendingApprovals->count() }}</span>
                    </div>
                    
                    <div class="space-y-4 flex-1">
                        @forelse($pendingApprovals as $user)
                            <div class="bg-slate-800 rounded-xl p-4 border border-slate-700 flex justify-between items-center group overflow-hidden relative">
                                <div class="flex items-center gap-3 relative z-10 bg-slate-800 w-full transition-transform duration-300 group-hover:-translate-x-12">
                                    <div class="w-10 h-10 rounded-full bg-slate-700 flex items-center justify-center text-white font-bold">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-white text-sm">{{ $user->name }}</p>
                                        <p class="text-xs text-slate-400">Wants to be an Instructor</p>
                                    </div>
                                </div>
                                {{-- Swipe actions revealed on hover --}}
                                <div class="absolute right-0 top-0 bottom-0 flex">
                                    <button class="w-12 bg-emerald-500 text-white flex items-center justify-center hover:bg-emerald-600 transition" title="Approve">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center h-full text-center p-6">
                                <svg class="w-12 h-12 text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <p class="text-slate-400 text-sm font-medium">All caught up! No pending approvals.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Real-time Activity Ticker --}}
                <div class="admin-card rounded-2xl p-6 border flex flex-col">
                    <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                        </span>
                        Activity Feed
                    </h3>
                    
                    <div class="relative border-l border-slate-700 ml-3 space-y-6">
                        {{-- Mocking activities for demo --}}
                        @php
                            $activities = [
                                ['type' => 'user', 'msg' => 'New user registered', 'time' => '2 mins ago', 'color' => 'bg-sky-500', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                                ['type' => 'course', 'msg' => 'Course published', 'time' => '15 mins ago', 'color' => 'bg-emerald-500', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['type' => 'enroll', 'msg' => 'New enrollment in PHP Masterclass', 'time' => '1 hour ago', 'color' => 'bg-indigo-500', 'icon' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'],
                                ['type' => 'system', 'msg' => 'Daily backup completed', 'time' => '3 hours ago', 'color' => 'bg-slate-500', 'icon' => 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'],
                            ];
                        @endphp
                        
                        @foreach($activities as $act)
                            <div class="relative pl-6">
                                <div class="absolute -left-3 top-0 w-6 h-6 {{ $act['color'] }} rounded-full flex items-center justify-center ring-4 ring-slate-900">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $act['icon'] }}"></path></svg>
                                </div>
                                <p class="text-sm font-bold text-slate-200">{{ $act['msg'] }}</p>
                                <p class="text-xs admin-text-muted mt-0.5">{{ $act['time'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Top Categories Grid --}}
                <div class="admin-card rounded-2xl p-6 border flex flex-col">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-white">Top Categories</h3>
                        <a href="#" class="text-xs font-bold text-sky-400 hover:text-sky-300">View All</a>
                    </div>
                    
                    <div class="space-y-4">
                        @foreach($categories as $category)
                            <div class="flex items-center gap-4 group cursor-pointer">
                                <div class="w-12 h-12 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center group-hover:border-sky-500 transition-colors">
                                    <svg class="w-6 h-6 text-slate-400 group-hover:text-sky-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                </div>
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <h4 class="text-sm font-bold text-slate-200">{{ $category->name }}</h4>
                                        <span class="text-xs font-bold text-slate-400">{{ $category->courses_count }} courses</span>
                                    </div>
                                    @php $percent = $stats['totalCourses'] > 0 ? ($category->courses_count / $stats['totalCourses']) * 100 : 0; @endphp
                                    <div class="w-full bg-slate-800 rounded-full h-1">
                                        <div class="bg-indigo-400 h-1 rounded-full" style="width: {{ $percent }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dark Mode Revenue Chart
            const revCtx = document.getElementById('adminRevenueChart').getContext('2d');
            let gradRev = revCtx.createLinearGradient(0, 0, 0, 300);
            gradRev.addColorStop(0, 'rgba(56, 189, 248, 0.3)'); // sky-400
            gradRev.addColorStop(1, 'rgba(56, 189, 248, 0)');

            new Chart(revCtx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Total Revenue ($)',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#38bdf8',
                        backgroundColor: gradRev,
                        borderWidth: 2,
                        pointBackgroundColor: '#0f172a',
                        pointBorderColor: '#38bdf8',
                        pointBorderWidth: 2,
                        pointRadius: 3,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: '#64748b' } },
                        y: { grid: { color: '#1e293b', drawBorder: false }, ticks: { color: '#64748b', callback: function(val) { return '$' + val; } } }
                    }
                }
            });

            // Doughnut Chart
            const roleCtx = document.getElementById('roleDistributionChart').getContext('2d');
            new Chart(roleCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Students', 'Instructors', 'Admins'],
                    datasets: [{
                        data: [{!! $demographics['students'] !!}, {!! $demographics['instructors'] !!}, {!! $demographics['admins'] !!}],
                        backgroundColor: ['#38bdf8', '#6366f1', '#64748b'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        });
    </script>
</x-app-layout>
