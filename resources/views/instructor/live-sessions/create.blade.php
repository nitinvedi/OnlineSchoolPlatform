<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}" class="text-gray-500 hover:text-gray-900 transition text-sm">← Dashboard</a>
            <span class="text-gray-300">/</span>
            <h2 class="font-bold text-gray-900">New Live Session</h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="text-red-700 font-semibold mb-1 text-sm">Please fix the following:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('instructor.live-sessions.store') }}" method="POST" id="sessionForm" class="space-y-6">
                @csrf
                {{-- Hidden flag for start_now --}}
                <input type="hidden" name="start_now" id="start_now_input" value="0">

                {{-- Mode Toggle --}}
                <div class="bg-white border border-gray-200 rounded-2xl p-1.5 flex gap-1.5">
                    <button type="button" id="btn-start-now"
                        onclick="setMode('now')"
                        class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 bg-[#2255FF] text-white shadow">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3" fill="currentColor"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                        Start Live Now
                    </button>
                    <button type="button" id="btn-schedule"
                        onclick="setMode('schedule')"
                        class="flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 text-gray-500 hover:text-gray-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Schedule for Later
                    </button>
                </div>

                {{-- Mode label --}}
                <div id="mode-label-now" class="flex items-center gap-2 px-1">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-sm font-semibold text-green-700">Session will go live immediately after creation</p>
                </div>
                <div id="mode-label-schedule" class="hidden flex items-center gap-2 px-1">
                    <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                    <p class="text-sm font-semibold text-blue-700">Session will be visible to students but not live until started</p>
                </div>

                <div class="bg-white border border-gray-100 rounded-2xl shadow-sm p-7 space-y-6">

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Session Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g. Q&A — Week 3, Deep Dive: React Hooks"
                               class="w-full px-4 py-2.5 border border-gray-200 bg-white text-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2255FF]/30 focus:border-[#2255FF] @error('title') border-red-400 @enderror"
                               required>
                        @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Course Selection --}}
                    <div>
                        <label for="course_id" class="block text-sm font-semibold text-gray-900 mb-1.5">
                            Course <span class="text-red-500">*</span>
                        </label>
                        <select id="course_id" name="course_id"
                                class="w-full px-4 py-2.5 border border-gray-200 bg-white text-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2255FF]/30 focus:border-[#2255FF] @error('course_id') border-red-400 @enderror"
                                onchange="updateStudentPreview(this)"
                                required>
                            <option value="">— Select a course —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}"
                                        data-students="{{ json_encode($course->enrolledStudents) }}"
                                        {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }} ({{ $course->student_count }} {{ Str::plural('student', $course->student_count) }})
                                </option>
                            @endforeach
                        </select>
                        @error('course_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

                        {{-- Enrolled Students Preview --}}
                        <div id="students-panel" class="hidden mt-3 p-4 bg-blue-50 border border-blue-100 rounded-xl">
                            <p class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-2">
                                Who will see this in their dashboard:
                            </p>
                            <div id="students-list" class="flex flex-wrap gap-2"></div>
                        </div>
                        <div id="no-students-panel" class="hidden mt-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <p class="text-sm text-amber-800 font-medium">No students enrolled yet — the session will appear once students join this course.</p>
                        </div>
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-900 mb-1.5">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea id="description" name="description" rows="2"
                                  placeholder="Topics you'll cover, prerequisites, etc."
                                  class="w-full px-4 py-2.5 border border-gray-200 bg-white text-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2255FF]/30 focus:border-[#2255FF] resize-y">{{ old('description') }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Scheduled Time (hidden in Start Now mode) --}}
                        <div id="scheduled-at-field">
                            <label for="scheduled_at" class="block text-sm font-semibold text-gray-900 mb-1.5">
                                Date & Time <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at"
                                   value="{{ old('scheduled_at', now()->addDay()->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-2.5 border border-gray-200 bg-white text-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2255FF]/30 focus:border-[#2255FF] @error('scheduled_at') border-red-400 @enderror">
                            @error('scheduled_at')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- Duration --}}
                        <div>
                            <label for="max_duration_minutes" class="block text-sm font-semibold text-gray-900 mb-1.5">
                                Duration (minutes) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="max_duration_minutes" name="max_duration_minutes"
                                   value="{{ old('max_duration_minutes', 60) }}" min="15" max="300"
                                   class="w-full px-4 py-2.5 border border-gray-200 bg-white text-gray-900 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#2255FF]/30 focus:border-[#2255FF]"
                                   required>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="flex items-center justify-end gap-4">
                    <a href="{{ route('dashboard') }}" class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-900 transition">Cancel</a>
                    <button type="submit" id="submit-btn"
                            class="px-7 py-2.5 bg-[#2255FF] hover:bg-[#1a44dd] text-white text-sm font-bold rounded-xl transition shadow-sm shadow-blue-200">
                        🔴 Go Live Now
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    let currentMode = 'now';

    function setMode(mode) {
        currentMode = mode;
        const startNowInput = document.getElementById('start_now_input');
        const btnNow = document.getElementById('btn-start-now');
        const btnSchedule = document.getElementById('btn-schedule');
        const labelNow = document.getElementById('mode-label-now');
        const labelSchedule = document.getElementById('mode-label-schedule');
        const scheduledAtField = document.getElementById('scheduled-at-field');
        const scheduledAtInput = document.getElementById('scheduled_at');
        const submitBtn = document.getElementById('submit-btn');

        if (mode === 'now') {
            startNowInput.value = '1';
            btnNow.className = 'flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 bg-[#2255FF] text-white shadow';
            btnSchedule.className = 'flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 text-gray-500 hover:text-gray-700';
            labelNow.classList.remove('hidden');
            labelSchedule.classList.add('hidden');
            scheduledAtField.classList.add('hidden');
            scheduledAtInput.removeAttribute('required');
            submitBtn.textContent = '🔴 Go Live Now';
        } else {
            startNowInput.value = '0';
            btnSchedule.className = 'flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 bg-[#2255FF] text-white shadow';
            btnNow.className = 'flex-1 py-3 px-4 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 text-gray-500 hover:text-gray-700';
            labelSchedule.classList.remove('hidden');
            labelNow.classList.add('hidden');
            scheduledAtField.classList.remove('hidden');
            scheduledAtInput.setAttribute('required', 'required');
            submitBtn.textContent = '📅 Schedule Session';
        }
    }

    function updateStudentPreview(select) {
        const option = select.selectedOptions[0];
        const panel = document.getElementById('students-panel');
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
                `<span class="inline-flex items-center gap-1.5 px-3 py-1 bg-white border border-blue-200 rounded-full text-xs font-semibold text-blue-800 shadow-sm">
                    <span class="w-5 h-5 rounded-full bg-[#2255FF] text-white flex items-center justify-center text-[10px] font-black">${s.name.charAt(0).toUpperCase()}</span>
                    ${s.name}
                </span>`
            ).join('');
            panel.classList.remove('hidden');
        }
    }

    // Init with "Start Now" mode active
    setMode('now');
    </script>
</x-app-layout>
