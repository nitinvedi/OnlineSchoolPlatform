<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .mesh-hero { background-color: #0f172a; background-image: radial-gradient(at 0% 0%, hsla(215,100%,20%,1) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(200,100%,15%,1) 0px, transparent 50%); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div x-data="{ videoModalOpen: false, activeTab: 'curriculum' }" class="bg-slate-50 min-h-screen pb-20">
        
        {{-- Video Trailer Modal --}}
        <div x-show="videoModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm" @click="videoModalOpen = false"></div>
            <div class="relative w-full max-w-5xl bg-black rounded-2xl overflow-hidden shadow-2xl z-10 aspect-video">
                <button @click="videoModalOpen = false" class="absolute top-4 right-4 z-20 w-10 h-10 bg-black/50 text-white rounded-full flex items-center justify-center hover:bg-black/80 transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="absolute inset-0 flex items-center justify-center text-slate-500">
                    <p>Course Trailer Video (Placeholder)</p>
                </div>
            </div>
        </div>

        {{-- Split-Screen Hero Layout --}}
        <div class="mesh-hero pt-8 pb-12 lg:pt-16 lg:pb-24 border-b border-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-12 items-center">
                    
                    {{-- Left: Info --}}
                    <div class="flex-1 text-center lg:text-left">
                        <div class="flex items-center justify-center lg:justify-start gap-2 mb-6">
                            <span class="px-3 py-1 bg-sky-500/20 text-sky-400 text-xs font-bold uppercase tracking-wider rounded-lg border border-sky-500/30">{{ $course->category->name }}</span>
                            @if($course->published_at && $course->published_at->diffInDays(now()) < 30)
                                <span class="px-3 py-1 bg-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider rounded-lg border border-rose-500/30">New Release</span>
                            @endif
                        </div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 tracking-tight leading-tight">
                            {{ $course->title }}
                        </h1>
                        <p class="text-lg md:text-xl text-slate-300 font-medium mb-8 max-w-2xl mx-auto lg:mx-0">
                            {{ strip_tags($course->overview) }}
                        </p>
                        
                        <div class="flex flex-wrap items-center justify-center lg:justify-start gap-6 text-slate-300 font-medium">
                            <div class="flex items-center gap-2">
                                <div class="flex gap-0.5 text-amber-400">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <span class="text-white font-bold">{{ number_format($course->rating, 1) }}</span>
                                <span>({{ number_format($course->student_count) }} students)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $course->lessons->count() }} Lessons</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path></svg>
                                <span>English</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right: Thumbnail / Enrollment Card --}}
                    <div class="w-full lg:w-[400px] flex-shrink-0 relative z-20">
                        <div class="bg-white rounded-[2rem] p-2 shadow-2xl border border-slate-100 overflow-hidden">
                            <div class="relative aspect-[4/3] rounded-3xl overflow-hidden group cursor-pointer" @click="videoModalOpen = true">
                                <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=0ea5e9&color=fff&size=800' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700">
                                <div class="absolute inset-0 bg-slate-900/40 group-hover:bg-slate-900/20 transition duration-300 flex items-center justify-center">
                                    <div class="w-16 h-16 bg-white/90 backdrop-blur rounded-full flex items-center justify-center text-sky-500 shadow-xl transform group-hover:scale-110 transition duration-300">
                                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                    </div>
                                </div>
                                <div class="absolute bottom-4 left-0 w-full text-center">
                                    <span class="inline-block px-3 py-1 bg-black/60 backdrop-blur text-white text-xs font-bold rounded-lg shadow-sm">Preview Course</span>
                                </div>
                            </div>
                            
                            <div class="p-6">
                                @if($enrolled)
                                    {{-- Dynamic Progress Overlay --}}
                                    <div class="text-center">
                                        <div class="w-20 h-20 mx-auto relative mb-4">
                                            @php
                                                $percentage = $enrolled->progress_percent ?? 0;
                                                $circumference = 2 * pi() * 36;
                                                $offset = $circumference - ($percentage / 100) * $circumference;
                                            @endphp
                                            <svg class="w-full h-full transform -rotate-90" viewBox="0 0 80 80">
                                                <circle cx="40" cy="40" r="36" fill="transparent" stroke="#f1f5f9" stroke-width="8"></circle>
                                                <circle cx="40" cy="40" r="36" fill="transparent" stroke="#0ea5e9" stroke-width="8" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" class="transition-all duration-1000 ease-out"></circle>
                                            </svg>
                                            <div class="absolute inset-0 flex items-center justify-center">
                                                <span class="text-sm font-bold text-slate-800">{{ $percentage }}%</span>
                                            </div>
                                        </div>
                                        <h3 class="font-bold text-lg text-slate-900 mb-4">You're enrolled!</h3>
                                        
                                        @php
                                            $nextLesson = $course->lessons->first();
                                            foreach($course->lessons as $lesson) {
                                                if (!$enrolled->completedLessons->contains('id', $lesson->id)) {
                                                    $nextLesson = $lesson;
                                                    break;
                                                }
                                            }
                                        @endphp
                                        
                                        <a href="{{ $nextLesson ? route('lessons.show', [$course, $nextLesson]) : '#' }}" class="block w-full py-4 bg-sky-500 hover:bg-sky-600 text-white font-bold rounded-xl text-center transition shadow-lg shadow-sky-500/30">
                                            {{ $nextLesson ? 'Resume Course' : 'Re-watch Course' }}
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center">
                                        <h2 class="text-3xl font-black text-slate-900 mb-6">$49.00 <span class="text-lg text-slate-400 line-through ml-2">$99.00</span></h2>
                                        <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="w-full py-4 bg-sky-500 hover:bg-sky-600 text-white font-bold text-lg rounded-xl transition shadow-lg shadow-sky-500/30">
                                                Enroll Now
                                            </button>
                                        </form>
                                        <p class="text-xs text-slate-500 font-medium mt-4 flex justify-center items-center gap-1">
                                            <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            30-Day Money-Back Guarantee
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex flex-col lg:flex-row gap-12">
                
                {{-- Main Content Column --}}
                <div class="flex-1 min-w-0">
                    
                    {{-- Tabs --}}
                    <div class="flex gap-8 border-b border-slate-200 mb-8 overflow-x-auto hide-scrollbar">
                        <button @click="activeTab = 'curriculum'" :class="{'border-sky-500 text-sky-600': activeTab === 'curriculum', 'border-transparent text-slate-500 hover:text-slate-800': activeTab !== 'curriculum'}" class="pb-4 font-bold text-lg border-b-2 transition whitespace-nowrap">Curriculum</button>
                        <button @click="activeTab = 'instructor'" :class="{'border-sky-500 text-sky-600': activeTab === 'instructor', 'border-transparent text-slate-500 hover:text-slate-800': activeTab !== 'instructor'}" class="pb-4 font-bold text-lg border-b-2 transition whitespace-nowrap">Instructor</button>
                        <button @click="activeTab = 'reviews'" :class="{'border-sky-500 text-sky-600': activeTab === 'reviews', 'border-transparent text-slate-500 hover:text-slate-800': activeTab !== 'reviews'}" class="pb-4 font-bold text-lg border-b-2 transition whitespace-nowrap">Reviews</button>
                    </div>

                    {{-- Curriculum Tab --}}
                    <div x-show="activeTab === 'curriculum'">
                        
                        {{-- What You'll Learn Grid --}}
                        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-6">What you'll learn</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @for($i=0; $i<6; $i++)
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1 w-5 h-5 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="text-slate-600 font-medium text-sm">Build real-world applications from scratch using modern best practices and design patterns.</p>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        {{-- Curriculum Accordion Redesign --}}
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-2xl font-black text-slate-900">Course Content</h3>
                                <span class="text-sm font-bold text-slate-500">{{ $course->lessons->count() }} lectures • 5h 30m total length</span>
                            </div>
                            
                            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden divide-y divide-slate-100">
                                @forelse($course->lessons as $index => $lesson)
                                    <div class="group">
                                        @if($enrolled)
                                            <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="flex items-center p-4 sm:p-6 hover:bg-slate-50 transition">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full {{ $enrolled->completedLessons->contains('id', $lesson->id) ? 'bg-emerald-100 text-emerald-600' : 'bg-sky-50 text-sky-500' }} mr-4 group-hover:scale-110 transition">
                                                    @if($enrolled->completedLessons->contains('id', $lesson->id))
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    @else
                                                        <svg class="w-5 h-5 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-slate-800 text-lg group-hover:text-sky-600 transition">{{ $lesson->title }}</h4>
                                                    <p class="text-xs text-slate-500 font-medium">Video • 12 mins</p>
                                                </div>
                                            </a>
                                        @else
                                            <div class="flex items-center p-4 sm:p-6 opacity-75">
                                                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-slate-100 text-slate-400 mr-4">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-slate-800 text-lg">{{ $lesson->title }}</h4>
                                                    <p class="text-xs text-slate-500 font-medium">Enroll to unlock</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="p-8 text-center text-slate-500">Curriculum is being prepared.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Instructor Tab --}}
                    <div x-show="activeTab === 'instructor'" style="display: none;">
                        <div class="bg-white rounded-[2rem] p-8 md:p-12 border border-slate-100 shadow-sm">
                            <h3 class="text-2xl font-black text-slate-900 mb-8">Meet your instructor</h3>
                            <div class="flex flex-col md:flex-row gap-8">
                                <div class="flex-shrink-0">
                                    <div class="w-32 h-32 rounded-full overflow-hidden shadow-lg border-4 border-white mb-4">
                                        <img src="{{ $course->instructor->avatar_url ? Storage::url($course->instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=0ea5e9&color=fff' }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex flex-col gap-2 text-sm font-bold text-slate-600">
                                        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg> 4.8 Instructor Rating</span>
                                        <span class="flex items-center gap-2"><svg class="w-4 h-4 text-sky-500" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg> 12,450 Students</span>
                                    </div>
                                </div>
                                <div>
                                    <h4 class="text-3xl font-bold text-slate-900 mb-1">{{ $course->instructor->name }}</h4>
                                    <p class="text-sky-600 font-bold mb-6">{{ $course->instructor->headline ?? 'Expert Instructor' }}</p>
                                    <div class="prose prose-slate max-w-none mb-6">
                                        <p>{{ $course->instructor->bio ?? 'An experienced professional dedicated to helping students achieve their goals through high-quality education.' }}</p>
                                    </div>
                                    {{-- Social Links --}}
                                    @if($course->instructor->social_links)
                                        <div class="flex gap-4">
                                            @foreach($course->instructor->social_links as $platform => $url)
                                                @if($url)
                                                    <a href="{{ $url }}" target="_blank" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-sky-500 hover:text-white transition">
                                                        <span class="sr-only">{{ ucfirst($platform) }}</span>
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm3.444 15.908c-.19.043-1.636.196-3.328.196-1.666 0-3.036-.145-3.238-.19-.481-.107-.847-.464-.959-.942-.14-3.111-.14-6.326 0-9.437.111-.478.478-.835.959-.942.202-.045 1.572-.19 3.238-.19 1.692 0 3.138.153 3.328.196.481.107.847.464.959.942.14 3.111.14 6.326 0 9.437-.112.478-.478.835-.959.942zm-4.444-6.331l3.24 1.838-3.24 1.838v-3.676z"/></svg>
                                                    </a>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Reviews Tab --}}
                    <div x-show="activeTab === 'reviews'" style="display: none;">
                        <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-sm mb-8">
                            <h3 class="text-2xl font-black text-slate-900 mb-8">Student Feedback</h3>
                            
                            {{-- Review Breakdown Chart --}}
                            <div class="flex flex-col md:flex-row gap-8 items-center border-b border-slate-100 pb-8 mb-8">
                                <div class="text-center w-full md:w-auto">
                                    <h4 class="text-6xl font-black text-slate-900 mb-2">{{ number_format($course->rating, 1) }}</h4>
                                    <div class="flex justify-center gap-1 text-amber-400 mb-2">
                                        @for($i=0; $i<5; $i++)
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                    <span class="text-slate-500 font-bold text-sm">Course Rating</span>
                                </div>
                                <div class="flex-1 w-full space-y-2">
                                    @for($i=5; $i>=1; $i--)
                                        @php $pct = [5 => 75, 4 => 15, 3 => 5, 2 => 3, 1 => 2][$i]; @endphp
                                        <div class="flex items-center gap-3 text-sm">
                                            <span class="font-bold text-slate-500 w-24 flex items-center gap-1">{{ $i }} <svg class="w-4 h-4 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg></span>
                                            <div class="flex-1 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full bg-amber-400 rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="font-bold text-slate-500 w-10 text-right">{{ $pct }}%</span>
                                        </div>
                                    @endfor
                                </div>
                            </div>

                            <div class="space-y-6">
                                <p class="text-slate-500 italic text-center">Reviews will be populated dynamically here.</p>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Sidebar (Requirements & Target Audience blocks) --}}
                <div class="w-full lg:w-80 flex-shrink-0">
                    <div class="sticky top-24 space-y-6">
                        
                        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                            <h4 class="font-black text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Requirements
                            </h4>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 mt-1.5"></div>
                                    Basic understanding of HTML and CSS
                                </li>
                                <li class="flex items-start gap-2 text-sm text-slate-600 font-medium">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-300 mt-1.5"></div>
                                    No prior programming experience required
                                </li>
                            </ul>
                        </div>

                        <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                            <h4 class="font-black text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                Who is this for?
                            </h4>
                            <p class="text-sm text-slate-600 font-medium">Anyone looking to start a career in web development or build their own projects from scratch.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        
        {{-- Floating "Enrollment Bar" (Mobile) --}}
        @if(!$enrolled)
            <div class="fixed bottom-0 left-0 w-full bg-white border-t border-slate-200 p-4 shadow-[0_-10px_40px_rgba(0,0,0,0.1)] lg:hidden z-50 flex items-center justify-between">
                <div>
                    <h4 class="font-black text-slate-900 text-lg">$49.00</h4>
                </div>
                <form action="{{ route('courses.enroll', $course) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-6 py-2.5 bg-sky-500 text-white font-bold rounded-xl shadow-lg">Enroll Now</button>
                </form>
            </div>
        @endif

    </div>
</x-app-layout>
