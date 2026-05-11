<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        .builder-theme { background-color: #f8fafc; min-height: 100vh; }
        .glass-panel { background: rgba(255, 255, 255, 0.9); backdrop-filter: blur(16px); border: 1px solid rgba(226, 232, 240, 0.8); }
        .ql-toolbar { border-radius: 0.75rem 0.75rem 0 0 !important; border-color: #e2e8f0 !important; background: #f8fafc; }
        .ql-container { border-radius: 0 0 0.75rem 0.75rem !important; border-color: #e2e8f0 !important; font-family: inherit !important; font-size: 1rem !important; }
        .ql-editor { min-height: 200px; padding: 1.5rem !important; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Custom Toggle Switch */
        .toggle-checkbox:checked { right: 0; border-color: #10b981; }
        .toggle-checkbox:checked + .toggle-label { background-color: #10b981; }
        .toggle-checkbox:checked + .toggle-label:after { transform: translateX(100%); border-color: white; }
    </style>

    <div class="builder-theme pb-32">
        
        {{-- Sticky Header --}}
        <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <a href="{{ route('instructor.courses.index') }}" class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-slate-200 hover:text-slate-900 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </a>
                    <h1 class="font-black text-xl text-slate-900 truncate max-w-xs sm:max-w-md">{{ $course->title }}</h1>
                    <span class="px-2.5 py-1 text-xs font-bold uppercase tracking-wider rounded-md {{ $course->status === 'published' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                        {{ $course->status }}
                    </span>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('courses.show', $course) }}" target="_blank" class="hidden sm:flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-sky-500 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        Preview
                    </a>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            @if(session('success'))
                <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between animate-[fade-in_0.5s_ease-out]">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="font-bold text-emerald-800">{{ session('success') }}</span>
                    </div>
                </div>
                {{-- Floating toast (ensures visibility even when page doesn't scroll) --}}
                <div id="floating-success" data-message="{{ session('success') }}" class="hidden"></div>
            @endif

            @if($errors->any())
                <div class="mb-8 p-6 bg-rose-50 border border-rose-200 rounded-2xl animate-[shake_0.5s_ease-in-out]">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-8 h-8 bg-rose-100 text-rose-600 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="font-black text-rose-800 text-lg">Please fix these errors</h3>
                    </div>
                    <ul class="list-disc list-inside text-rose-600 font-medium space-y-1 ml-11">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form id="update-course-form" action="{{ route('instructor.courses.update', $course) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                    
                    {{-- Left Column: Main Editing Area --}}
                    <div class="xl:col-span-2 space-y-8">
                        
                        {{-- Basic Info Card --}}
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </div>
                                <h2 class="text-2xl font-black text-slate-900">Basic Information</h2>
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="title" class="block text-sm font-bold text-slate-700 mb-2">Course Title</label>
                                    <input type="text" id="title" name="title" value="{{ old('title', $course->title) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-900 font-bold focus:bg-white focus:border-sky-500 focus:ring-0 transition" required>
                                </div>

                                <div>
                                    <label for="overview" class="block text-sm font-bold text-slate-700 mb-2">Short Overview (Subtitle)</label>
                                    <textarea id="overview" name="overview" rows="2" maxlength="500" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-900 focus:bg-white focus:border-sky-500 focus:ring-0 transition">{{ old('overview', $course->overview) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-slate-700 mb-2">Full Description</label>
                                    <input type="hidden" id="description" name="description" value="{{ old('description', $course->description) }}">
                                    <div id="quill-editor"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Media Dropzone Card --}}
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
                            <div class="flex items-center gap-3 mb-8">
                                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-500 flex items-center justify-center">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                                <h2 class="text-2xl font-black text-slate-900">Course Media</h2>
                            </div>

                            <label class="relative block w-full aspect-video md:aspect-[21/9] rounded-2xl border-4 border-dashed border-slate-200 bg-slate-50 hover:bg-slate-100 hover:border-sky-400 transition cursor-pointer overflow-hidden group">
                                <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewThumbnail(this)">
                                
                                @if($course->thumbnail_src)
                                    <img id="thumb-preview-img" src="{{ $course->thumbnail_src }}" class="absolute inset-0 w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center">
                                        <span class="px-6 py-3 bg-white text-slate-900 font-bold rounded-xl shadow-xl transform scale-95 group-hover:scale-100 transition">Change Thumbnail</span>
                                    </div>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 group-hover:text-sky-500 transition" id="thumb-placeholder">
                                        <svg class="w-16 h-16 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                                        <span class="text-lg font-bold mb-1">Click to upload or drag and drop</span>
                                        <span class="text-sm font-medium opacity-70">WebP, PNG, or JPG (max. 2MB)</span>
                                    </div>
                                    <img id="thumb-preview-img" class="absolute inset-0 w-full h-full object-cover hidden">
                                @endif
                            </label>
                        </div>

                    </div>

                    {{-- Right Column: Settings & Curriculum --}}
                    <div class="space-y-8">
                        
                        {{-- Publish Settings Card --}}
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 p-8">
                            <h2 class="text-xl font-black text-slate-900 mb-6">Publishing</h2>
                            
                            {{-- iOS Style Toggle --}}
                            <div class="flex items-center justify-between mb-8 p-4 bg-slate-50 rounded-xl border border-slate-200">
                                <div>
                                    <p class="font-bold text-slate-900">Visibility Status</p>
                                    <p class="text-xs text-slate-500 font-medium mt-1">Make course public</p>
                                </div>
                                <div class="relative inline-block w-14 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" id="status-toggle" class="toggle-checkbox absolute block w-7 h-7 rounded-full bg-white border-4 border-slate-200 appearance-none cursor-pointer transition-transform duration-300 z-10" {{ old('status', $course->status) === 'published' ? 'checked' : '' }} onchange="document.getElementById('status-hidden').value = this.checked ? 'published' : 'draft';">
                                    <label for="status-toggle" class="toggle-label block overflow-hidden h-7 rounded-full bg-slate-200 cursor-pointer transition-colors duration-300"></label>
                                </div>
                                <input type="hidden" name="status" id="status-hidden" value="{{ old('status', $course->status) }}">
                            </div>

                            <div class="space-y-6">
                                <div>
                                    <label for="category_id" class="block text-sm font-bold text-slate-700 mb-2">Category</label>
                                    <select id="category_id" name="category_id" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-200 rounded-xl text-slate-900 font-bold focus:bg-white focus:border-sky-500 focus:ring-0 transition appearance-none cursor-pointer" required style="background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%2394a3b8%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'); background-repeat: no-repeat; background-position: right 1.25rem top 50%; background-size: 0.8rem auto;">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}" {{ old('category_id', $course->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Curriculum Builder Card --}}
                        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col h-[600px]">
                            <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                <h2 class="text-xl font-black text-slate-900">Curriculum</h2>
                                <a href="{{ route('instructor.lessons.create', $course) }}" class="w-8 h-8 bg-slate-900 text-white rounded-full flex items-center justify-center hover:bg-slate-800 transition transform hover:scale-110 shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </a>
                            </div>

                            <div class="flex-1 overflow-y-auto p-4 space-y-3 hide-scrollbar bg-slate-50/30">
                                @forelse($lessons as $lesson)
                                    <div class="group relative bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-md hover:border-sky-300 transition-all flex items-center gap-4">
                                        <div class="cursor-move text-slate-300 hover:text-slate-600">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path></svg>
                                        </div>
                                        
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 font-bold shadow-inner 
                                            {{ $lesson->type === 'video' ? 'bg-sky-100 text-sky-600' : ($lesson->type === 'quiz' ? 'bg-purple-100 text-purple-600' : 'bg-amber-100 text-amber-600') }}">
                                            @if($lesson->type === 'video')
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                            @elseif($lesson->type === 'quiz')
                                                <span class="text-lg">📝</span>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 truncate">{{ $lesson->title }}</p>
                                            <p class="text-xs font-semibold text-slate-400 mt-0.5">Lesson {{ $lesson->order }} • {{ ucfirst($lesson->type) }}</p>
                                        </div>

                                        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition absolute right-4 top-1/2 -translate-y-1/2 bg-white pl-4">
                                            <a href="{{ route('instructor.lessons.edit', [$course, $lesson]) }}" class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center hover:bg-sky-100 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('instructor.lessons.destroy', [$course, $lesson]) }}" method="POST" onsubmit="return confirm('Delete this lesson?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-100 transition">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @empty
                                    <div class="h-full flex flex-col items-center justify-center text-center p-6 border-2 border-dashed border-slate-200 rounded-2xl">
                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center text-3xl mb-4">👻</div>
                                        <h3 class="font-bold text-slate-800 mb-1">It's quiet here...</h3>
                                        <p class="text-sm text-slate-500 font-medium mb-4">Start building your curriculum.</p>
                                        <a href="{{ route('instructor.lessons.create', $course) }}" class="px-6 py-2 bg-slate-900 text-white font-bold rounded-lg shadow-lg hover:bg-slate-800 transition">Add Lesson</a>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Action Hub Footer (Sticky) --}}
                <div class="fixed bottom-0 left-0 right-0 p-4 z-40">
                    <div class="max-w-7xl mx-auto">
                        <div class="glass-panel rounded-2xl shadow-2xl p-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-t-4 border-t-sky-500">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-sky-100 text-sky-600 rounded-xl flex items-center justify-center shadow-inner">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                </div>
                                <div>
                                    <p class="font-black text-slate-900 leading-tight">Ready to save?</p>
                                    <p class="text-xs font-bold text-slate-500">Don't forget to review your changes.</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <button type="button" onclick="if(confirm('Permanently delete this course?')) document.getElementById('delete-course-form').submit();" class="flex-1 sm:flex-none px-6 py-3.5 text-rose-500 font-bold hover:bg-rose-50 rounded-xl transition">
                                    Delete
                                </button>
                                <button type="submit" class="flex-1 sm:flex-none px-10 py-3.5 bg-slate-900 text-white font-black rounded-xl hover:bg-slate-800 shadow-xl hover:-translate-y-1 transition-all duration-300">
                                    Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <form id="delete-course-form" action="{{ route('instructor.courses.destroy', $course) }}" method="POST" style="display:none;">
                @csrf @method('DELETE')
            </form>

        </div>
    </div>

    <script src="https://cdn.quilljs.com/1.3.7/quill.js"></script>
    <script>
        const quill = new Quill('#quill-editor', {
            theme: 'snow',
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
        const existingDesc = document.getElementById('description').value;
        if (existingDesc) quill.root.innerHTML = existingDesc;
        document.getElementById('update-course-form').addEventListener('submit', function () {
            document.getElementById('description').value = quill.root.innerHTML;
        });

        // Floating toast for session success (ensures visibility)
        (function () {
            const holder = document.getElementById('floating-success');
            if (!holder) return;
            const msg = holder.dataset.message;
            if (!msg) return;

            const toast = document.createElement('div');
            toast.className = 'fixed right-6 top-6 z-50 max-w-sm w-full p-4 bg-emerald-600 text-white rounded-lg shadow-lg flex items-start gap-3';
            toast.style.boxShadow = '0 10px 30px rgba(2,6,23,0.2)';
            toast.innerHTML = `
                <div class="flex-shrink-0 mt-1"> 
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div class="flex-1 text-sm font-semibold">${msg}</div>
                <button aria-label="Dismiss" class="ml-3 opacity-80 hover:opacity-100">✕</button>
            `;

            const btn = toast.querySelector('button');
            btn.addEventListener('click', () => toast.remove());

            document.body.appendChild(toast);
            setTimeout(() => { try { toast.remove(); } catch (e){} }, 6000);
        })();

        function previewThumbnail(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.getElementById('thumb-preview-img');
                    const placeholder = document.getElementById('thumb-placeholder');
                    if (img) {
                        img.src = e.target.result;
                        img.classList.remove('hidden');
                        if(placeholder) placeholder.style.display = 'none';
                    }
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>
