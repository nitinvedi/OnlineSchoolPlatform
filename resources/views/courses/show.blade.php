<x-app-layout>
    <div x-data="{ videoModalOpen: false, activeTab: 'overview', expandedSection: null }" class="min-h-screen bg-[#F8FAFC]">
        
        {{-- Video Trailer Modal --}}
        <div x-show="videoModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <div class="fixed inset-0 bg-black/95 backdrop-blur-md" @click="videoModalOpen = false"></div>
            <div class="relative w-full max-w-5xl bg-black overflow-hidden shadow-2xl z-10 aspect-video border border-white/10">
                <button @click="videoModalOpen = false" class="absolute top-6 right-6 z-20 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="absolute inset-0 flex items-center justify-center text-gray-500 bg-black">
                    <p class="font-bold uppercase tracking-widest text-sm">{{ $course->video_url ? 'Video Available' : 'No preview video' }}</p>
                </div>
            </div>
        </div>

        {{-- BREADCRUMB --}}
        <div class="border-b border-slate-200 bg-[#F8FAFC] sticky top-0 z-40 backdrop-blur-md bg-[#F8FAFC]/90">
            <div class="max-w-full px-4 sm:px-8 lg:px-12 py-3">
                <nav class="text-[10px] font-mono uppercase tracking-widest text-gray-500" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 flex-wrap">
                        <li><a href="/" class="hover:text-[#0F172A] transition-colors">Home</a></li>
                        <li>·</li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-[#0F172A] transition-colors">Catalog</a></li>
                        <li>·</li>
                        <li><span class="text-gray-600">{{ $course->category->name ?? 'Category' }}</span></li>
                        <li>·</li>
                        <li class="text-[#0F172A] font-bold break-words">{{ $course->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- HERO HEADER --}}
        <div class="bg-white border-b border-slate-200 pt-16 pb-12 lg:pt-16 lg:pb-12">
            <div class="max-w-full px-4 sm:px-8 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-[65%_1fr] gap-8 lg:gap-12">
                    
                    {{-- LEFT: 65% CONTENT --}}
                    <div>
                        {{-- Badges Row --}}
                        <div class="flex flex-wrap items-center gap-3 mb-6">
                            <span class="px-3 py-1 border border-[#333] text-[9px] font-mono uppercase tracking-widest text-[#0F172A] font-bold">
                                {{ $course->category->name ?? 'Category' }}
                            </span>
                            <span class="px-3 py-1 border border-[#333] text-[9px] font-mono uppercase tracking-widest text-[#0F172A] font-bold">
                                {{ ucfirst($course->difficulty_level ?? $course->level ?? 'Intermediate') }}
                            </span>
                            @if($course->is_bestseller)
                                <span class="px-3 py-1 bg-[#0F172A] text-white text-[9px] font-mono uppercase tracking-widest font-bold">
                                    Bestseller
                                </span>
                            @endif
                        </div>

                        {{-- Title --}}
                        <h1 class="text-[5vw] font-display font-black text-[#0F172A] leading-[1] mb-4 tracking-tight">
                            {{ $course->title }}
                        </h1>

                        {{-- Subtitle --}}
                        <p class="text-base font-sans text-gray-600 max-w-xl mb-8 leading-relaxed">
                            {{ strip_tags($course->overview ?: $course->description) }}
                        </p>

                        {{-- Meta Row --}}
                        <div class="flex flex-wrap items-center gap-3 mb-8 text-[11px] font-mono uppercase tracking-widest">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[#2255FF] font-bold">★ {{ number_format($reviewAverage ?? $course->rating ?? 0, 1) }}</span>
                            </div>
                            <span class="text-gray-500">·</span>
                            <span class="text-gray-500">({{ number_format($reviews->count()) }} REVIEWS)</span>
                            <span class="text-gray-500">·</span>
                            <span class="text-gray-500">{{ number_format($course->student_count ?? 0) }} STUDENTS</span>
                            <span class="text-gray-500">·</span>
                            <span class="text-gray-400">{{ $course->updated_at ? $course->updated_at->format('M Y') : 'Recently updated' }}</span>
                        </div>

                        {{-- Instructor Mini --}}
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name ?? 'Instructor') }}&background=2255FF&color=fff&size=48" 
                                 alt="{{ $course->instructor->name ?? 'Instructor' }}" 
                                 class="w-6 h-6 rounded-full">
                            <span class="text-sm font-sans text-[#0F172A] font-medium">By <span class="font-bold">{{ $course->instructor->name ?? 'Expert Instructor' }}</span></span>
                        </div>
                    </div>

                    {{-- RIGHT: 35% SIDEBAR (Sticky on desktop) --}}
                    <div class="hidden lg:block">
                        <div class="sticky top-24 space-y-4 z-30">
                            {{-- Thumbnail with Play Button --}}
                            <div class="bg-white border border-slate-200 overflow-hidden group cursor-pointer shadow-sm" @click="videoModalOpen = true">
                                <div class="relative aspect-video bg-white overflow-hidden">
                                    <img src="{{ $course->thumbnail_src ?? $course->thumbnail_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=2255FF&color=fff&size=800' }}" 
                                         alt="{{ $course->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors">
                                        <div class="w-13 h-13 bg-[#2255FF] text-white flex items-center justify-center transition-all duration-300 group-hover:bg-[#F0EDE6] group-hover:text-[#0A0A0A]">
                                            <svg class="w-6 h-6 ml-0.5 fill-current" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Text --}}
                            <p class="text-[10px] font-mono uppercase tracking-widest text-gray-600 text-center">
                                Preview This Course
                            </p>

                            {{-- Price Block --}}
                            <div class="space-y-1.5">
                                @if($course->price && $course->price > 0)
                                    <div class="text-[36px] font-display font-black text-[#0F172A] leading-none">
                                        ${{ number_format($course->price, 0) }}
                                    </div>
                                    <div class="text-[16px] font-mono text-gray-400 line-through">
                                        ${{ number_format($course->price * 1.67, 0) }}
                                    </div>
                                    <div class="text-[#F5A623] text-[10px] font-mono font-bold">
                                        40% OFF
                                    </div>
                                @else
                                    <div class="text-[36px] font-display font-black text-[#0F172A] leading-none">
                                        FREE
                                    </div>
                                @endif
                            </div>

                            {{-- Live Sessions Block --}}
                            @php
                                $visibleLiveSessions = $course->liveSessions->take(3);
                                $canJoinLiveSessions = $enrolled || auth()->id() === $course->instructor_id || auth()->user()?->isAdmin();
                            @endphp

                            @if($visibleLiveSessions->isNotEmpty())
                                <div class="bg-white border border-slate-200 p-4 space-y-3 shadow-sm">
                                    <div class="flex items-center justify-between gap-3">
                                        <p class="text-[9px] font-mono uppercase tracking-widest text-gray-600 font-bold">Live Classes</p>
                                        <span class="text-[10px] font-mono uppercase tracking-widest font-bold {{ $visibleLiveSessions->contains(fn ($session) => $session->isLive()) ? 'text-red-600 animate-pulse' : ($visibleLiveSessions->contains(fn ($session) => $session->status === 'scheduled') ? 'text-gray-500' : 'text-gray-400') }}">
                                            {{ $visibleLiveSessions->contains(fn ($session) => $session->isLive()) ? '● Live Now' : ($visibleLiveSessions->contains(fn ($session) => $session->status === 'scheduled') ? 'Upcoming' : 'Past Sessions') }}
                                        </span>
                                    </div>

                                    <div class="space-y-3">
                                        @foreach($visibleLiveSessions as $session)
                                            <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50">
                                                <div class="flex items-start justify-between gap-3 mb-2">
                                                    <div>
                                                        <h4 class="font-bold text-sm text-[#0F172A] line-clamp-1">{{ $session->title }}</h4>
                                                        <p class="text-xs text-gray-600 mt-1">
                                                            {{ $session->isLive() ? 'Broadcasting live' : ($session->status === 'ended' ? 'Ended ' . $session->scheduled_at->format('M d') : $session->scheduled_at->format('M d, h:i A')) }}
                                                        </p>
                                                    </div>
                                                    <span class="px-2 py-0.5 rounded text-[9px] font-mono font-bold uppercase tracking-widest {{ $session->isLive() ? 'bg-red-100 text-red-700' : ($session->status === 'ended' ? 'bg-gray-100 text-gray-500' : 'bg-white border border-slate-200 text-slate-600') }}">
                                                        {{ $session->isLive() ? 'Live' : ($session->status === 'ended' ? 'Ended' : 'Scheduled') }}
                                                    </span>
                                                </div>

                                                @if($canJoinLiveSessions)
                                                    @if($session->isLive())
                                                        <a href="{{ route('live-sessions.show', $session) }}" class="inline-flex items-center justify-center w-full h-9 bg-red-600 text-white text-xs font-bold uppercase tracking-widest hover:bg-red-700 transition-colors rounded">
                                                            Join Live Class
                                                        </a>
                                                    @elseif($session->status === 'ended')
                                                        <div class="text-[11px] text-gray-400">This broadcast has ended.</div>
                                                    @else
                                                        <div class="text-[11px] text-gray-500">Link activated automatically when instructor begins streaming.</div>
                                                    @endif
                                                @else
                                                    <div class="text-[11px] text-gray-500">{{ $session->status === 'ended' ? 'This broadcast has ended.' : 'Enroll to join live interactive streams.' }}</div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            {{-- Timing summary (dynamic) --}}
                            @php
                                $nextLiveSession = $visibleLiveSessions->filter(fn($s) => $s->status !== 'ended')->first();
                            @endphp

                            @if($course->sale_ends_at && $course->sale_ends_at->isFuture())
                                <div class="bg-white border border-slate-200 p-3 space-y-1">
                                    <p class="text-[9px] font-mono uppercase tracking-widest text-gray-600 font-bold">Sale Ends</p>
                                    <div class="text-[16px] font-bold text-[#0F172A] leading-snug font-mono">
                                        {{ $course->sale_ends_at->diffForHumans() }}
                                    </div>
                                </div>
                            @elseif($nextLiveSession)
                                <div class="bg-white border border-slate-200 p-3 space-y-1">
                                    <p class="text-[9px] font-mono uppercase tracking-widest text-gray-600 font-bold">Next Live Session</p>
                                    <div class="text-[16px] font-bold text-[#0F172A] leading-snug font-sans">
                                        {{ $nextLiveSession->scheduled_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endif

                            {{-- Enroll Button --}}
                            @if($enrolled)
                                <a href="{{ route('lessons.show', [$course, $course->lessons->first() ?? 1]) }}" 
                                   class="block w-full h-13 bg-[#2255FF] text-white text-center leading-13 font-bold uppercase text-sm tracking-widest hover:bg-[#1a3fb3] transition-colors flex items-center justify-center shadow-sm">
                                    Continue Learning
                                </a>
                            @else
                                <form action="{{ $course->price && $course->price > 0 ? route('payments.checkout', $course) : route('courses.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full h-13 bg-[#2255FF] text-white font-bold uppercase text-sm tracking-widest hover:bg-[#1a3fb3] transition-colors shadow-sm">
                                        {{ $course->price && $course->price > 0 ? 'Enroll Now' : 'Enroll Free' }}
                                    </button>
                                </form>
                            @endif

                            {{-- Wishlist Button --}}
                            @auth
                                <form action="{{ route('courses.wishlist.toggle', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full h-11 border {{ $isWishlisted ? 'border-[#2255FF] bg-[#2255FF]/5 text-[#2255FF]' : 'border-slate-200 text-gray-700 hover:bg-gray-50' }} font-bold uppercase text-xs tracking-widest transition-colors flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4 {{ $isWishlisted ? 'fill-current' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                        <span class="hidden sm:inline">{{ $isWishlisted ? 'Saved to Wishlist' : 'Add to Wishlist' }}</span>
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="w-full h-11 border border-slate-200 text-gray-700 font-bold uppercase text-xs tracking-widest hover:bg-gray-50 transition-colors flex items-center justify-center gap-2 block text-center leading-11">
                                    <svg class="w-4 h-4 inline-block -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                    <span class="hidden sm:inline ml-1">Add to Wishlist</span>
                                </a>
                            @endauth

                            {{-- Divider --}}
                            <div class="border-t border-slate-200 pt-2"></div>

                            {{-- What's Included --}}
                            <div class="space-y-2.5 text-sm font-sans">
                                <div class="flex items-center gap-3 text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $course->lessons->count() }} Video Lessons</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ round(($course->lessons->sum('duration_minutes') ?? 330) / 60, 1) }}h Content</span>
                                </div>
                                <div class="flex items-center gap-3 text-gray-600">
                                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Lifetime Access</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MOBILE STICKY BOTTOM BAR --}}
        <div class="fixed bottom-0 left-0 right-0 lg:hidden bg-white border-t border-slate-200 p-3 flex gap-2 z-50 shadow-lg">
            <div class="text-base font-display font-black text-gray-900 flex items-center px-2">
                @if($course->price && $course->price > 0)
                    ${{ $course->price }}
                @else
                    FREE
                @endif
            </div>
            @if($enrolled)
                <a href="{{ route('lessons.show', [$course, $course->lessons->first() ?? 1]) }}" class="flex-1 h-11 bg-[#2255FF] text-white font-bold uppercase text-xs tracking-widest flex items-center justify-center text-center">
                    Continue Learning
                </a>
            @else
                <form action="{{ $course->price && $course->price > 0 ? route('payments.checkout', $course) : route('courses.enroll', $course) }}" method="POST" class="flex-1">
                    @csrf
                    <button type="submit" class="w-full h-11 bg-[#2255FF] text-white font-bold uppercase text-xs tracking-widest hover:bg-[#1a3fb3] transition-colors">
                        {{ $course->price && $course->price > 0 ? 'Enroll Now' : 'Free Access' }}
                    </button>
                </form>
            @endif
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="pb-20 lg:pb-8">
            <div class="max-w-full px-4 sm:px-8 lg:px-12 py-16">
                
                {{-- Integrated Discussion Access Bar --}}
                <div class="mb-10 p-4 rounded-xl bg-[#0F172A] text-white flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <div class="text-xs font-mono uppercase tracking-widest text-sky-400 font-bold mb-0.5">Interactive Hub</div>
                        <h3 class="text-sm font-bold">Have technical questions or code blockers?</h3>
                    </div>
                    <a href="{{ route('courses.discussions.index', $course) }}" class="px-4 py-2 bg-[#2255FF] hover:bg-[#1a3fb3] text-white text-xs font-mono font-bold uppercase tracking-wider shrink-0 transition-colors">
                        Open Course Q&A Hub →
                    </a>
                </div>

                {{-- Tab Navigation --}}
                <div class="flex gap-8 border-b border-slate-200 mb-12 overflow-x-auto">
                    <button @click="activeTab = 'overview'" 
                            :class="{'border-[#2255FF] text-[#0F172A]': activeTab === 'overview', 'border-transparent text-slate-500 hover:text-[#0F172A]': activeTab !== 'overview'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap outline-none">Overview</button>
                    <button @click="activeTab = 'curriculum'" 
                            :class="{'border-[#2255FF] text-[#0F172A]': activeTab === 'curriculum', 'border-transparent text-slate-500 hover:text-[#0F172A]': activeTab !== 'curriculum'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap outline-none">Curriculum</button>
                    <button @click="activeTab = 'instructor'" 
                            :class="{'border-[#2255FF] text-[#0F172A]': activeTab === 'instructor', 'border-transparent text-slate-500 hover:text-[#0F172A]': activeTab !== 'instructor'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap outline-none">Instructor</button>
                    <button @click="activeTab = 'reviews'" 
                            :class="{'border-[#2255FF] text-[#0F172A]': activeTab === 'reviews', 'border-transparent text-slate-500 hover:text-[#0F172A]': activeTab !== 'reviews'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap outline-none">Reviews</button>
                </div>

                {{-- OVERVIEW TAB --}}
                <div x-show="activeTab === 'overview'">
                    {{-- WHAT YOU'LL LEARN --}}
                    <div class="mb-16">
                        <div class="flex items-center gap-4 mb-8 pb-4 border-b border-slate-200">
                            <h2 class="text-[3vw] font-display font-black text-[#0F172A]">What You'll Learn</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @if($course->learning_outcomes && is_array(json_decode($course->learning_outcomes, true)))
                                @foreach(json_decode($course->learning_outcomes, true) as $outcome)
                                    <div class="flex items-start gap-3">
                                        <span class="text-[#2255FF] font-bold text-xl mt-0.5 flex-shrink-0">→</span>
                                        <p class="text-[13px] font-sans text-[#0F172A] leading-relaxed">{{ $outcome }}</p>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-span-2 text-gray-500 italic text-sm">
                                    Learning outcomes not yet defined for this course.
                                </div>
                            @endif
                        </div>
                        
                        @if($course->learning_outcomes && is_array(json_decode($course->learning_outcomes, true)) && count(json_decode($course->learning_outcomes, true)) > 6)
                            <button class="px-6 py-3 border border-slate-200 text-[#0F172A] font-bold text-xs uppercase tracking-widest hover:bg-gray-100 transition-colors mx-auto block">
                                Show More
                            </button>
                        @endif
                    </div>
                </div>

                {{-- CURRICULUM TAB --}}
                <div x-show="activeTab === 'curriculum'" style="display: none;">
                    <div class="mb-16">
                        <h2 class="text-[3vw] font-display font-black text-[#0F172A] mb-8 pb-4 border-b border-slate-200">Course Content</h2>
                        
                        <div class="space-y-0 border border-slate-200 bg-white">
                            @php
                                $sections = $course->lessons->groupBy('section_name')->all();
                                if(empty($sections)) {
                                    $sections = [null => $course->lessons];
                                }
                            @endphp
                            
                            @foreach($sections as $sectionIndex => $lessonGroup)
                                <div class="border-b border-slate-200 last:border-b-0">
                                    {{-- Section Header --}}
                                    <button @click="expandedSection === {{ $loop->index }} ? expandedSection = null : expandedSection = {{ $loop->index }}"
                                            class="w-full h-12 bg-gray-50 hover:bg-gray-100 px-6 flex items-center justify-between transition-colors">
                                        <div class="flex items-center gap-3 text-[12px] font-mono uppercase tracking-widest text-[#0F172A] font-bold">
                                            <span x-show="expandedSection !== {{ $loop->index }}">▸</span>
                                            <span x-show="expandedSection === {{ $loop->index }}" style="display: none;">▾</span>
                                            <span>Section {{ $loop->iteration }}{{ $sectionIndex ? ' — ' . $sectionIndex : ' — Getting Started' }}</span>
                                        </div>
                                        <div class="text-[10px] font-mono uppercase tracking-widest text-slate-500 font-bold">
                                            {{ $lessonGroup->count() }} LESSONS · {{ round($lessonGroup->sum('duration_minutes') / 60, 1) }}H
                                        </div>
                                    </button>

                                    {{-- Lesson Rows --}}
                                    <div x-show="expandedSection === {{ $loop->index }}" style="display: none;">
                                        @foreach($lessonGroup as $lesson)
                                            @php
                                                $isCompleted = is_object($enrolled) && ($enrolled->completedLessons ?? collect())->contains('id', $lesson->id);
                                            @endphp
                                            @if($enrolled)
                                                <a href="{{ route('lessons.show', [$course, $lesson]) }}" 
                                                   class="group flex items-center h-11 px-6 border-t border-slate-200 hover:bg-[#161616] hover:text-white transition-colors">
                                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                                        <span class="text-slate-400 text-lg flex-shrink-0 group-hover:text-[#2255FF]">▶</span>
                                                        <span class="text-[13px] font-sans text-[#0F172A] group-hover:text-white truncate">{{ $lesson->title }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-4 ml-4 flex-shrink-0">
                                                        <span class="text-[10px] font-mono uppercase tracking-widest text-slate-500 group-hover:text-slate-400">{{ $lesson->duration_minutes ?? 12 }} MIN</span>
                                                        @if($isCompleted)
                                                            <svg class="w-4 h-4 text-[#1DB954]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        @endif
                                                    </div>
                                                </a>
                                            @else
                                                <div class="flex items-center h-11 px-6 border-t border-slate-200 opacity-50">
                                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                                        <svg class="w-4 h-4 text-slate-400 flex-shrink-0 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                        <span class="text-[13px] font-sans text-[#0F172A] truncate">{{ $lesson->title }}</span>
                                                    </div>
                                                    <span class="text-[10px] font-mono uppercase tracking-widest text-slate-500 ml-4 font-bold">LOCKED</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- INSTRUCTOR TAB --}}
                <div x-show="activeTab === 'instructor'" style="display: none;">
                    <div class="mb-16">
                        <h2 class="text-[3vw] font-display font-black text-[#0F172A] mb-8 pb-4 border-b border-slate-200">About the Instructor</h2>
                        
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name ?? 'Instructor') }}&background=2255FF&color=fff&size=160" 
                                     alt="{{ $course->instructor->name ?? 'Instructor' }}" 
                                     class="w-20 h-20 rounded-lg border border-slate-200 object-cover">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-display font-black text-[#0F172A] mb-2">{{ $course->instructor->name ?? 'Expert Instructor' }}</h3>
                                <p class="text-[11px] font-mono uppercase tracking-widest text-slate-500 mb-6 font-bold">Senior Industry Expert</p>
                                <p class="text-[14px] font-sans text-[#0F172A] leading-relaxed mb-6">{{ $course->instructor->bio ?? 'An experienced professional with over 15 years in the industry, focused on delivering high-impact educational content.' }}</p>
                                
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6 border-t border-slate-200 pt-6">
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#0F172A] mb-1">{{ number_format($course->instructor->courses_count ?? 1) }}</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-slate-500">Courses</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#0F172A] mb-1">{{ number_format($reviews->count()) }}</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-slate-500">Reviews</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#0F172A] mb-1">{{ number_format($course->student_count ?? 0) }}</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-slate-500">Students</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#0F172A] mb-1">{{ number_format($course->live_sessions_count ?? 0) }}</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-slate-500">Live Sessions</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REVIEWS TAB --}}
                <div x-show="activeTab === 'reviews'" style="display: none;">
                    <div class="mb-16">
                        <h2 class="text-[3vw] font-display font-black text-[#0F172A] mb-8 pb-4 border-b border-slate-200">Student Reviews</h2>
                        
                        {{-- Overall Rating --}}
                        <div class="flex flex-col md:flex-row gap-12 mb-12 items-center sm:items-start">
                            <div class="flex flex-col items-center shrink-0">
                                <div class="text-[6vw] font-display font-black text-[#0F172A] leading-none">{{ number_format($reviewAverage ?? $course->rating ?? 0, 1) }}</div>
                                <div class="flex gap-1.5 text-[#F5A623] my-3">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                                <p class="text-[10px] font-mono uppercase tracking-widest text-slate-500 font-bold">AVERAGE RATING</p>
                            </div>

                            <div class="flex-1 w-full space-y-3">
                                @foreach($reviewDistribution as $star => $stats)
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 w-8 flex-shrink-0 justify-end">
                                            <span class="text-xs font-black text-slate-500">{{ $star }}</span>
                                            <svg class="w-3 h-3 text-[#F5A623] fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        </div>
                                        <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div class="h-full bg-[#2255FF]" style="width: {{ $stats['percentage'] }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-mono uppercase tracking-widest text-slate-500 w-10 text-right font-bold">{{ $stats['percentage'] }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Individual Reviews --}}
                        <div class="space-y-4 border-t border-slate-200 pt-8">
                            @forelse($reviews->take(6) as $review)
                                <div class="bg-white border border-slate-200 p-5 rounded-none sm:rounded-lg">
                                    <div class="flex items-start justify-between gap-4 mb-3">
                                        <div class="flex items-center gap-3 min-w-0">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($review->user->name ?? 'Student') }}&background=2255FF&color=fff&size=48" alt="{{ $review->user->name ?? 'Student' }}" class="w-10 h-10 rounded-full flex-shrink-0">
                                            <div class="min-w-0">
                                                <p class="font-bold text-[#0F172A] truncate text-xs">{{ $review->user->name ?? 'Student' }}</p>
                                                <p class="text-[10px] font-mono uppercase tracking-widest text-slate-500">{{ $review->created_at?->format('M d, Y') ?? 'Recently' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-0.5 text-[#F5A623] flex-shrink-0">
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-3.5 h-3.5 {{ $i < $review->rating ? 'fill-current' : 'fill-slate-200' }}" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            @endfor
                                        </div>
                                    </div>
                                    <p class="text-[13px] leading-relaxed text-[#0F172A]">
                                        {{ $review->review ?: 'Highly recommended layout metrics standard presentation fully fulfilled.' }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] text-center py-12">
                                    No written student feedback recorded yet.
                                </p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
