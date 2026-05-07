<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .player-theme { background-color: #ffffff; color: #111827; min-height: 100vh; }
        .glass-panel { background: #ffffff; border: 1px solid rgba(229,231,235,1); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        .video-glow { box-shadow: 0 6px 24px rgba(15, 23, 42, 0.06); }
    </style>

        <div x-data="{ sidebarOpen: true, activeTab: 'overview', theaterMode: false }" 
            class="player-theme -mt-8 pt-8 flex flex-col md:flex-row transition-all duration-500 min-h-screen">
        
        {{-- Main Player Area --}}
        <div :class="{'w-full': theaterMode || !sidebarOpen, 'w-full lg:w-[70%] xl:w-[75%]': !theaterMode && sidebarOpen}" 
             class="flex-1 flex flex-col transition-all duration-500 ease-in-out relative z-10">
            
            {{-- Top Bar (Breadcrumbs & Toggles) --}}
            <div class="px-6 py-4 flex items-center justify-between glass-panel rounded-br-2xl md:rounded-none z-20">
                <div class="flex items-center gap-2 text-sm font-semibold text-gray-600">
                    <a href="{{ route('courses.show', $course) }}" class="hover:text-sky-400 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        Back to Course
                    </a>
                    <span class="mx-2 opacity-50">/</span>
                    <span class="text-gray-700 truncate max-w-[200px] sm:max-w-xs">{{ $lesson->title }}</span>
                </div>
                
                <div class="flex items-center gap-3">
                    <button @click="theaterMode = !theaterMode" class="hidden md:flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-gray-100 transition text-sm font-semibold text-gray-700">
                        <svg x-show="!theaterMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"></path></svg>
                        <svg x-show="theaterMode" style="display: none;" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 14h6m0 0v6m0-6l-7 7m17-11h-6m0 0V4m0 6l7-7m-7 17v-6m0 0h6m-6 0l7 7M10 10H4m6 0V4m0 6l-7-7"></path></svg>
                        <span x-text="theaterMode ? 'Exit Theater' : 'Theater Mode'"></span>
                    </button>
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden lg:flex items-center justify-center w-10 h-10 rounded-lg hover:bg-gray-100 transition text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </div>
            </div>

            {{-- Video Container --}}
            <div class="w-full bg-gray-100 relative flex items-center justify-center video-glow" :class="{'aspect-video': true, 'h-screen max-h-screen': theaterMode}">
                @if($lesson->type === 'video' && $lesson->video_url)
                    @php
                        $videoUrl = $lesson->video_url;
                        $isYouTube = preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $videoUrl, $youtubeMatches);
                        $isVimeo = preg_match('/(?:vimeo\.com\/)(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|)(\d+)(?:$|\/|\?)/i', $videoUrl, $vimeoMatches);
                        $isLocal = str_starts_with($videoUrl, 'lessons/');
                    @endphp

                    @if($isYouTube && isset($youtubeMatches[1]))
                        <iframe class="w-full h-full absolute inset-0" src="https://www.youtube.com/embed/{{ $youtubeMatches[1] }}?rel=0" frameborder="0" allowfullscreen></iframe>
                    @elseif($isVimeo && isset($vimeoMatches[3]))
                        <iframe class="w-full h-full absolute inset-0" src="https://player.vimeo.com/video/{{ $vimeoMatches[3] }}" frameborder="0" allowfullscreen></iframe>
                    @else
                        <video id="lesson-video" controls class="w-full h-full absolute inset-0 object-contain bg-black" controlsList="nodownload">
                            <source src="{{ $isLocal ? asset('storage/' . $videoUrl) : $videoUrl }}" type="video/mp4">
                        </video>
                    @endif
                @elseif($lesson->type === 'quiz')
                        <div class="text-center p-8">
                        <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg shadow-purple-100 transform -rotate-6">
                            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor"><path d="M3 3h18v2H3V3zm2 6h14v2H5V9zm0 6h14v2H5v-2z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black text-gray-900 mb-4">Knowledge Check</h2>
                        <p class="text-gray-600 font-medium max-w-md mx-auto mb-8">Test what you've learned in this section to ensure you're ready to move forward.</p>
                        @if($enrolled && $lesson->quiz)
                            <a href="{{ route('quizzes.show', [$course, $lesson, $lesson->quiz]) }}" class="inline-block px-8 py-4 bg-white text-indigo-900 font-black rounded-xl hover:scale-105 transition shadow-xl">
                                Start Assessment
                            </a>
                        @endif
                    </div>
                @else
                        <div class="text-center p-8">
                        <div class="w-20 h-20 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                            <svg class="w-8 h-8 text-gray-600" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h16v2H4V4zm0 6h16v2H4v-2zm0 6h16v2H4v-2z"/></svg>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Text Lesson</h2>
                        <p class="text-gray-600">Scroll down to read the content.</p>
                    </div>
                @endif
            </div>

            {{-- Content Area (Below Video) --}}
            <div class="flex-1 overflow-y-auto px-4 sm:px-8 py-8" x-show="!theaterMode">
                <div class="max-w-4xl mx-auto">
                    
                    {{-- Title & Actions --}}
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-10">
                        <div>
                            <h1 class="text-3xl sm:text-4xl font-black text-gray-900 mb-2 leading-tight">{{ $lesson->title }}</h1>
                            <p class="text-gray-600 font-medium">Lesson {{ $currentIndex }} of {{ $totalLessons }} @if($lesson->duration_minutes) • {{ $lesson->duration_minutes }} mins @endif</p>
                        </div>
                        
                        <div class="flex-shrink-0 flex items-center gap-3">
                            @if($enrolled && !$enrolled->isLessonCompleted($lesson))
                                <form action="{{ route('lessons.complete', [$course, $lesson]) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="group relative px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-lg shadow-sm transition-all flex items-center gap-2 overflow-hidden">
                                        <div class="absolute inset-0 w-full h-full bg-white/20 transform -skew-x-12 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                                        <svg class="w-5 h-5 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        <span class="relative z-10">Mark Complete</span>
                                    </button>
                                </form>
                            @elseif($enrolled && $enrolled->isLessonCompleted($lesson))
                                <div class="px-6 py-3 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 font-bold rounded-xl flex items-center gap-2 cursor-default">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                    Completed
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Tabs --}}
                        <div class="flex gap-8 border-b border-gray-200 mb-8 overflow-x-auto hide-scrollbar">
                        <button @click="activeTab = 'overview'" :class="{'border-violet-500 text-gray-900': activeTab === 'overview', 'border-transparent text-gray-600 hover:text-gray-700': activeTab !== 'overview'}" class="pb-4 font-bold text-lg border-b-2 transition whitespace-nowrap">Overview</button>
                        @if($lesson->type === 'text')
                            <button @click="activeTab = 'content'" :class="{'border-sky-500 text-white': activeTab === 'content', 'border-transparent text-slate-500 hover:text-slate-300': activeTab !== 'content'}" class="pb-4 font-bold text-lg border-b-2 transition whitespace-nowrap">Content</button>
                        @endif
                        <button @click="activeTab = 'qa'" :class="{'border-sky-500 text-white': activeTab === 'qa', 'border-transparent text-slate-500 hover:text-slate-300': activeTab !== 'qa'}" class="pb-4 font-bold text-lg border-b-2 transition whitespace-nowrap">Q&A</button>
                    </div>

                    {{-- Tab Panels --}}
                    <div class="min-h-[300px]">
                        {{-- Overview --}}
                        <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                            @if($lesson->description)
                                <div class="glass-panel p-6 sm:p-8 rounded-2xl mb-8">
                                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-violet-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        About this lesson
                                    </h3>
                                    <p class="text-gray-700 leading-relaxed font-medium">{{ $lesson->description }}</p>
                                </div>
                            @endif

                            {{-- Navigation Buttons --}}
                            <div class="flex justify-between items-center gap-4 pt-8 border-t border-gray-200">
                                @if($previousLesson)
                                    <a href="{{ route('lessons.show', [$course, $previousLesson]) }}" class="flex items-center gap-2 text-gray-600 hover:text-gray-900 font-semibold transition group">
                                        <div class="w-10 h-10 rounded-full glass-panel flex items-center justify-center group-hover:bg-gray-100 transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                        </div>
                                        <span class="hidden sm:inline">Previous</span>
                                    </a>
                                @else <div></div> @endif

                                @if($nextLesson)
                                    <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="flex items-center gap-2 text-gray-900 font-semibold transition group">
                                        <span class="hidden sm:inline">Next Lesson</span>
                                        <div class="w-10 h-10 rounded-full bg-violet-600 flex items-center justify-center group-hover:bg-violet-700 shadow-sm transition transform group-hover:scale-105">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </div>
                                    </a>
                                @else
                                    <div class="text-emerald-400 font-bold flex items-center gap-2">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        Course Complete!
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Content (For Text lessons) --}}
                        @if($lesson->type === 'text')
                            <div x-show="activeTab === 'content'" style="display: none;" class="glass-panel rounded-2xl p-6 sm:p-10 shadow-sm">
                                <div class="prose max-w-none prose-lg text-gray-700 font-medium">
                                    {!! nl2br(e($lesson->content)) !!}
                                </div>
                            </div>
                        @endif

                        {{-- Q&A Mock --}}
                        <div x-show="activeTab === 'qa'" style="display: none;" class="text-center py-12">
                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">No Questions Yet</h3>
                            <p class="text-gray-600 mb-6">Be the first to ask a question about this lesson.</p>
                            <button class="px-6 py-3 bg-white border border-gray-200 text-gray-800 font-semibold rounded-lg transition">Ask a Question</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Expandable Sidebar Navigation --}}
            <div x-show="sidebarOpen" style="display: none;" 
             x-transition:enter="transition ease-out duration-300" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0" 
             x-transition:leave="transition ease-in duration-300" x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full"
             class="fixed lg:relative top-0 right-0 h-screen w-80 sm:w-96 glass-panel border-l border-gray-100 z-50 flex flex-col flex-shrink-0 shadow-sm overflow-hidden">
            
            <div class="p-6 border-b border-gray-100 flex justify-between items-center bg-white">
                <h3 class="font-black text-gray-900 text-lg">Course Content</h3>
                <button @click="sidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            @if($enrolled)
                <div class="px-6 py-4 bg-white border-b border-gray-100">
                    <div class="flex justify-between text-xs font-semibold text-gray-500 mb-2">
                        <span>Progress</span>
                        <span class="text-violet-600">{{ $enrolled->progress_percent }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-violet-600 h-1.5 rounded-full" style="width: {{ $enrolled->progress_percent }}%"></div>
                    </div>
                </div>
            @endif

            <div class="flex-1 overflow-y-auto hide-scrollbar p-4 space-y-2">
                @foreach($lessons as $index => $courseLesson)
                    @php
                        $isActive = $courseLesson->id === $lesson->id;
                        $isCompleted = $enrolled && $enrolled->isLessonCompleted($courseLesson);
                        $isLocked = !$enrolled; // In actual logic, implement sequential locking here if needed
                    @endphp
                    <a href="{{ $isLocked ? '#' : route('lessons.show', [$course, $courseLesson]) }}" 
                       class="group block p-4 rounded-xl transition-all duration-300 relative overflow-hidden {{ $isActive ? 'bg-sky-500/10 border border-sky-500/30' : 'hover:bg-white/5 border border-transparent' }} {{ $isLocked ? 'opacity-50 cursor-not-allowed' : '' }}">
                        
                        @if($isActive)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-sky-500 rounded-l-xl"></div>
                        @endif

                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($isCompleted)
                                    <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center border border-emerald-500/30">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @elseif($isLocked)
                                    <div class="w-6 h-6 rounded-full bg-slate-800 text-slate-500 flex items-center justify-center border border-slate-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                    </div>
                                @else
                                    <div class="w-6 h-6 rounded-full border border-slate-600 flex items-center justify-center text-[10px] font-bold text-slate-400 {{ $isActive ? 'border-sky-500 text-sky-400' : '' }}">
                                        {{ $index + 1 }}
                                    </div>
                                @endif
                            </div>
                            
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-bold truncate {{ $isActive ? 'text-white' : 'text-slate-300 group-hover:text-white' }}">{{ $courseLesson->title }}</h4>
                                <div class="flex items-center gap-2 mt-1.5 text-[10px] font-bold uppercase tracking-wider text-slate-500">
                                    @if($courseLesson->type === 'video')
                                        <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg> Video</span>
                                    @elseif($courseLesson->type === 'quiz')
                                        <span class="flex items-center gap-1 text-purple-400"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg> Quiz</span>
                                    @else
                                        <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Article</span>
                                    @endif
                                    
                                    @if($courseLesson->duration_minutes)
                                        <span class="opacity-50">•</span>
                                        <span>{{ $courseLesson->duration_minutes }}m</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @if(session('success'))
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if($enrolled && $enrolled->isCompleted())
                    var duration = 5 * 1000;
                    var animationEnd = Date.now() + duration;
                    var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 10000 };
                    function randomInRange(min, max) { return Math.random() * (max - min) + min; }
                    var interval = setInterval(function() {
                        var timeLeft = animationEnd - Date.now();
                        if (timeLeft <= 0) { return clearInterval(interval); }
                        var particleCount = 50 * (timeLeft / duration);
                        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                        confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
                    }, 250);
                @else
                    confetti({ particleCount: 100, spread: 70, origin: { y: 0.6 }, colors: ['#0ea5e9', '#8b5cf6', '#ec4899'] });
                @endif
            });
        </script>
    @endif

    {{-- Next Lesson Auto-Preview --}}
    @if($nextLesson)
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const video = document.getElementById('lesson-video');
                if (video) {
                    video.addEventListener('ended', function() {
                        let countdown = 5;
                        const overlay = document.createElement('div');
                        overlay.className = 'absolute inset-0 bg-black/90 backdrop-blur-sm flex flex-col items-center justify-center text-white z-[60] opacity-0 transition-opacity duration-500';
                        overlay.innerHTML = `
                            <p class="text-sky-400 font-bold tracking-widest uppercase text-sm mb-4">Up Next</p>
                            <h3 class="text-3xl font-black mb-8 text-center px-4">${"{{ addslashes($nextLesson->title) }}"}</h3>
                            <div class="relative w-24 h-24 mb-8">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                    <circle cx="50" cy="50" r="45" fill="none" stroke="#1e293b" stroke-width="10"></circle>
                                    <circle id="countdown-circle" cx="50" cy="50" r="45" fill="none" stroke="#0ea5e9" stroke-width="10" stroke-dasharray="283" stroke-dashoffset="0" class="transition-all duration-1000 linear"></circle>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center text-2xl font-black" id="countdown">${countdown}</div>
                            </div>
                            <div class="flex gap-4">
                                <a href="{{ route('lessons.show', [$course, $nextLesson]) }}" class="px-8 py-3 bg-white text-slate-900 rounded-xl font-black hover:scale-105 transition shadow-xl">Play Now</a>
                                <button id="cancel-autoplay" class="px-8 py-3 glass-panel text-white rounded-xl font-bold hover:bg-white/10 transition">Cancel</button>
                            </div>
                        `;
                        document.querySelector('#lesson-video').parentElement.appendChild(overlay);
                        setTimeout(() => overlay.classList.remove('opacity-0'), 100);

                        const interval = setInterval(() => {
                            countdown--;
                            const countSpan = document.getElementById('countdown');
                            const circle = document.getElementById('countdown-circle');
                            if (countSpan) countSpan.innerText = countdown;
                            if (circle) circle.style.strokeDashoffset = 283 - (283 * (countdown / 5));
                            
                            if (countdown <= 0) {
                                clearInterval(interval);
                                window.location.href = "{{ route('lessons.show', [$course, $nextLesson]) }}";
                            }
                        }, 1000);

                        document.getElementById('cancel-autoplay').addEventListener('click', function() {
                            clearInterval(interval);
                            overlay.classList.add('opacity-0');
                            setTimeout(() => overlay.remove(), 500);
                        });
                    });
                }
            });
        </script>
    @endif
</x-app-layout>
