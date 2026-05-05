<x-app-layout>
    {{-- Immersive Search Header --}}
    <div class="relative bg-dark-bg py-24 overflow-hidden border-b border-white/5">
        {{-- Subtle Grid Background --}}
        <div class="absolute inset-0 z-0 opacity-10 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:40px_40px]"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <h1 class="text-4xl md:text-7xl font-display font-black text-white mb-10 tracking-tighter">Expand Your <span class="text-brand-500">Knowledge</span></h1>
            
            <form action="{{ route('courses.index') }}" method="GET" class="max-w-3xl mx-auto relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-slate-500 group-focus-within:text-brand-500 transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search for courses, skills, or instructors..." 
                    class="block w-full pl-16 pr-32 py-6 border border-white/10 rounded-3xl bg-dark-card/50 backdrop-blur-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-brand-500/20 focus:border-brand-500 text-lg transition-all duration-300">
                <div class="absolute inset-y-2 right-2 flex items-center">
                    <button type="submit" class="px-8 py-4 bg-brand-500 text-white font-black rounded-2xl hover:bg-brand-600 transition-all duration-300 shadow-xl shadow-brand-500/20 active:scale-95">
                        Search
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Main Catalog Area --}}
    <div x-data="{ viewMode: 'grid' }" class="py-16">
        
        {{-- Filters & Controls Row --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8 mb-16">
            
            {{-- Pill-Based Category Filters --}}
            <div class="flex-1 overflow-x-auto hide-scrollbar">
                <div class="flex gap-4 p-1">
                    <a href="{{ route('courses.index') }}" class="whitespace-nowrap px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all {{ !request('category') ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/20' : 'bg-dark-card text-slate-400 border border-white/5 hover:border-white/10 hover:text-white' }}">
                        All Courses
                    </a>
                    @foreach($categories as $category)
                        <a href="{{ route('courses.index', ['category' => $category->id]) }}" class="whitespace-nowrap px-6 py-3 rounded-2xl font-black text-xs uppercase tracking-widest transition-all {{ request('category') == $category->id ? 'bg-brand-500 text-white shadow-lg shadow-brand-500/20' : 'bg-dark-card text-slate-400 border border-white/5 hover:border-white/10 hover:text-white' }}">
                            {{ $category->name }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Controls --}}
            <div class="flex items-center gap-4 flex-shrink-0">
                <form action="{{ route('courses.index') }}" method="GET" x-ref="sortForm">
                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                    <select name="sort" onchange="$refs.sortForm.submit()" class="bg-dark-card border border-white/5 text-slate-400 font-bold text-xs uppercase tracking-widest rounded-2xl py-3 pl-4 pr-10 focus:ring-brand-500 focus:border-brand-500 cursor-pointer hover:bg-dark-surface transition-colors">
                        <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>Newest Releases</option>
                        <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                    </select>
                </form>

                <div class="hidden sm:flex items-center bg-dark-card border border-white/5 rounded-2xl p-1">
                    <button @click="viewMode = 'grid'" :class="{'bg-dark-surface text-brand-500': viewMode === 'grid', 'text-slate-500 hover:text-slate-300': viewMode !== 'grid'}" class="p-2 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    </button>
                    <button @click="viewMode = 'list'" :class="{'bg-dark-surface text-brand-500': viewMode === 'list', 'text-slate-500 hover:text-slate-300': viewMode !== 'list'}" class="p-2 rounded-xl transition-all">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        {{-- Catalog Grid/List --}}
        <div :class="{'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8': viewMode === 'grid', 'flex flex-col gap-6': viewMode === 'list'}">
            
            @forelse($courses as $course)
                <div :class="{'flex-col': viewMode === 'grid', 'flex-col sm:flex-row': viewMode === 'list'}" 
                     class="flex bg-dark-card border border-white/5 rounded-[2.5rem] group overflow-hidden hover:border-brand-500/50 transition-all duration-500 relative">
                    
                    {{-- Badge --}}
                    <div class="absolute top-6 left-6 z-20">
                        <span class="px-3 py-1 bg-brand-500 text-[10px] font-black text-white rounded-lg uppercase tracking-widest shadow-lg">Featured</span>
                    </div>

                    {{-- Thumbnail --}}
                    <div :class="{'w-full': viewMode === 'grid', 'w-full sm:w-80': viewMode === 'list'}" class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                        <img src="{{ $course->thumbnail_src ?? 'https://ui-avatars.com/api/?name='.urlencode($course->title).'&background=121217&color=fff&size=600' }}" 
                             class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700 opacity-80 group-hover:opacity-100">
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-bg/90 to-transparent"></div>
                    </div>

                    {{-- Content --}}
                    <div class="p-8 flex-1 flex flex-col">
                        <div class="flex items-center justify-between mb-6">
                            <span class="text-[10px] font-black text-brand-500 uppercase tracking-[0.2em]">{{ $course->category->name }}</span>
                            <div class="flex items-center gap-1.5 px-2.5 py-1 bg-white/5 rounded-lg border border-white/5">
                                <svg class="w-3.5 h-3.5 text-amber-400 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                <span class="text-xs font-black text-slate-300">{{ number_format($course->rating, 1) }}</span>
                            </div>
                        </div>

                        <h2 class="text-xl font-bold text-white mb-4 leading-tight group-hover:text-brand-500 transition-colors line-clamp-2">
                            <a href="{{ route('courses.show', $course) }}" class="focus:outline-none">
                                {{ $course->title }}
                            </a>
                        </h2>

                        <p class="text-slate-500 text-sm font-medium mb-8 line-clamp-2" x-show="viewMode === 'list'">
                            {{ Str::limit(strip_tags($course->overview), 150) }}
                        </p>
                        
                        <div class="flex items-center justify-between mt-auto pt-6 border-t border-white/5">
                            <div class="flex items-center gap-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name) }}&background=3b67f5&color=fff&size=40" class="w-8 h-8 rounded-full border border-white/10">
                                <span class="text-xs font-bold text-slate-400 group-hover:text-slate-200 transition-colors">{{ $course->instructor->name }}</span>
                            </div>
                            <div class="text-right">
                                <span class="block text-lg font-black text-white">${{ number_format($course->price, 0) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-32 text-center">
                    <div class="w-24 h-24 mx-auto mb-8 text-slate-800">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <h3 class="text-3xl font-display font-black text-white mb-4">No results found</h3>
                    <p class="text-slate-500 font-medium max-w-sm mx-auto mb-10">We couldn't find any courses matching your search or filters.</p>
                    <a href="{{ route('courses.index') }}" class="btn-primary inline-block">Clear All Filters</a>
                </div>
            @endforelse
            
        </div>

        <div class="mt-20">
            {{ $courses->links() }}
        </div>
    </div>
</x-app-layout>
