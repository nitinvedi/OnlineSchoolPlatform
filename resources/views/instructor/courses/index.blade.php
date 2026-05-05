<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">My Courses</h2>
            <a href="{{ route('instructor.courses.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-sky-500 text-white text-sm font-semibold rounded-lg hover:bg-sky-600 transition">
                <span class="text-lg leading-none">+</span> New Course
            </a>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash messages --}}
            @if(session('success'))
                <div class="mb-6 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg flex items-center gap-3">
                    <span class="text-xl">✓</span>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                @if($courses->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-gray-50 border-b">
                                <tr>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Course</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Category</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Status</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Students</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Lessons</th>
                                    <th class="px-6 py-4 font-semibold text-gray-600">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($courses as $course)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="font-semibold text-gray-800 line-clamp-1">{{ $course->title }}</div>
                                            <div class="text-xs text-gray-400 mt-0.5">{{ $course->slug }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $course->category->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="inline-block px-2.5 py-0.5 text-xs font-semibold rounded-full
                                                @if($course->status === 'published') bg-green-100 text-green-700
                                                @elseif($course->status === 'draft') bg-yellow-100 text-yellow-700
                                                @else bg-gray-100 text-gray-600 @endif">
                                                {{ ucfirst($course->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-gray-600">{{ $course->student_count }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $course->lessons->count() }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <a href="{{ route('courses.show', $course) }}"
                                                   class="text-sky-600 hover:text-sky-800 font-medium">View</a>
                                                <a href="{{ route('instructor.courses.edit', $course) }}"
                                                   class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                                <form action="{{ route('instructor.courses.destroy', $course) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Delete this course and all its lessons? This cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-500 hover:text-red-700 font-medium">Delete</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="px-6 py-4 border-t">
                        {{ $courses->links() }}
                    </div>
                @else
                    <div class="text-center py-20">
                        <div class="text-5xl mb-4">📚</div>
                        <p class="text-gray-500 text-lg mb-6">You haven't created any courses yet.</p>
                        <a href="{{ route('instructor.courses.create') }}"
                           class="inline-block px-6 py-3 bg-sky-500 text-white font-semibold rounded-lg hover:bg-sky-600 transition">
                            Create Your First Course
                        </a>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
