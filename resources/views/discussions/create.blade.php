<x-app-layout>
    <div class="min-h-screen bg-[#F8FAFC] py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb Navigation --}}
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-slate-500">
                    <li><a href="{{ route('courses.show', $course) }}" class="hover:text-slate-900 transition-colors">{{ $course->title }}</a></li>
                    <li>·</li>
                    <li><a href="{{ route('courses.discussions.index', $course) }}" class="hover:text-slate-900 transition-colors">Q&A Discussions</a></li>
                    <li>·</li>
                    <li class="text-slate-900 font-bold">Ask a Question</li>
                </ol>
            </nav>

            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold tracking-tight text-slate-900">Start a New Discussion</h1>
                <p class="text-sm text-slate-500 mt-1">Ask a question or share a learning insight with instructors and peers enrolled in {{ $course->title }}.</p>
            </div>

            {{-- Form Container --}}
            <div class="bg-white border border-slate-200/80 rounded-3xl p-6 sm:p-10 shadow-sm">
                <form action="{{ route('courses.discussions.store', $course) }}" method="POST" class="space-y-6">
                    @csrf

                    {{-- Title Input --}}
                    <div>
                        <label for="title" class="block text-xs font-bold font-mono uppercase tracking-widest text-slate-700 mb-2">
                            Discussion Title <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required
                            placeholder="e.g., Confused about async/await behavior in lesson 3"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 outline-none transition-all text-sm placeholder:text-slate-400">
                        @error('title')
                            <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Content Input --}}
                    <div>
                        <label for="content" class="block text-xs font-bold font-mono uppercase tracking-widest text-slate-700 mb-2">
                            Details & Context <span class="text-red-500">*</span>
                        </label>
                        <textarea name="content" id="content" rows="6" required
                            placeholder="Provide details, code snippets, or background context to help others understand your question..."
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 outline-none transition-all text-sm placeholder:text-slate-400"></textarea>
                        @error('content')
                            <p class="mt-1.5 text-xs text-red-500 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                        <a href="{{ route('courses.discussions.index', $course) }}" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-600 hover:bg-slate-50 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all shadow-sm">
                            Post Discussion
                        </button>
                    </div>
                </form>
            </div>

            {{-- Community Guidelines Tips --}}
            <div class="mt-8 bg-amber-50/50 border border-amber-100 rounded-2xl p-5 flex gap-4">
                <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 shrink-0 flex items-center justify-center font-bold text-sm">!</div>
                <div class="text-xs text-amber-900">
                    <span class="font-bold block mb-1">Tips for Getting Fast Answers</span>
                    Be clear and specific in your title. Include minimal reproducible code examples where relevant, and ensure your query hasn't already been answered in active discussions.
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
