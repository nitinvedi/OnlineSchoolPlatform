<x-admin-layout>
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">Courses</h2>
            <div class="flex space-x-2">
                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Export CSV</button>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex-1 min-w-0">
                        <form method="GET" action="{{ route('admin.courses.index') }}" class="flex space-x-4">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search courses..." class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Instructor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Enrollments</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($courses as $course)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            <img class="h-12 w-12 rounded-lg object-cover" src="{{ $course->thumbnail_url ?? 'https://via.placeholder.com/150' }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $course->title }}</div>
                                            <div class="text-sm text-gray-500">{{ $course->category->name ?? 'No Category' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $course->instructor->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $course->instructor->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                        @if($course->status === 'published') bg-green-100 text-green-800 
                                        @elseif($course->status === 'pending') bg-yellow-100 text-yellow-800 
                                        @elseif($course->status === 'draft') bg-gray-100 text-gray-800 
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($course->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $course->enrollments_count ?? 0 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    ${{ number_format($course->price, 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end space-x-2">
                                        <a href="{{ route('admin.courses.show', $course) }}" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        @if($course->status === 'pending')
                                            <form method="POST" action="{{ route('admin.courses.approve', $course) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.courses.reject', $course) }}" class="inline" onsubmit="return confirm('Are you sure you want to reject this course?');">
                                                @csrf
                                                <input type="hidden" name="reason" value="Does not meet platform quality standards.">
                                                <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                            </form>
                                        @elseif($course->status === 'published')
                                            <form method="POST" action="{{ route('admin.courses.unpublish', $course) }}" class="inline">
                                                @csrf
                                                <button type="submit" class="text-gray-600 hover:text-gray-900">Unpublish</button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.courses.feature', $course) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-purple-600 hover:text-purple-900">
                                                {{ $course->is_featured ? 'Unfeature' : 'Feature' }}
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200">
                {{ $courses->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>