<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Reported Content</h2>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1 min-w-0">
                        <form method="GET" action="{{ route('admin.reports.index') }}" class="flex space-x-4">
                            <select name="type" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Types</option>
                                <option value="review" {{ request('type') === 'review' ? 'selected' : '' }}>Review</option>
                                <option value="course" {{ request('type') === 'course' ? 'selected' : '' }}>Course</option>
                                <option value="user" {{ request('type') === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dismissed" {{ request('status') === 'dismissed' ? 'selected' : '' }}>Dismissed</option>
                                <option value="removed" {{ request('status') === 'removed' ? 'selected' : '' }}>Removed</option>
                                <option value="warned" {{ request('status') === 'warned' ? 'selected' : '' }}>Warned</option>
                                <option value="banned" {{ request('status') === 'banned' ? 'selected' : '' }}>Banned</option>
                            </select>
                            <button type="submit" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Filter</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Report</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reporter</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($reports as $report)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #{{ $report->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $report->reporter->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $report->reporter->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ ucfirst($report->type) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs truncate">{{ $report->reason }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($report->status === 'pending') bg-yellow-100 text-yellow-800 
                                        @elseif($report->status === 'dismissed') bg-gray-100 text-gray-800 
                                        @elseif($report->status === 'removed') bg-red-100 text-red-800 
                                        @elseif($report->status === 'warned') bg-orange-100 text-orange-800 
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($report->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        @if($report->status === 'pending')
                                            <form method="POST" action="{{ route('admin.reports.dismiss', $report) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-gray-600 hover:text-gray-900">Dismiss</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reports.remove-content', $report) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Remove Content</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reports.warn-user', $report) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-yellow-600 hover:text-yellow-900">Warn User</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.reports.ban-user', $report) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-red-600 hover:text-red-900">Ban User</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $reports->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>