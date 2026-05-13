<x-app-layout>
    <div class="min-h-screen bg-[#F8FAFC] py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb --}}
            <nav class="mb-8" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-slate-500">
                    <li><a href="{{ route('courses.show', $course) }}" class="hover:text-slate-900 transition-colors">{{ $course->title }}</a></li>
                    <li>·</li>
                    <li class="text-slate-900 font-bold">Q&A Discussions</li>
                </ol>
            </nav>

            {{-- Header & Action --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">Course Q&A</h1>
                    <p class="text-sm text-slate-500 mt-1">Explore questions, solutions, and community announcements for {{ $course->title }}.</p>
                </div>
                <div>
                    <a href="{{ route('courses.discussions.create', $course) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all shadow-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ask a Question
                    </a>
                </div>
            </div>

            {{-- Discussions List List --}}
            <div class="space-y-4">
                @forelse($discussions as $discussion)
                    <div class="bg-white border {{ $discussion->is_pinned ? 'border-amber-200/80 bg-amber-50/10' : 'border-slate-200/80' }} rounded-3xl p-6 hover:border-slate-300 transition-all shadow-sm">
                        <div class="flex items-start justify-between gap-4">
                            
                            {{-- Main Info --}}
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    @if($discussion->is_pinned)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-amber-50 border border-amber-200 text-[9px] font-mono font-bold text-amber-700 uppercase tracking-widest">
                                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                            Pinned
                                        </span>
                                    @endif
                                    @if(!$discussion->isOpen())
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-slate-100 text-[9px] font-mono font-bold text-slate-600 uppercase tracking-widest">
                                            Closed
                                        </span>
                                    @endif
                                    <span class="text-[10px] text-slate-400 font-medium">
                                        Asked {{ $discussion->created_at->diffForHumans() }} by <span class="font-bold text-slate-700">{{ $discussion->user->name ?? 'Anonymous' }}</span>
                                    </span>
                                </div>

                                <h2 class="text-base font-bold text-slate-900 hover:text-brand-600 transition-colors leading-snug">
                                    <a href="{{ route('courses.discussions.show', [$course, $discussion]) }}" class="block">
                                        {{ $discussion->title }}
                                    </a>
                                </h2>

                                <p class="text-sm text-slate-500 mt-1 line-clamp-2">
                                    {{ strip_tags($discussion->content) }}
                                </p>
                            </div>

                            {{-- Stats & Controls --}}
                            <div class="flex flex-col sm:flex-row items-end sm:items-center gap-4 shrink-0">
                                <div class="flex items-center gap-4 text-xs text-slate-400 font-mono">
                                    <div class="flex items-center gap-1" title="Views">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        <span>{{ number_format($discussion->view_count ?? 0) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 {{ ($discussion->reply_count ?? 0) > 0 ? 'text-brand-600 font-bold' : '' }}" title="Replies">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                        <span>{{ number_format($discussion->reply_count ?? 0) }}</span>
                                    </div>
                                </div>

                                <a href="{{ route('courses.discussions.show', [$course, $discussion]) }}" class="p-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                </a>
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-12 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mx-auto mb-4 text-slate-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <h3 class="text-base font-bold text-slate-900">No discussions started yet</h3>
                        <p class="text-sm text-slate-500 mt-1 max-w-sm mx-auto">Be the first to ask a question or initiate a topic related to this course material.</p>
                        <a href="{{ route('courses.discussions.create', $course) }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all mt-6 shadow-sm">
                            Ask the First Question
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination Links --}}
            @if($discussions->hasPages())
                <div class="mt-8">
                    {{ $discussions->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
