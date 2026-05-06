<x-app-layout>
    <div x-data="{ videoModalOpen: false, activeTab: 'curriculum' }" class="min-h-screen pb-32">
        
        {{-- Video Trailer Modal --}}
        <div x-show="videoModalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95">
            <div class="fixed inset-0 bg-dark-bg/95 backdrop-blur-md" @click="videoModalOpen = false"></div>
            <div class="relative w-full max-w-5xl bg-black rounded-[2.5rem] overflow-hidden shadow-2xl z-10 aspect-video border border-white/10">
                <button @click="videoModalOpen = false" class="absolute top-6 right-6 z-20 w-12 h-12 bg-white/10 hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all duration-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
                <div class="absolute inset-0 flex items-center justify-center text-slate-500 bg-dark-card">
                    <p class="font-bold uppercase tracking-widest text-sm">Course Trailer Placeholder</p>
                </div>
            </div>
        </div>

        {{-- Hero Section --}}
        <div class="relative pt-12 pb-24 border-b border-white/5 overflow-hidden">
            <div class="absolute inset-0 z-0 opacity-5 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:40px_40px]"></div>
            
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                <div class="flex flex-col lg:flex-row gap-16 items-start">
                    
                    {{-- Left Info --}}
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-8">
                            <span class="px-4 py-1.5 bg-brand-500/10 text-brand-500 text-[10px] font-black uppercase tracking-[0.2em] rounded-lg border border-brand-500/20">{{ $course->category->name }}</span>
                            @if($course->published_at && $course->published_at->diffInDays(now()) < 30)
                                <span class="px-4 py-1.5 bg-white/5 text-white text-[10px] font-black uppercase tracking-[0.2em] rounded-lg border border-white/10">New Release</span>
                            @endif
                        </div>
                        
                        <h1 class="text-4xl md:text-6xl font-display font-black text-white mb-8 tracking-tighter leading-[1.1]">
                            {{ $course->title }}
                        </h1>
                        
                        <p class="text-xl text-slate-400 font-medium leading-relaxed mb-12 max-w-3xl">
                            {{ strip_tags($course->overview) }}
                        </p>
                        
                        <div class="flex flex-wrap items-center gap-8 text-slate-300 font-bold text-xs uppercase tracking-widest">
                            <div class="flex items-center gap-2.5">
                                <div class="flex gap-1 text-amber-400">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                                <span class="text-white">{{ number_format($course->rating, 1) }}</span>
                                <span class="opacity-50">({{ number_format($course->student_count) }} Students)</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>{{ $course->lessons->count() }} Lessons</span>
                            </div>
                            <div class="flex items-center gap-2.5">
                                <svg class="w-4 h-4 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>English</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right Card --}}
                    <div class="w-full lg:w-96 flex-shrink-0">
                        <div class="bg-dark-card border border-white/5 rounded-[3rem] p-3 shadow-2xl">
                            <div class="relative aspect-[4/3] rounded-[2.2rem] overflow-hidden group cursor-pointer" @click="videoModalOpen = true">
                                <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=121217&color=fff&size=800' }}" class="w-full h-full object-cover group-hover:scale-110 transition duration-700 opacity-80 group-hover:opacity-100">
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <div class="w-16 h-16 bg-white text-brand-500 rounded-full flex items-center justify-center shadow-2xl transform group-hover:scale-110 transition duration-500">
                                        <svg class="w-8 h-8 ml-1" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                    </div>
                                </div>
                                <div class="absolute bottom-6 left-0 w-full text-center">
                                    <span class="inline-block px-4 py-2 bg-dark-bg/60 backdrop-blur-md text-white text-[10px] font-black uppercase tracking-widest rounded-xl border border-white/10">Preview Course</span>
                                </div>
                            </div>
                            
                            <div class="p-8">
                                @if($enrolled)
                                    <div class="text-center">
                                        <div class="mb-6">
                                            <div class="flex justify-between items-center mb-3">
                                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Your Progress</span>
                                                <span class="text-sm font-black text-white">{{ $enrolled->progress_percent ?? 0 }}%</span>
                                            </div>
                                            <div class="h-2 bg-white/5 rounded-full overflow-hidden">
                                                <div class="h-full bg-brand-500 rounded-full transition-all duration-1000" style="width: {{ $enrolled->progress_percent ?? 0 }}%"></div>
                                            </div>
                                        </div>
                                        
                                        @php
                                            $nextLesson = $course->lessons->first();
                                            foreach($course->lessons as $lesson) {
                                                if (!$enrolled->completedLessons->contains('id', $lesson->id)) { $nextLesson = $lesson; break; }
                                            }
                                        @endphp
                                        
                                        <a href="{{ $nextLesson ? route('lessons.show', [$course, $nextLesson]) : '#' }}" class="btn-primary block w-full py-5 text-center">
                                            {{ $nextLesson ? 'Resume Learning' : 'Re-watch Course' }}
                                        </a>
                                    </div>
                                @else
                                    <div class="text-center">
                                        @if($course->price && $course->price > 0)
                                            <div class="flex items-center justify-center gap-3 mb-8">
                                                <span class="text-4xl font-display font-black text-white">${{ number_format($course->price, 0) }}</span>
                                                <span class="text-xl text-slate-500 line-through font-bold">${{ number_format($course->price * 2, 0) }}</span>
                                            </div>
                                            <form action="{{ route('payments.checkout', $course) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn-primary w-full py-5 text-xl">
                                                    Buy Now
                                                </button>
                                            </form>
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-6 flex justify-center items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                30-Day Money-Back Guarantee
                                            </p>
                                        @else
                                            <form action="{{ route('courses.enroll', $course) }}" method="POST" class="mb-8">
                                                @csrf
                                                <button type="submit" class="btn-primary w-full py-5 text-xl">
                                                    Enroll Free
                                                </button>
                                            </form>
                                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest flex justify-center items-center gap-2">
                                                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                Free Course
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="flex flex-col lg:flex-row gap-16">
                
                {{-- Main Content --}}
                <div class="flex-1 min-w-0">
                    
                    {{-- Tabs --}}
                    <div class="flex gap-12 border-b border-white/5 mb-12 overflow-x-auto hide-scrollbar">
                        <button @click="activeTab = 'curriculum'" :class="{'border-brand-500 text-brand-500': activeTab === 'curriculum', 'border-transparent text-slate-500 hover:text-white': activeTab !== 'curriculum'}" class="pb-6 font-black text-xs uppercase tracking-[0.2em] border-b-2 transition-all whitespace-nowrap">Curriculum</button>
                        <button @click="activeTab = 'instructor'" :class="{'border-brand-500 text-brand-500': activeTab === 'instructor', 'border-transparent text-slate-500 hover:text-white': activeTab !== 'instructor'}" class="pb-6 font-black text-xs uppercase tracking-[0.2em] border-b-2 transition-all whitespace-nowrap">Instructor</button>
                        <button @click="activeTab = 'reviews'" :class="{'border-brand-500 text-brand-500': activeTab === 'reviews', 'border-transparent text-slate-500 hover:text-white': activeTab !== 'reviews'}" class="pb-6 font-black text-xs uppercase tracking-[0.2em] border-b-2 transition-all whitespace-nowrap">Reviews</button>
                    </div>

                    {{-- Curriculum Tab --}}
                    <div x-show="activeTab === 'curriculum'" class="space-y-12">
                        
                        <div class="bg-dark-card border border-white/5 rounded-[3rem] p-12">
                            <h3 class="text-2xl font-display font-black text-white mb-8">What you'll master</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @for($i=0; $i<6; $i++)
                                    <div class="flex items-start gap-4">
                                        <div class="mt-1 w-6 h-6 rounded-lg bg-brand-500/10 flex items-center justify-center flex-shrink-0 text-brand-500 border border-brand-500/20">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                        </div>
                                        <p class="text-slate-400 font-medium leading-relaxed">Advanced techniques and professional workflow patterns used in modern high-end software development.</p>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-8">
                                <h3 class="text-2xl font-display font-black text-white">Course Content</h3>
                                <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">{{ $course->lessons->count() }} Lectures • 5.5 Hours Total</span>
                            </div>
                            
                            <div class="bg-dark-card border border-white/5 rounded-[2.5rem] overflow-hidden divide-y divide-white/5">
                                @forelse($course->lessons as $index => $lesson)
                                    <div class="group">
                                        @if($enrolled)
                                            <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="flex items-center p-6 hover:bg-white/5 transition-all duration-300">
                                                <div class="flex items-center justify-center w-12 h-12 rounded-2xl {{ $enrolled->completedLessons->contains('id', $lesson->id) ? 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20' : 'bg-brand-500/10 text-brand-500 border-brand-500/20' }} border mr-6 group-hover:scale-110 transition-transform duration-300">
                                                    @if($enrolled->completedLessons->contains('id', $lesson->id))
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                    @else
                                                        <svg class="w-6 h-6 ml-0.5" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                                    @endif
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-white group-hover:text-brand-500 transition-colors">{{ $lesson->title }}</h4>
                                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Video • 12 Mins</p>
                                                </div>
                                            </a>
                                        @else
                                            <div class="flex items-center p-6 opacity-40">
                                                <div class="flex items-center justify-center w-12 h-12 rounded-2xl bg-white/5 border border-white/10 text-slate-500 mr-6">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-bold text-white">{{ $lesson->title }}</h4>
                                                    <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mt-1">Enroll to unlock</p>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @empty
                                    <div class="p-12 text-center text-slate-500 font-bold uppercase tracking-widest text-xs">Curriculum is being prepared.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Instructor Tab --}}
                    <div x-show="activeTab === 'instructor'" style="display: none;">
                        <div class="bg-dark-card border border-white/5 rounded-[3rem] p-12">
                            <div class="flex flex-col md:flex-row gap-12 items-start">
                                <div class="flex-shrink-0 text-center md:text-left">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name) }}&background=3b67f5&color=fff&size=200" 
                                         class="w-40 h-40 rounded-[2.5rem] border-4 border-white/5 shadow-2xl mb-6">
                                    <div class="space-y-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">
                                        <div class="flex items-center justify-center md:justify-start gap-2 text-brand-500">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            <span>4.9 Instructor Rating</span>
                                        </div>
                                        <div class="flex items-center justify-center md:justify-start gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                            <span>15,000+ Students</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-4xl font-display font-black text-white mb-2">{{ $course->instructor->name }}</h4>
                                    <p class="text-brand-500 font-black text-xs uppercase tracking-[0.2em] mb-8">{{ $course->instructor->headline ?? 'Senior Industry Expert' }}</p>
                                    <div class="prose prose-invert max-w-none mb-10 text-slate-400 font-medium leading-relaxed">
                                        <p>{{ $course->instructor->bio ?? 'An experienced professional with over 15 years in the industry, focused on delivering high-impact educational content for modern developers.' }}</p>
                                    </div>
                                    <div class="flex gap-4">
                                        <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-brand-500 hover:border-brand-500 transition-all duration-300">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                        </a>
                                        <a href="#" class="w-12 h-12 rounded-2xl bg-white/5 border border-white/10 flex items-center justify-center text-white hover:bg-brand-500 hover:border-brand-500 transition-all duration-300">
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Reviews Tab --}}
                    <div x-show="activeTab === 'reviews'" style="display: none;">
                        <div class="bg-dark-card border border-white/5 rounded-[3rem] p-12">
                            <div class="flex flex-col md:flex-row gap-12 items-center mb-12">
                                <div class="text-center">
                                    <h4 class="text-7xl font-display font-black text-white mb-4">{{ number_format($course->rating, 1) }}</h4>
                                    <div class="flex justify-center gap-1.5 text-amber-400 mb-2">
                                        @for($i=0; $i<5; $i++)
                                            <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        @endfor
                                    </div>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Average Rating</span>
                                </div>
                                <div class="flex-1 w-full space-y-4">
                                    @foreach([5 => 88, 4 => 8, 3 => 2, 2 => 1, 1 => 1] as $star => $pct)
                                        <div class="flex items-center gap-6">
                                            <div class="flex items-center gap-2 w-12 flex-shrink-0">
                                                <span class="text-xs font-black text-slate-400">{{ $star }}</span>
                                                <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                            </div>
                                            <div class="flex-1 h-1.5 bg-white/5 rounded-full overflow-hidden">
                                                <div class="h-full bg-brand-500 rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="text-[10px] font-black text-slate-500 w-8 text-right">{{ $pct }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <p class="text-slate-500 font-bold uppercase tracking-widest text-[10px] text-center">Reviews will be populated dynamically from student feedback.</p>
                        </div>
                    </div>

                </div>

                {{-- Sidebar --}}
                <div class="w-full lg:w-96 flex-shrink-0">
                    <div class="sticky top-28 space-y-8">
                        <div class="bg-dark-card border border-white/5 rounded-[2.5rem] p-10">
                            <h4 class="font-display font-black text-white text-xl mb-8 flex items-center gap-3">
                                <svg class="w-6 h-6 text-brand-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Prerequisites
                            </h4>
                            <ul class="space-y-4">
                                <li class="flex items-start gap-3 text-slate-400 font-medium">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-500 mt-2 flex-shrink-0"></div>
                                    Fundamental understanding of development workflows.
                                </li>
                                <li class="flex items-start gap-3 text-slate-400 font-medium">
                                    <div class="w-1.5 h-1.5 rounded-full bg-brand-500 mt-2 flex-shrink-0"></div>
                                    Modern code editor and terminal basics.
                                </li>
                            </ul>
                        </div>

                        <div class="bg-brand-500 rounded-[2.5rem] p-10 text-white relative overflow-hidden">
                            <div class="relative z-10">
                                <h4 class="font-display font-black text-xl mb-6 flex items-center gap-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                    Target Audience
                                </h4>
                                <p class="font-medium leading-relaxed text-brand-50 text-sm">Professional developers looking to transition to high-end architectural roles or master advanced full-stack patterns.</p>
                            </div>
                            <svg class="absolute -bottom-10 -right-10 w-48 h-48 text-white/10" fill="currentColor" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 00-3-3.87"></path><path d="M16 3.13a4 4 0 010 7.75"></path></svg>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
