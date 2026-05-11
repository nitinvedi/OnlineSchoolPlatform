<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('instructor.courses.index') }}"
               class="text-gray-400 hover:text-gray-600 transition">← Back</a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Create New Course</h2>
        </div>
    </x-slot>

    {{-- Quill.js CSS --}}
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <p class="text-red-700 font-semibold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-red-600 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm p-8">
                {{-- IMPORTANT: enctype for file upload --}}
                <form action="{{ route('instructor.courses.store') }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6" id="create-course-form">
                    @csrf

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Course Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}"
                               placeholder="e.g. Complete Python Bootcamp 2025"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('title') border-red-400 @enderror"
                               required>
                        <p id="slug-preview" class="text-xs text-gray-400 mt-1.5"></p>
                        @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Overview --}}
                    <div>
                        <label for="overview" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Short Overview
                            <span class="font-normal text-gray-400">(shown on course cards)</span>
                        </label>
                        <input type="text" id="overview" name="overview" value="{{ old('overview') }}"
                               placeholder="A one-line pitch for your course…"
                               maxlength="500"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('overview') border-red-400 @enderror">
                        @error('overview') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description (Quill.js rich text) --}}
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Full Description <span class="text-red-500">*</span>
                        </label>
                        {{-- Hidden textarea that Quill writes to on submit --}}
                        <input type="hidden" id="description" name="description" value="{{ old('description') }}">
                        <div id="quill-editor" style="min-height: 200px;"
                             class="border border-gray-300 rounded-b-lg @error('description') border-red-400 @enderror">
                        </div>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Category + Status --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select id="category_id" name="category_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400 @error('category_id') border-red-400 @enderror"
                                    required>
                                <option value="">— Select category —</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-semibold text-gray-700 mb-1.5">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="status" name="status"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                                <option value="draft"     {{ old('status', 'draft') === 'draft'     ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Published</option>
                                <option value="archived"  {{ old('status') === 'archived'  ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                    </div>

                    {{-- YouTube Playlist (optional) --}}
                    <div>
                        <label for="youtube_playlist" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            YouTube Playlist URL (optional)
                        </label>
                        <input type="url" id="youtube_playlist" name="youtube_playlist" value="{{ old('youtube_playlist') }}"
                               placeholder="https://www.youtube.com/playlist?list=PL..."
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-sky-400">
                        <p class="text-xs text-gray-400 mt-1">If provided, videos from the playlist will be imported as lessons.</p>
                        @error('youtube_playlist') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Thumbnail Upload --}}
                    <div>
                        <label for="thumbnail" class="block text-sm font-semibold text-gray-700 mb-1.5">
                            Course Thumbnail
                            <span class="font-normal text-gray-400">(optional — JPEG, PNG, or WebP, max 2MB)</span>
                        </label>
                        <div class="flex items-center gap-4">
                            <div id="thumb-preview"
                                 class="w-24 h-16 rounded-lg bg-gradient-to-br from-sky-100 to-blue-200 flex items-center justify-center text-2xl overflow-hidden flex-shrink-0">
                                📚
                            </div>
                            <input type="file" id="thumbnail" name="thumbnail"
                                   accept="image/jpeg,image/png,image/webp,image/gif"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-sky-50 file:text-sky-700 hover:file:bg-sky-100"
                                   onchange="previewThumbnail(this)">
                        </div>
                        @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-4 pt-4 border-t">
                        <a href="{{ route('instructor.courses.index') }}"
                           class="px-5 py-2.5 text-sm text-gray-600 hover:text-gray-900 transition">Cancel</a>
                        <button type="submit"
                                class="px-6 py-2.5 bg-sky-500 text-white text-sm font-semibold rounded-lg hover:bg-sky-600 transition">
                            Create Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Quill.js Script --}}
    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <script>
        // ── Quill Rich Text ──
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
            placeholder: 'What will students learn? Who is this for? What are the prerequisites?',
            modules: {
                toolbar: [
                    [{ header: [2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ list: 'ordered' }, { list: 'bullet' }],
                    ['link'],
                    ['clean'],
                ],
            },
        });

        // Pre-fill Quill if there's old() content (after a validation error)
        const existingContent = document.getElementById('description').value;
        if (existingContent) {
            quill.root.innerHTML = existingContent;
        }

        // On form submit, copy Quill HTML into the hidden input
        const courseForm = document.getElementById('create-course-form');
        courseForm.addEventListener('submit', function (e) {
            // Copy Quill content to hidden input before submission
            const quillContent = quill.root.innerHTML;
            
            // Check if description is empty (only whitespace/empty tags)
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = quillContent;
            const plainText = tempDiv.textContent || tempDiv.innerText || '';
            
            if (!plainText.trim()) {
                e.preventDefault();
                alert('Please enter a description for your course.');
                return false;
            }
            
            document.getElementById('description').value = quillContent;
        });

        // ── Slug Preview ──
        document.getElementById('title').addEventListener('input', function () {
            const slug = this.value.toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '').trim()
                .replace(/\s+/g, '-').replace(/-+/g, '-');
            document.getElementById('slug-preview').textContent = slug ? 'URL: /courses/' + slug : '';
        });

        // ── Thumbnail Preview ──
        function previewThumbnail(input) {
            const preview = document.getElementById('thumb-preview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" alt="Preview">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
