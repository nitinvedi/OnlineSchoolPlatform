<x-app-layout>
    <div class="min-h-screen bg-[#F8FAFC] py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Breadcrumb Navigation --}}
            <nav class="mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-xs font-mono uppercase tracking-widest text-slate-500 truncate">
                    <li><a href="{{ route('courses.show', $course) }}" class="hover:text-slate-900 transition-colors">{{ $course->title }}</a></li>
                    <li>·</li>
                    <li><a href="{{ route('courses.discussions.index', $course) }}" class="hover:text-slate-900 transition-colors">Q&A</a></li>
                    <li>·</li>
                    <li class="text-slate-900 font-bold truncate">{{ $discussion->title }}</li>
                </ol>
            </nav>

            {{-- Flash Messages --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 text-xs font-bold text-emerald-800 flex items-center gap-2">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- Original Discussion Thread Header --}}
            <div class="bg-white border {{ $discussion->is_pinned ? 'border-amber-200/80 bg-amber-50/5' : 'border-slate-200/80' }} rounded-3xl p-6 sm:p-8 mb-8 shadow-sm">
                
                {{-- Meta row --}}
                <div class="flex flex-wrap items-center justify-between gap-4 mb-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($discussion->user->name ?? 'User') }}&background=0F172A&color=fff&rounded=true&size=40" class="w-10 h-10 rounded-xl ring-1 ring-slate-100">
                        <div>
                            <div class="text-xs font-bold text-slate-900">{{ $discussion->user->name ?? 'Anonymous Student' }}</div>
                            <div class="text-[10px] text-slate-400">{{ $discussion->created_at->format('M d, Y · h:i A') }}</div>
                        </div>
                    </div>

                    {{-- Status/Tags --}}
                    <div class="flex items-center gap-2">
                        @if($discussion->is_pinned)
                            <span class="px-2 py-0.5 rounded bg-amber-50 border border-amber-200 text-[9px] font-mono font-bold text-amber-700 uppercase tracking-widest">Pinned</span>
                        @endif
                        @if(!$discussion->isOpen())
                            <span class="px-2 py-0.5 rounded bg-slate-100 text-[9px] font-mono font-bold text-slate-600 uppercase tracking-widest">Closed</span>
                        @endif

                        {{-- Moderation Tools --}}
                        @if(Auth::check() && (Auth::user()->isAdmin() || (Auth::user()->isInstructor() && $course->instructor_id === Auth::id())))
                            <div class="flex items-center gap-1 ml-2 border-l border-slate-100 pl-2">
                                <form action="{{ route('courses.discussions.pin', [$course, $discussion]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-amber-600 transition-colors" title="{{ $discussion->is_pinned ? 'Unpin' : 'Pin' }}">
                                        <svg class="w-3.5 h-3.5" fill="{{ $discussion->is_pinned ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 20 20"><path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('courses.discussions.close', [$course, $discussion]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-slate-50 text-slate-400 hover:text-slate-900 transition-colors" title="{{ $discussion->isOpen() ? 'Close thread' : 'Reopen thread' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </button>
                                </form>
                                <form action="{{ route('courses.discussions.delete', [$course, $discussion]) }}" method="POST" onsubmit="return confirm('Delete this discussion thread?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg hover:bg-red-50 text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <h1 class="text-xl font-bold text-slate-900 mb-3">{{ $discussion->title }}</h1>
                <div class="prose prose-slate max-w-none text-sm text-slate-700 leading-relaxed space-y-4">
                    {!! nl2br(e($discussion->content)) !!}
                </div>

            </div>

            {{-- Replies Section --}}
            <div class="mb-6 flex items-center justify-between">
                <h2 class="text-base font-bold text-slate-900">
                    Replies & Solutions <span class="text-xs font-mono font-normal text-slate-400 ml-1">({{ number_format($replies->total()) }})</span>
                </h2>
            </div>

            {{-- Reply List --}}
            <div class="space-y-4 mb-8">
                @forelse($replies as $reply)
                    <div id="reply-{{ $reply->id }}" class="bg-white border border-slate-200/80 rounded-2xl p-5 shadow-sm transition-all {{ $reply->isApproved() ? 'ring-2 ring-emerald-500/20' : '' }} {{ $reply->status === 'hidden' ? 'opacity-60 bg-slate-50' : '' }}">
                        <div class="flex items-start justify-between gap-3 mb-3">
                            <div class="flex items-center gap-2.5">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name ?? 'User') }}&background=0F172A&color=fff&rounded=true&size=32" class="w-8 h-8 rounded-lg">
                                <div>
                                    <div class="text-xs font-bold text-slate-900 flex items-center gap-1.5">
                                        {{ $reply->user->name ?? 'Student' }}
                                        @if(($reply->user->id ?? null) === ($discussion->user_id ?? null))
                                            <span class="px-1.5 py-0.2 rounded bg-sky-50 text-[8px] font-bold text-sky-600 uppercase tracking-wider">Author</span>
                                        @endif
                                        @if(($reply->user->id ?? null) === $course->instructor_id)
                                            <span class="px-1.5 py-0.2 rounded bg-violet-50 text-[8px] font-bold text-violet-700 uppercase tracking-wider">Instructor</span>
                                        @endif
                                    </div>
                                    <div class="text-[9px] text-slate-400">{{ $reply->created_at->diffForHumans() }}</div>
                                </div>
                            </div>

                            {{-- Actions / Like Button --}}
                            <div class="flex items-center gap-1">
                                @if($reply->isApproved())
                                    <span class="px-2 py-0.5 rounded-md bg-emerald-50 text-[9px] font-mono font-bold text-emerald-700 uppercase tracking-widest flex items-center gap-1 mr-1">
                                        ✓ Solution
                                    </span>
                                @endif

                                {{-- Like/Unlike --}}
                                @auth
                                    <form action="{{ route('discussions.replies.like', [$course, $discussion, $reply]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 rounded-lg text-xs font-medium bg-slate-50 hover:bg-slate-100 text-slate-600 transition-colors">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                                            <span class="text-[10px] font-bold">{{ $reply->likes_count ?? 0 }}</span>
                                        </button>
                                    </form>
                                @endauth

                                {{-- Moderation Options --}}
                                @if(Auth::check() && (Auth::user()->isAdmin() || Auth::id() === $course->instructor_id))
                                    <form action="{{ route('discussions.replies.approve', [$course, $discussion, $reply]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-emerald-600 transition-colors" title="Mark as approved solution">
                                            ✓
                                        </button>
                                    </form>
                                    <form action="{{ route('discussions.replies.hide', [$course, $discussion, $reply]) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-amber-600 transition-colors" title="Hide reply">
                                            ✕
                                        </button>
                                    </form>
                                    <form action="{{ route('discussions.replies.delete', [$course, $discussion, $reply]) }}" method="POST" onsubmit="return confirm('Delete reply?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1 rounded text-slate-400 hover:text-red-600 transition-colors" title="Delete">
                                            🗑
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>

                        {{-- Reply Body --}}
                        <div class="text-xs text-slate-700 leading-relaxed whitespace-pre-wrap">{!! e($reply->content) !!}</div>

                        {{-- Child Replies (Nested) --}}
                        @if($reply->childReplies->count() > 0)
                            <div class="mt-3 pt-3 border-t border-slate-100 pl-4 border-l-2 border-slate-200 space-y-3">
                                @foreach($reply->childReplies as $child)
                                    <div class="flex items-start justify-between gap-2">
                                        <div>
                                            <span class="font-bold text-slate-900 text-[11px]">{{ $child->user->name ?? 'Student' }}:</span>
                                            <span class="text-[11px] text-slate-600">{!! e($child->content) !!}</span>
                                        </div>
                                        @if(Auth::check() && (Auth::user()->isAdmin() || Auth::id() === $course->instructor_id))
                                            <form action="{{ route('discussions.replies.delete', [$course, $discussion, $child]) }}" method="POST" class="shrink-0">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[10px] text-slate-300 hover:text-red-500">×</button>
                                            </form>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @empty
                    <div class="bg-white border border-slate-200/80 rounded-2xl p-8 text-center text-slate-400 text-xs font-medium">
                        No replies posted yet. Share your insight below!
                    </div>
                @endforelse

                {{-- Pagination --}}
                @if($replies->hasPages())
                    <div class="pt-4">
                        {{ $replies->links() }}
                    </div>
                @endif
            </div>

            {{-- Leave a Reply Form --}}
            @if($discussion->isOpen())
                @auth
                    <div class="bg-white border border-slate-200/80 rounded-3xl p-6 shadow-sm">
                        <h3 class="text-xs font-bold font-mono uppercase tracking-widest text-slate-800 mb-4">Post a Reply</h3>
                        <form action="{{ route('discussions.replies.store', [$course, $discussion]) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <textarea name="content" rows="4" required
                                    placeholder="Write your response, solution, or follow-up clarification..."
                                    class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/10 outline-none transition-all text-xs placeholder:text-slate-400"></textarea>
                                @error('content')
                                    <p class="mt-1 text-xs text-red-500 font-medium">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="flex justify-end">
                                <button type="submit" class="px-5 py-2 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition-all shadow-sm">
                                    Submit Reply
                                </button>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="bg-slate-100 border border-slate-200 rounded-2xl p-4 text-center text-xs text-slate-600">
                        Please <a href="{{ route('login') }}" class="font-bold underline hover:text-slate-900">sign in</a> to participate in this discussion.
                    </div>
                @endauth
            @else
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-4 text-center text-xs text-slate-400 font-medium">
                    🔒 This discussion thread has been closed to new replies.
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
