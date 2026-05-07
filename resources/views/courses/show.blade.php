<x-app-layout>
    <div x-data="{ videoModalOpen: false, activeTab: 'overview', expandedSection: null }" class="min-h-screen bg-[#0A0A0A]">
        
        {{-- Video Trailer Modal --}}
        <div x-show="videoModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <div class="fixed inset-0 bg-black/95 backdrop-blur-md" @click="videoModalOpen = false"></div>
            <div class="relative w-full max-w-5xl bg-black overflow-hidden shadow-2xl z-10 aspect-video border border-white/10">
                <button @click="videoModalOpen = false" class="absolute top-6 right-6 z-20 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="absolute inset-0 flex items-center justify-center text-slate-500 bg-black">
                    <p class="font-bold uppercase tracking-widest text-sm">Course Trailer Placeholder</p>
                </div>
            </div>
        </div>

        {{-- BREADCRUMB --}}
        <div class="border-b border-[#1E1E1E] bg-[#0A0A0A] sticky top-0 z-40">
            <div class="max-w-full px-4 sm:px-8 lg:px-12 py-3">
                <nav class="text-[10px] font-mono uppercase tracking-widest text-[#333]" aria-label="Breadcrumb">
                    <ol class="flex items-center gap-2 flex-wrap">
                        <li><a href="/" class="hover:text-[#F0EDE6] transition-colors">Home</a></li>
                        <li>·</li>
                        <li><a href="{{ route('courses.index') }}" class="hover:text-[#F0EDE6] transition-colors">Catalog</a></li>
                        <li>·</li>
                        <li><a href="#" class="hover:text-[#F0EDE6] transition-colors">{{ $course->category->name ?? 'Category' }}</a></li>
                        <li>·</li>
                        <li class="text-[#F0EDE6] break-words">{{ $course->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- HERO HEADER --}}
        <div class="bg-[#0F0F0F] border-b border-[#1E1E1E] pt-16 pb-12 lg:pt-16 lg:pb-12">
            <div class="max-w-full px-4 sm:px-8 lg:px-12">
                <div class="grid grid-cols-1 lg:grid-cols-[65%_1fr] gap-8 lg:gap-12">
                    
                    {{-- LEFT: 65% CONTENT --}}
                    <div>
                        {{-- Badges Row --}}
                        <div class="flex flex-wrap items-center gap-3 mb-6">
                            <span class="px-3 py-1 border border-[#333] text-[9px] font-mono uppercase tracking-widest text-[#F0EDE6]">
                                {{ $course->category->name ?? 'Category' }}
                            </span>
                            @php
                                $levels = ['Beginner', 'Intermediate', 'Advanced', 'Expert'];
                                $level = $levels[array_rand($levels)] ?? 'Intermediate';
                            @endphp
                            <span class="px-3 py-1 border border-[#333] text-[9px] font-mono uppercase tracking-widest text-[#F0EDE6]">
                                {{ $level }}
                            </span>
                        </div>

                        {{-- Title --}}
                        <h1 class="text-[5vw] font-display font-black text-[#F0EDE6] leading-[1] mb-4">
                            {{ $course->title }}
                        </h1>

                        {{-- Subtitle --}}
                        <p class="text-base font-sans text-[#555] max-w-xl mb-8 leading-relaxed">
                            {{ strip_tags($course->overview) }}
                        </p>

                        {{-- Meta Row --}}
                        <div class="flex flex-wrap items-center gap-3 mb-8 text-[11px] font-mono uppercase tracking-widest">
                            <div class="flex items-center gap-1.5">
                                <span class="text-[#2255FF]">★ {{ number_format($course->rating ?? 4.9, 1) }}</span>
                            </div>
                            <span class="text-[#555]">·</span>
                            <span class="text-[#555]">({{ number_format($course->reviews_count ?? 2341) }} REVIEWS)</span>
                            <span class="text-[#555]">·</span>
                            <span class="text-[#555]">{{ number_format($course->student_count ?? 12450) }} STUDENTS</span>
                            <span class="text-[#555]">·</span>
                            <span class="text-[#333]">UPDATED JAN 2025</span>
                        </div>

                        {{-- Instructor Mini --}}
                        <div class="flex items-center gap-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name ?? 'Instructor') }}&background=2255FF&color=fff&size=48" 
                                 alt="{{ $course->instructor->name ?? 'Instructor' }}" 
                                 class="w-6 h-6 rounded-full">
                            <span class="text-sm font-sans text-[#F0EDE6]">By {{ $course->instructor->name ?? 'Expert Instructor' }}</span>
                        </div>
                    </div>

                    {{-- RIGHT: 35% SIDEBAR (Sticky on desktop) --}}
                    <div class="hidden lg:block">
                        <div class="sticky top-24 space-y-4">
                            {{-- Thumbnail with Play Button --}}
                            <div class="bg-[#111] border border-[#1E1E1E] overflow-hidden group cursor-pointer" @click="videoModalOpen = true">
                                <div class="relative aspect-video bg-gradient-to-br from-[#2255FF]/20 to-[#111] overflow-hidden">
                                    <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=2255FF&color=fff&size=800' }}" 
                                         alt="{{ $course->title }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/20 group-hover:bg-black/10 transition-colors">
                                        <div class="w-13 h-13 bg-[#2255FF] text-white flex items-center justify-center transition-all duration-300 group-hover:bg-[#F0EDE6] group-hover:text-[#0A0A0A]">
                                            <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Preview Text --}}
                            <p class="text-[10px] font-mono uppercase tracking-widest text-[#555] text-center">
                                Preview This Course
                            </p>

                            {{-- Price Block --}}
                            <div class="space-y-1.5">
                                @if($course->price && $course->price > 0)
                                    <div class="text-[36px] font-display font-black text-[#F0EDE6] leading-none">
                                        ${{ number_format($course->price, 0) }}
                                    </div>
                                    <div class="text-[16px] font-mono text-[#333] line-through">
                                        ${{ number_format($course->price * 1.67, 0) }}
                                    </div>
                                    <div class="text-[#F5A623] text-[10px] font-mono">
                                        40% OFF
                                    </div>
                                @else
                                    <div class="text-[36px] font-display font-black text-[#F0EDE6] leading-none">
                                        FREE
                                    </div>
                                @endif
                            </div>

                            {{-- Countdown Timer (if applicable) --}}
                            <div class="bg-[#111] border border-[#1E1E1E] p-3 space-y-1">
                                <p class="text-[9px] font-mono uppercase tracking-widest text-[#555]">Ends In</p>
                                <div class="text-[24px] font-mono text-[#F5A623] tracking-widest leading-none">
                                    02 : 14 : 33
                                </div>
                            </div>

                            {{-- Enroll Button --}}
                            @if($enrolled)
                                <a href="{{ route('lessons.show', [$course, $course->lessons->first()]) }}" 
                                   class="block w-full h-13 bg-[#2255FF] text-[#F0EDE6] text-center line-clamp-1 leading-13 font-bold uppercase text-sm tracking-widest hover:bg-[#1a3fb3] transition-colors">
                                    Continue Learning
                                </a>
                            @else
                                <form action="{{ $course->price && $course->price > 0 ? route('payments.checkout', $course) : route('courses.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full h-13 bg-[#2255FF] text-[#F0EDE6] font-bold uppercase text-sm tracking-widest hover:bg-[#1a3fb3] transition-colors">
                                        {{ $course->price && $course->price > 0 ? 'Enroll Now' : 'Enroll Free' }}
                                    </button>
                                </form>
                            @endif

                            {{-- Wishlist Button --}}
                            <button class="w-full h-11 border border-[#1E1E1E] text-[#F0EDE6] font-bold uppercase text-xs tracking-widest hover:bg-[#111] transition-colors flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                <span class="hidden sm:inline">Add to Wishlist</span>
                            </button>

                            {{-- Divider --}}
                            <div class="border-t border-[#1E1E1E]"></div>

                            {{-- What's Included --}}
                            <div class="space-y-2.5 text-sm font-sans">
                                <div class="flex items-center gap-3 text-[#555]">
                                    <svg class="w-4 h-4 text-[#333] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m7 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ $course->lessons->count() }} Video Lessons</span>
                                </div>
                                <div class="flex items-center gap-3 text-[#555]">
                                    <svg class="w-4 h-4 text-[#333] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>{{ round(($course->lessons->sum('duration_minutes') ?? 330) / 60, 1) }}h Content</span>
                                </div>
                                <div class="flex items-center gap-3 text-[#555]">
                                    <svg class="w-4 h-4 text-[#333] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <span>Lifetime Access</span>
                                </div>
                            </div>

                            {{-- Share Row --}}
                            <div class="border-t border-[#1E1E1E] pt-3">
                                <p class="text-[9px] font-mono uppercase tracking-widest text-[#555] mb-2.5">Share:</p>
                                <div class="flex gap-2">
                                    <a href="#" class="w-8 h-8 border border-[#1E1E1E] flex items-center justify-center text-[#555] hover:border-[#2255FF] hover:text-[#2255FF] transition-colors">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                    </a>
                                    <a href="#" class="w-8 h-8 border border-[#1E1E1E] flex items-center justify-center text-[#555] hover:border-[#2255FF] hover:text-[#2255FF] transition-colors">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M19.615 3.175c-3.899-.646-6.49-.646-10.488 0C6.618 3.821 5.949 5.446 5.8 8.2c-.149 2.754-.149 6.71 0 9.464.149 2.754.818 4.379 3.327 5.025 3.998.646 6.589.646 10.488 0 2.509-.646 3.178-2.271 3.327-5.025.149-2.754.149-6.71 0-9.464-.149-2.754-.818-4.379-3.327-5.025zM12 16.25c-2.344 0-4.25-1.906-4.25-4.25S9.656 7.75 12 7.75s4.25 1.906 4.25 4.25-1.906 4.25-4.25 4.25zm4.5-7.75c-.552 0-1-.448-1-1s.448-1 1-1 1 .448 1 1-.448 1-1 1z"/></svg>
                                    </a>
                                    <a href="#" class="w-8 h-8 border border-[#1E1E1E] flex items-center justify-center text-[#555] hover:border-[#2255FF] hover:text-[#2255FF] transition-colors">
                                        <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V15.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MOBILE STICKY BOTTOM BAR --}}
        <div class="fixed bottom-0 left-0 right-0 lg:hidden bg-[#111] border-t border-[#1E1E1E] p-3 flex gap-2 z-50">
            <div class="text-base font-display font-black text-[#F0EDE6]">
                @if($course->price && $course->price > 0)
                    ${{ $course->price }}
                @else
                    FREE
                @endif
            </div>
            <form action="{{ $course->price && $course->price > 0 ? route('payments.checkout', $course) : route('courses.enroll', $course) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full h-11 bg-[#2255FF] text-[#F0EDE6] font-bold uppercase text-xs tracking-widest hover:bg-[#1a3fb3] transition-colors">
                    {{ $course->price && $course->price > 0 ? 'Enroll' : 'Free' }}
                </button>
            </form>
        </div>

        {{-- MAIN CONTENT AREA --}}
        <div class="pb-20 lg:pb-8">
            <div class="max-w-full px-4 sm:px-8 lg:px-12 py-16">
                {{-- Tab Navigation --}}
                <div class="flex gap-8 border-b border-[#1E1E1E] mb-12 overflow-x-auto">
                    <button @click="activeTab = 'overview'" 
                            :class="{'border-[#2255FF] text-[#F0EDE6]': activeTab === 'overview', 'border-transparent text-[#555] hover:text-[#F0EDE6]': activeTab !== 'overview'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap">Overview</button>
                    <button @click="activeTab = 'curriculum'" 
                            :class="{'border-[#2255FF] text-[#F0EDE6]': activeTab === 'curriculum', 'border-transparent text-[#555] hover:text-[#F0EDE6]': activeTab !== 'curriculum'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap">Curriculum</button>
                    <button @click="activeTab = 'instructor'" 
                            :class="{'border-[#2255FF] text-[#F0EDE6]': activeTab === 'instructor', 'border-transparent text-[#555] hover:text-[#F0EDE6]': activeTab !== 'instructor'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap">Instructor</button>
                    <button @click="activeTab = 'reviews'" 
                            :class="{'border-[#2255FF] text-[#F0EDE6]': activeTab === 'reviews', 'border-transparent text-[#555] hover:text-[#F0EDE6]': activeTab !== 'reviews'}" 
                            class="pb-4 font-black text-xs uppercase tracking-widest border-b-2 transition-colors whitespace-nowrap">Reviews</button>
                </div>

                {{-- OVERVIEW TAB --}}
                <div x-show="activeTab === 'overview'">
                    {{-- WHAT YOU'LL LEARN --}}
                    <div class="mb-16">
                        <div class="flex items-center gap-4 mb-8 pb-4 border-b border-[#1E1E1E]">
                            <h2 class="text-[3vw] font-display font-black text-[#F0EDE6]">What You'll Learn</h2>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            @for($i = 0; $i < 6; $i++)
                                <div class="flex items-start gap-3">
                                    <span class="text-[#2255FF] font-bold text-xl mt-0.5 flex-shrink-0">→</span>
                                    <p class="text-[13px] font-sans text-[#F0EDE6] leading-relaxed">Advanced techniques and professional workflow patterns used in modern high-end software development.</p>
                                </div>
                            @endfor
                        </div>
                        
                        <button class="px-6 py-3 border border-[#1E1E1E] text-[#F0EDE6] font-bold text-xs uppercase tracking-widest hover:bg-[#111] transition-colors mx-auto block">
                            Show More
                        </button>
                    </div>
                </div>

                {{-- CURRICULUM TAB --}}
                <div x-show="activeTab === 'curriculum'" style="display: none;">
                    <div class="mb-16">
                        <h2 class="text-[3vw] font-display font-black text-[#F0EDE6] mb-8 pb-4 border-b border-[#1E1E1E]">Course Content</h2>
                        
                        <div class="space-y-0 border border-[#1E1E1E]">
                            @php
                                $sections = $course->lessons->groupBy('section_name')->all();
                                if(empty($sections)) {
                                    $sections = [null => $course->lessons];
                                }
                            @endphp
                            
                            @foreach($sections as $sectionIndex => $lessonGroup)
                                <div class="border-b border-[#1E1E1E] last:border-b-0">
                                    {{-- Section Header --}}
                                    <button @click="expandedSection === {{ $loop->index }} ? expandedSection = null : expandedSection = {{ $loop->index }}"
                                            class="w-full h-12 bg-[#111] hover:bg-[#161616] px-6 flex items-center justify-between transition-colors">
                                        <div class="flex items-center gap-3 text-[12px] font-mono uppercase tracking-widest text-[#F0EDE6]">
                                            <span x-show="expandedSection !== {{ $loop->index }}">▸</span>
                                            <span x-show="expandedSection === {{ $loop->index }}" style="display: none;">▾</span>
                                            <span>Section {{ $loop->iteration }}{{ $sectionIndex ? ' — ' . $sectionIndex : ' — Getting Started' }}</span>
                                        </div>
                                        <div class="text-[10px] font-mono uppercase tracking-widest text-[#555]">
                                            {{ $lessonGroup->count() }} LESSONS · {{ round($lessonGroup->sum('duration_minutes') / 60, 1) }}H
                                        </div>
                                    </button>

                                    {{-- Lesson Rows --}}
                                    <div x-show="expandedSection === {{ $loop->index }}" style="display: none;">
                                        @foreach($lessonGroup as $lesson)
                                            @if($enrolled)
                                                <a href="{{ route('lessons.show', [$course, $lesson]) }}" 
                                                   class="group flex items-center h-11 px-6 border-t border-[#1E1E1E] hover:bg-[#161616] transition-colors">
                                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                                        <span class="text-[#333] text-lg flex-shrink-0">▶</span>
                                                        <span class="text-[13px] font-sans text-[#F0EDE6] truncate">{{ $lesson->title }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-4 ml-4 flex-shrink-0">
                                                        <span class="text-[10px] font-mono uppercase tracking-widest text-[#555]">{{ $lesson->duration_minutes ?? 12 }} MIN</span>
                                                        @if($enrolled->completedLessons->contains('id', $lesson->id))
                                                            <svg class="w-4 h-4 text-[#1DB954]" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                                        @endif
                                                    </div>
                                                </a>
                                            @else
                                                <div class="flex items-center h-11 px-6 border-t border-[#1E1E1E] opacity-50">
                                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                                        <svg class="w-5 h-5 text-[#333] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                        <span class="text-[13px] font-sans text-[#F0EDE6] truncate">{{ $lesson->title }}</span>
                                                    </div>
                                                    <span class="text-[10px] font-mono uppercase tracking-widest text-[#555] ml-4">LOCKED</span>
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
                        <h2 class="text-[3vw] font-display font-black text-[#F0EDE6] mb-8 pb-4 border-b border-[#1E1E1E]">About the Instructor</h2>
                        
                        <div class="flex flex-col md:flex-row gap-8">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name ?? 'Instructor') }}&background=2255FF&color=fff&size=160" 
                                     alt="{{ $course->instructor->name ?? 'Instructor' }}" 
                                     class="w-20 h-20 rounded-lg border border-[#1E1E1E]">
                            </div>
                            <div class="flex-1">
                                <h3 class="text-2xl font-display font-black text-[#F0EDE6] mb-2">{{ $course->instructor->name ?? 'Expert Instructor' }}</h3>
                                <p class="text-[11px] font-mono uppercase tracking-widest text-[#555] mb-6">Senior Industry Expert</p>
                                <p class="text-[14px] font-sans text-[#F0EDE6] leading-relaxed mb-6">{{ $course->instructor->bio ?? 'An experienced professional with over 15 years in the industry, focused on delivering high-impact educational content.' }}</p>
                                
                                <div class="grid grid-cols-4 gap-4 mb-6">
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#F0EDE6] mb-1">15K+</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-[#555]">Students</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#F0EDE6] mb-1">4.9★</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-[#555]">Rating</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#F0EDE6] mb-1">12</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-[#555]">Courses</p>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-[24px] font-display font-black text-[#F0EDE6] mb-1">15y</div>
                                        <p class="text-[10px] font-mono uppercase tracking-widest text-[#555]">Experience</p>
                                    </div>
                                </div>

                                <div class="flex gap-3">
                                    <a href="#" class="w-10 h-10 border border-[#1E1E1E] flex items-center justify-center text-[#555] hover:border-[#2255FF] hover:text-[#2255FF] transition-colors rounded">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                    </a>
                                    <a href="#" class="w-10 h-10 border border-[#1E1E1E] flex items-center justify-center text-[#555] hover:border-[#2255FF] hover:text-[#2255FF] transition-colors rounded">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V15.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.129 22 16.99 22 12c0-5.523-4.477-10-10-10z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REVIEWS TAB --}}
                <div x-show="activeTab === 'reviews'" style="display: none;">
                    <div class="mb-16">
                        <h2 class="text-[3vw] font-display font-black text-[#F0EDE6] mb-8 pb-4 border-b border-[#1E1E1E]">Student Reviews</h2>
                        
                        {{-- Overall Rating --}}
                        <div class="flex flex-col md:flex-row gap-12 mb-12">
                            <div class="flex flex-col items-center">
                                <div class="text-[6vw] font-display font-black text-[#F0EDE6]">{{ number_format($course->rating ?? 4.9, 1) }}</div>
                                <div class="flex gap-1.5 text-[#F5A623] my-3">
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    @endfor
                                </div>
                                <p class="text-[10px] font-mono uppercase tracking-widest text-[#555]">AVERAGE RATING</p>
                            </div>

                            <div class="flex-1 space-y-3">
                                @foreach([5 => 78, 4 => 15, 3 => 5, 2 => 1, 1 => 1] as $star => $pct)
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1 w-8 flex-shrink-0">
                                            <span class="text-xs font-black text-[#555]">{{ $star }}</span>
                                            <svg class="w-3 h-3 text-[#F5A623] fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        </div>
                                        <div class="flex-1 h-1 bg-[#1E1E1E] rounded-full overflow-hidden">
                                            <div class="h-full bg-[#2255FF]" style="width: {{ $pct }}%"></div>
                                        </div>
                                        <span class="text-[10px] font-mono uppercase tracking-widest text-[#555] w-8 text-right">{{ $pct }}%</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Individual Reviews --}}
                        <p class="text-[#555] font-bold uppercase tracking-widest text-[10px] text-center py-12">
                            Reviews will be populated dynamically from student feedback.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
