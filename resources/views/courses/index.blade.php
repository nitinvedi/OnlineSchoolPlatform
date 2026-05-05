<x-app-layout>
    {{-- Immersive Search Header --}}
    <div class="relative bg-slate-900 py-20 overflow-hidden">
        {{-- Background Effects --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-1/2 -right-1/4 w-[1000px] h-[1000px] rounded-full bg-gradient-to-b from-sky-500/20 to-transparent blur-3xl mix-blend-overlay"></div>
            <div class="absolute -bottom-1/2 -left-1/4 w-[800px] h-[800px] rounded-full bg-gradient-to-t from-indigo-500/20 to-transparent blur-3xl mix-blend-overlay"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight">What do you want to learn today?</h1>
            
            <form action="{{ route('courses.index') }}" method="GET" class="max-w-3xl mx-auto relative group">
                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-slate-400 group-focus-within:text-sky-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for 'Laravel API', 'UI Design', 'PHP Masterclass'..." 
                    class="block w-full pl-14 pr-32 py-5 border-2 border-slate-700/50 rounded-2xl leading-5 bg-slate-800/80 backdrop-blur-xl text-white placeholder-slate-400 focus:outline-none focus:ring-0 focus:border-sky-500 text-lg shadow-2xl transition-all duration-300">
                <div class="absolute inset-y-2 right-2 flex items-center">
                    <button type="submit" class="px-6 py-3 bg-sky-500 text-white font-bold rounded-xl hover:bg-sky-400 transition-colors shadow-lg">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Main Catalog Area (AlpineJS for interactivity) --}}
    <div x-data="{ viewMode: 'grid', isLoading: true }" x-init="setTimeout(() => isLoading = false, 800)" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        
        {{-- Filters & Controls Row --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
            
            {{-- Pill-Based Category Filters --}}
            <div class="flex-1 overflow-x-auto hide-scrollbar pb-2 -mb-2">
                <div class="flex gap-3">
                    <a href="{{ route('courses.index') }}" class="whitespace-nowrap px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-sm {{ !request('category') ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                        All Courses
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('courses.index', ['category' => $category->id]) }}" class="whitespace-nowrap px-5 py-2.5 rounded-full font-bold text-sm transition-all shadow-sm {{ request('category') == $category->id ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 hover:bg-slate-50 border border-slate-200' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Controls (Sort & View Toggle) --}}
            <div class="flex items-center gap-4 flex-shrink-0">
                <form action="{{ route('courses.index') }}" method="GET" x-ref="sortForm">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    <select name="sort" onchange="$refs.sortForm.submit()" class="bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-xl py-2.5 pl-4 pr-10 focus:ring-sky-500 focus:border-sky-500 shadow-sm cursor-pointer hover:bg-slate-50 transition">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Releases</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                    </select>
                </form>

                {{-- Advanced Grid/List Toggle --}}
                <div class="hidden sm:flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                    <button @click="viewMode = 'grid'" :class="{'bg-slate-100 text-slate-900': viewMode === 'grid', 'text-slate-400 hover:text-slate-600': viewMode !== 'grid'}" class="p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                    <button @click="viewMode = 'list'" :class="{'bg-slate-100 text-slate-900': viewMode === 'list', 'text-slate-400 hover:text-slate-600': viewMode !== 'list'}" class="p-1.5 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Skeleton Loading State --}}
        <div x-show="isLoading" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for($i=0; $i<6; $i++)
                <div class="bg-white rounded-[2rem] p-4 border border-slate-100 shadow-sm animate-pulse">
                    <div class="w-full aspect-[16/9] bg-slate-200 rounded-2xl mb-4"></div>
                    <div class="h-4 bg-slate-200 rounded w-1/4 mb-3"></div>
                    <div class="h-6 bg-slate-200 rounded w-3/4 mb-4"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-slate-200"></div>
                        <div class="h-4 bg-slate-200 rounded w-1/3"></div>
                    </div>
                </div>
            @endfor
        </div>

        {{-- Real Content --}}
        <div x-show="!isLoading" style="display: none;" 
             :class="{'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8': viewMode === 'grid', 'flex flex-col gap-6': viewMode === 'list'}">
            
            @forelse($courses as $course)
                <div :class="{'flex-col': viewMode === 'grid', 'flex-col sm:flex-row': viewMode === 'list'}" 
                     class="flex bg-white rounded-[2rem] shadow-sm hover:shadow-2xl transition-all duration-500 border border-slate-100 group overflow-hidden relative">
                    
                    {{-- "New & Trending" Badges --}}
                    @if($course->published_at && $course->published_at->diffInDays(now()) < 30)
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3 py-1 bg-gradient-to-r from-pink-500 to-rose-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg">New</span>
                        </div>
                    @elseif($course->student_count > 100)
                        <div class="absolute top-4 left-4 z-20">
                            <span class="px-3 py-1 bg-gradient-to-r from-amber-400 to-orange-500 text-white text-[10px] font-black uppercase tracking-widest rounded-lg shadow-lg">Hot</span>
                        </div>
                    @endif

                    {{-- Cinematic Thumbnail --}}
                    <div :class="{'w-full': viewMode === 'grid', 'w-full sm:w-72 sm:min-w-[18rem]': viewMode === 'list'}" class="relative aspect-[16/9] sm:aspect-auto overflow-hidden bg-slate-900 p-2 sm:p-0">
                        <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=1e293b&color=fff&size=600' }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700 opacity-90 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/60 to-transparent"></div>
                        
                        {{-- Difficulty Indicator (bottom right over image) --}}
                        <div class="absolute bottom-3 right-3 flex items-end gap-0.5" title="Intermediate Level">
                            <div class="w-1 h-3 bg-white rounded-full"></div>
                            <div class="w-1 h-4 bg-white rounded-full"></div>
                            <div class="w-1 h-5 bg-white/30 rounded-full"></div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex-1 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold text-sky-500 uppercase tracking-wider">{{ $course->category->name }}</span>
                                <div class="flex items-center gap-1 text-amber-400">
                                    <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-sm font-bold text-slate-700">{{ number_format($course->rating, 1) }}</span>
                                </div>
                            </div>
                            <h2 class="text-xl font-black text-slate-900 mb-2 leading-tight group-hover:text-sky-600 transition-colors line-clamp-2">
                                <a href="{{ route('courses.show', $course) }}" class="focus:outline-none">
                                    <span class="absolute inset-0" aria-hidden="true"></span>
                                    {{ $course->title }}
                                </a>
                            </h2>
                            <p class="text-slate-500 text-sm font-medium mb-6 line-clamp-2" x-show="viewMode === 'list'">
                                {{ Str::limit(strip_tags($course->overview), 120) }}
                            </p>
                        </div>
                        
                        <div class="flex items-center justify-between mt-auto border-t border-slate-50 pt-4 relative z-10">
                            {{-- Instructor Avatar Stack --}}
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200 overflow-hidden shadow-sm relative z-20">
                                    <img src="{{ $course->instructor->avatar_url ? Storage::url($course->instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=e2e8f0' }}" class="w-full h-full object-cover">
                                </div>
                                <span class="text-sm font-bold text-slate-600">{{ $course->instructor->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-sm font-bold text-slate-400">{{ number_format($course->student_count) }} students</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State Illustrations --}}
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center">
                    <div class="w-48 h-48 mb-6 text-slate-200">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="11" y1="8" x2="11" y2="14"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">No courses found</h3>
                    <p class="text-slate-500 font-medium max-w-md mx-auto">We couldn't find anything matching your criteria. Try adjusting your filters or search term.</p>
                    <a href="{{ route('courses.index') }}" class="mt-8 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition">Clear Filters</a>
                </div>
            @endforelse
            
        </div>

        <div class="mt-12" x-show="!isLoading" style="display: none;">
            {{ $courses->links() }}
        </div>
    </div>

    <style>
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</x-app-layout>
