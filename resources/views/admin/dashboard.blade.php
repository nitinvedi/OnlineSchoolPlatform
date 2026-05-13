<x-admin-layout>
<div class="space-y-6">

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Total Users</p>
            <p class="text-3xl font-black text-gray-900">{{ number_format($totalUsers) }}</p>
            <p class="text-xs text-gray-400 mt-1">+{{ $newUsersThisMonth }} this month</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Total Courses</p>
            <p class="text-3xl font-black text-gray-900">{{ number_format($totalCourses) }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $publishedCourses }} published</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Enrollments</p>
            <p class="text-3xl font-black text-gray-900">{{ number_format($totalEnrollments) }}</p>
            <p class="text-xs text-gray-400 mt-1">All time</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs font-semibold uppercase tracking-widest text-gray-400 mb-1">Revenue</p>
            <p class="text-3xl font-black text-gray-900">${{ number_format($totalRevenue, 0) }}</p>
            <p class="text-xs text-gray-400 mt-1">Completed payments</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 {{ $pendingApprovals > 0 ? 'border-amber-200 bg-amber-50' : '' }}">
            <p class="text-xs font-semibold uppercase tracking-widest {{ $pendingApprovals > 0 ? 'text-amber-500' : 'text-gray-400' }} mb-1">Pending Review</p>
            <p class="text-3xl font-black {{ $pendingApprovals > 0 ? 'text-amber-700' : 'text-gray-900' }}">{{ $pendingApprovals }}</p>
            <p class="text-xs {{ $pendingApprovals > 0 ? 'text-amber-500' : 'text-gray-400' }} mt-1">Courses awaiting approval</p>
        </div>
    </div>

    {{-- Main Content Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Enrollments Table (Real Data) --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-50 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-900">Recent Enrollments</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-700">View all users &rarr;</a>
            </div>
            @if($recentEnrollments->count() > 0)
                <div class="divide-y divide-gray-50">
                    @foreach($recentEnrollments as $enrollment)
                        <div class="px-6 py-3 flex items-center justify-between hover:bg-gray-50/50 transition">
                            <div class="flex items-center gap-3 min-w-0">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-black flex-shrink-0">
                                    {{ strtoupper(substr($enrollment->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $enrollment->user->name ?? 'Deleted User' }}</p>
                                    <p class="text-xs text-gray-400 truncate">{{ $enrollment->course->title ?? 'Deleted Course' }}</p>
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-4">{{ $enrollment->created_at->diffForHumans() }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-16 text-center text-sm text-gray-400 font-medium">No enrollments yet.</div>
            @endif
        </div>

        {{-- Action Panel --}}
        <div class="flex flex-col gap-4">

            {{-- Pending Approvals --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Action Required</h3>
                @if($pendingApprovals > 0)
                    <div class="rounded-lg bg-amber-50 border border-amber-200 p-4">
                        <p class="text-sm font-bold text-amber-900">{{ $pendingApprovals }} course{{ $pendingApprovals > 1 ? 's' : '' }} pending review</p>
                        <p class="text-xs text-amber-700 mt-1">Courses submitted by instructors waiting for approval.</p>
                        <a href="{{ route('admin.courses.index', ['status' => 'pending']) }}" class="mt-3 inline-flex items-center justify-center w-full py-2 px-3 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg transition">
                            Review Now &rarr;
                        </a>
                    </div>
                @else
                    <div class="rounded-lg bg-green-50 border border-green-100 p-4">
                        <p class="text-sm font-semibold text-green-800">All clear — no pending approvals.</p>
                    </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="text-sm font-bold text-gray-900 mb-3">Quick Actions</h3>
                <div class="space-y-2">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 border border-gray-100 transition group">
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Manage Users</span>
                        <span class="text-xs font-bold text-gray-400">{{ number_format($totalUsers) }} total</span>
                    </a>
                    <a href="{{ route('admin.courses.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 border border-gray-100 transition group">
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Manage Courses</span>
                        <span class="text-xs font-bold text-gray-400">{{ number_format($totalCourses) }} total</span>
                    </a>
                    <a href="{{ route('admin.revenue.index') }}" class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 border border-gray-100 transition group">
                        <span class="text-sm font-semibold text-gray-700 group-hover:text-gray-900">Revenue Report</span>
                        <span class="text-xs font-bold text-green-600">${{ number_format($totalRevenue, 0) }}</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

</div>
</x-admin-layout>