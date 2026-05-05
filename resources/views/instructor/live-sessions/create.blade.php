<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-gray-400 hover:text-gray-600 transition">← Back to Dashboard</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Schedule Live Class</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-red-700 font-semibold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('instructor.live-sessions.store') }}" method="POST" class="space-y-6">
                @csrf

                <div class="bg-white rounded-xl shadow-sm p-8 space-y-6">
                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Session Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g. Q&A Session — Week 3"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('title') border-red-400 @enderror"
                               required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Course Selection — with enrolled student count --}}
                    <div>
                        <label for="course_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Course <span class="text-red-500">*</span>
                            <span class="font-normal text-gray-400 text-xs">(only students enrolled in this course will see the session)</span>
                        </label>
                        <select id="course_id" name="course_id"
                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('course_id') border-red-400 @enderror"
                                onchange="showEnrolledStudents(this)"
                                required>
                            <option value="">— Select a course —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                        data-students="{{ json_encode($course->enrolledStudents) }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                    ({{ $course->student_count }} {{ Str::plural('student', $course->student_count) }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror

                        {{-- Enrolled Students Panel (populated via JS) --}}
                        <div id="enrolled-students-panel" class="hidden mt-3 p-4 bg-sky-50 border border-sky-100 rounded-lg">
                            <p class="text-xs font-semibold text-sky-700 mb-2">👤 Students who will receive this session:</p>
                            <div id="students-list" class="flex flex-wrap gap-2"></div>
                        </div>

                        <div id="no-students-panel" class="hidden mt-3 p-4 bg-amber-50 border border-amber-100 rounded-lg">
                            <p class="text-sm text-amber-700">⚠️ No students are enrolled in this course yet. The session will still be created, but no one will see it until students enroll.</p>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Description</label>
                        <textarea id="description" name="description" rows="3"
                                  placeholder="What topics will you cover in this session?"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 resize-y">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Scheduled Time --}}
                        <div>
                            <label for="scheduled_at" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                                   value="{{ old('scheduled_at', now()->addDay()->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('scheduled_at') border-red-400 @enderror"
                                   required>
                            @error('scheduled_at') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Duration --}}
                        <div>
                            <label for="max_duration_minutes" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Duration (minutes) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="max_duration_minutes" name="max_duration_minutes"
                                   value="{{ old('max_duration_minutes', 60) }}" min="15" max="300"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-900 transition">Cancel</a>
                    <button type="submit"
                            class="px-6 py-2.5 bg-red-500 text-white text-sm font-semibold rounded-lg hover:bg-red-600 transition shadow">
                        🔴 Schedule Session
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function showEnrolledStudents(select) {
        const option = select.selectedOptions[0];
        const panel = document.getElementById('enrolled-students-panel');
        const noStudents = document.getElementById('no-students-panel');
        const list = document.getElementById('students-list');

        if (!option || !option.dataset.students) {
            panel.classList.add('hidden');
            noStudents.classList.add('hidden');
            return;
        }

        const students = JSON.parse(option.dataset.students);

        if (students.length === 0) {
            panel.classList.add('hidden');
            noStudents.classList.remove('hidden');
        } else {
            noStudents.classList.add('hidden');
            list.innerHTML = students.map(s =>
                `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-sky-100 text-sky-800">
                    ${s.name}
                 </span>`
            ).join('');
            panel.classList.remove('hidden');
        }
    }
    </script>
</x-app-layout>
