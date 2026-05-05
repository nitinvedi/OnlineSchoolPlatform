<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('instructor.courses.edit', $course) }}"
               class="text-gray-400 hover:text-gray-600 transition">← Back to Course</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Lesson — <span class="text-sky-600">{{ $lesson->title }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 px-5 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                    ✓ {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-8">

                {{-- Main update form --}}
                <form id="update-lesson-form"
                      action="{{ route('instructor.lessons.update', [$course, $lesson]) }}"
                      method="POST"
                      class="space-y-5"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">Lesson Title <span class="text-red-500">*</span></label>
                        <input type="text" id="title" name="title" value="{{ old('title', $lesson->title) }}"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400" required>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="type" class="block text-sm font-semibold text-gray-700 mb-1.5">Type <span class="text-red-500">*</span></label>
                            <select id="type" name="type" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                                <option value="video"    {{ old('type', $lesson->type) === 'video'    ? 'selected':'' }}>🎬 Video</option>
                                <option value="text"     {{ old('type', $lesson->type) === 'text'     ? 'selected':'' }}>📄 Text</option>
                                <option value="quiz"     {{ old('type', $lesson->type) === 'quiz'     ? 'selected':'' }}>✅ Quiz</option>
                                <option value="resource" {{ old('type', $lesson->type) === 'resource' ? 'selected':'' }}>📎 Resource</option>
                            </select>
                        </div>
                        <div>
                            <label for="order" class="block text-sm font-semibold text-gray-700 mb-1.5">Order</label>
                            <input type="number" id="order" name="order" value="{{ old('order', $lesson->order) }}" min="0"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-semibold text-gray-700 mb-1.5">Short Description</label>
                        <input type="text" id="description" name="description" value="{{ old('description', $lesson->description) }}" maxlength="500"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                    </div>

                    <div id="video-url-field">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Video Content <span class="text-gray-400 font-normal">(URL or Upload)</span></label>
                        <div class="space-y-3">
                            <div>
                                <input type="url" id="video_url" name="video_url" value="{{ old('video_url', $lesson->video_url) }}"
                                       placeholder="https://youtube.com/watch?v=… (Or Vimeo/Direct URL)"
                                       class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                                @error('video_url') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="text-xs text-gray-500 font-semibold uppercase">OR UPLOAD</span>
                                <div class="flex-1 border-t border-gray-200"></div>
                            </div>
                            <div>
                                <input type="file" id="video_file" name="video_file" accept="video/mp4,video/x-m4v,video/*"
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100 transition cursor-pointer border border-gray-200 rounded-lg p-1.5">
                                <p class="text-xs text-gray-400 mt-1">Max 100MB. Uploading a new file replaces the existing video.</p>
                                @error('video_file') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                                @if($lesson->video_url && str_starts_with($lesson->video_url, 'lessons/'))
                                    <p class="text-xs text-green-600 font-semibold mt-1">✓ Currently using an uploaded file.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="content" class="block text-sm font-semibold text-gray-700 mb-1.5">Lesson Content</label>
                        <textarea id="content" name="content" rows="7"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 resize-y">{{ old('content', $lesson->content) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4 items-end">
                        <div>
                            <label for="duration_minutes" class="block text-sm font-semibold text-gray-700 mb-1.5">Duration (minutes)</label>
                            <input type="number" id="duration_minutes" name="duration_minutes"
                                   value="{{ old('duration_minutes', $lesson->duration_minutes) }}" min="1" max="600"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                        </div>
                        <div class="pb-1">
                            <label class="inline-flex items-center gap-3 cursor-pointer">
                                <input type="hidden" name="is_free" value="0">
                                <input type="checkbox" id="is_free" name="is_free" value="1"
                                       {{ old('is_free', $lesson->is_free) ? 'checked':'' }}
                                       class="w-4 h-4 rounded border-gray-300 text-sky-500 focus:ring-sky-400">
                                <span class="text-sm font-semibold text-gray-700">Free Preview</span>
                            </label>
                        </div>
                    </div>

                    {{-- Action row: delete button references external form via form="..." attribute --}}
                    <div class="flex items-center justify-between pt-4 border-t">
                        <button type="submit"
                                form="delete-lesson-form"
                                onclick="return confirm('Permanently delete this lesson? This cannot be undone.')"
                                class="px-4 py-2 text-sm text-red-500 hover:text-red-700 font-medium">
                            Delete Lesson
                        </button>
                        <div class="flex items-center gap-4">
                            <a href="{{ route('instructor.courses.edit', $course) }}"
                               class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-900 transition">Cancel</a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-sky-500 text-white text-sm font-semibold rounded-lg hover:bg-sky-600 transition">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Separate delete form placed OUTSIDE the update form to avoid nested-form issues --}}
                <form id="delete-lesson-form"
                      action="{{ route('instructor.lessons.destroy', [$course, $lesson]) }}"
                      method="POST"
                      style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>

            </div>

            {{-- Quiz Builder Link (only shown for quiz-type lessons) --}}
            @if($lesson->type === 'quiz')
                <div class="mt-6 bg-purple-50 border border-purple-200 rounded-xl p-5 flex items-center justify-between">
                    <div>
                        <h3 class="font-bold text-purple-800">📝 Quiz Builder</h3>
                        <p class="text-sm text-purple-600 mt-0.5">
                            {{ $lesson->quiz ? 'This lesson has a quiz. Click to edit it.' : 'No quiz yet. Build one for your students.' }}
                        </p>
                    </div>
                    <a href="{{ route('instructor.quizzes.edit', [$course, $lesson]) }}"
                       class="px-5 py-2.5 bg-purple-600 text-white font-semibold text-sm rounded-lg hover:bg-purple-700 transition whitespace-nowrap">
                        {{ $lesson->quiz ? 'Edit Quiz' : 'Build Quiz' }}
                    </a>
                </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        const typeSelect = document.getElementById('type');
        const videoField = document.getElementById('video-url-field');
        function toggleVideo() { videoField.style.display = typeSelect.value === 'video' ? 'block' : 'none'; }
        typeSelect.addEventListener('change', toggleVideo);
        toggleVideo();

        tinymce.init({
            selector: '#content',
            menubar: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | bold italic | bullist numlist | link code'
        });
    </script>
</x-app-layout>
