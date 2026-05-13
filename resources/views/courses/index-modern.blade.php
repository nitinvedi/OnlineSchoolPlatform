<x-app-layout>
    <div x-data="catalogData()" class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100">
        
        <!-- HERO HEADER -->
        <div class="bg-gradient-to-br from-brand-600 via-brand-500 to-brand-700 text-white relative overflow-hidden">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-brand-400/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-brand-300/10 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2"></div>
            
            <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-8 py-16 sm:py-20">
                <div class="mb-8">
                    <div class="inline-flex items-center gap-2 mb-4 px-3 py-1.5 bg-white/20 backdrop-blur rounded-full text-xs font-semibold text-white/80 border border-white/20">
                        <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2a10 10 0 1120 0 10 10 0 01-20 0z"/></svg>
                        <span>Explore Learning</span>
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-display font-black tracking-tight leading-tight">
                        Discover Courses
                    </h1>
                    <p class="text-xl text-white/90 mt-4 max-w-2xl">
                        Learn from expert instructors and master new skills at your own pace.
                    </p>
                </div>

                <!-- SEARCH BAR -->
                <form action="{{ route('courses.index') }}" method="GET" class="max-w-2xl">
                    <div class="relative flex items-center bg-white rounded-xl overflow-hidden shadow-lg shadow-brand-900/20 group">
                        <svg class="absolute left-4 w-5 h-5 text-slate-400 group-focus-within:text-brand-600 transition-colors" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search by course name, topic, instructor..." 
                            class="w-full pl-12 pr-16 py-4 text-slate-900 placeholder-slate-500 outline-none text-base">
                        @if(request('search'))
                            <button type="button" @click="document.querySelector('input[name=search]').value=''; $el.closest('form').submit();" 
                                class="absolute right-4 text-slate-400 hover:text-slate-600 transition-colors">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- MAIN CONTENT AREA -->
        <div class="max-w-7xl mx-auto px-4 sm:px-8 py-12">
            
            <!-- FILTER SIDEBAR + GRID LAYOUT -->
            <div class="flex flex-col lg:flex-row gap-8">
                
                <!-- FILTER SIDEBAR -->
                <aside class="lg:w-64 flex-shrink-0">
                    <div class="sticky top-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-black text-slate-900">🔍 Filters</h2>
                            @if(request('category') || request('search') || request('sort'))
                                <a href="{{ route('courses.index') }}" class="text-xs font-bold text-brand-600 hover:text-brand-700">Clear</a>
                            @endif
                        </div>

                        <!-- Category Filter -->
                        <div class="rounded-2xl bg-white border border-slate-200 p-6 hover:shadow-md transition-shadow">
                            <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M3 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zm10-10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1V4zm0 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1h-6a1 1 0 01-1-1v-6z"/></svg>
                                Categories
                            </h3>
                            <div class="space-y-2">
                                @foreach($categories as $category)
                                    <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer group">
                                        <input type="checkbox" {{ request('category') == $category->id ? 'checked' : '' }} 
                                            onchange="location.href = '{{ route('courses.index') }}?category={{ $category->id }}'"
                                            class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-600">
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">{{ $category->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Level Filter -->
                        <div class="rounded-2xl bg-white border border-slate-200 p-6 hover:shadow-md transition-shadow">
                            <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                Level
                            </h3>
                            <div class="space-y-2">
                                @foreach(['Beginner' => 'beginner', 'Intermediate' => 'intermediate', 'Advanced' => 'advanced'] as $label => $value)
                                    <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer group">
                                        <input type="checkbox" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-600">
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Price Filter -->
                        <div class="rounded-2xl bg-white border border-slate-200 p-6 hover:shadow-md transition-shadow">
                            <h3 class="text-sm font-black text-slate-900 mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm0-14c-3.31 0-6 2.69-6 6s2.69 6 6 6 6-2.69 6-6-2.69-6-6-6z"/></svg>
                                Price
                            </h3>
                            <div class="space-y-2">
                                @foreach(['Free' => 'free', 'Under $50' => 'under50', '$50-$100' => '50to100', 'Over $100' => 'over100'] as $label => $value)
                                    <label class="flex items-center gap-3 p-2.5 rounded-lg hover:bg-slate-50 transition-colors cursor-pointer group">
                                        <input type="checkbox" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-600">
                                        <span class="text-sm font-medium text-slate-700 group-hover:text-slate-900">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </aside>

                <!-- MAIN GRID -->
                <main class="flex-1">
                    
                    <!-- SORT & RESULTS -->
                    <div class="flex items-center justify-between mb-8">
                        <div class="text-sm font-bold text-slate-600">
                            {{ $courses->total() }} <span class="text-slate-500">Results</span>
                        </div>
                        <div class="relative" x-data="{ sortOpen: false }">
                            <button @click="sortOpen = !sortOpen" 
                                    class="flex items-center gap-2 px-4 py-2 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 text-sm font-bold text-slate-700 transition-colors">
                                Sort: 
                                @switch(request('sort', 'popular'))
                                    @case('newest') Newest @break
                                    @case('rating') Highest Rated @break
                                    @default Most Popular
                                @endswitch
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7 10l5 5 5-5z"/></svg>
                            </button>
                            
                            <div x-show="sortOpen" @click.away="sortOpen = false" 
                                class="absolute top-full right-0 mt-2 w-56 bg-white rounded-xl border border-slate-200 shadow-lg z-10">
                                <form action="{{ route('courses.index') }}" method="GET" class="contents">
                                    @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                                    @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                    
                                    <button type="submit" name="sort" value="popular" 
                                        class="block w-full text-left px-6 py-3 hover:bg-slate-50 text-sm font-bold {{ request('sort', 'popular') === 'popular' ? 'text-brand-600' : 'text-slate-700' }} first:rounded-t-xl">
                                        Most Popular
                                    </button>
                                    <button type="submit" name="sort" value="newest" 
                                        class="block w-full text-left px-6 py-3 hover:bg-slate-50 text-sm font-bold {{ request('sort') === 'newest' ? 'text-brand-600' : 'text-slate-700' }} border-t border-slate-100">
                                        Newest Releases
                                    </button>
                                    <button type="submit" name="sort" value="rating" 
                                        class="block w-full text-left px-6 py-3 hover:bg-slate-50 text-sm font-bold {{ request('sort') === 'rating' ? 'text-brand-600' : 'text-slate-700' }} border-t border-slate-100 last:rounded-b-xl">
                                        Highest Rated
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- COURSES GRID -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($courses as $course)
                            <div class="group bg-white rounded-2xl border border-slate-200 overflow-hidden hover:border-brand-300 hover:shadow-lg transition-all duration-300">
                                
                                <!-- Image Container -->
                                <div class="relative h-48 overflow-hidden bg-gradient-to-br from-slate-200 to-slate-300">
                                    <img src="{{ $course->thumbnail_src ?? 'https://via.placeholder.com/600x400?text='.urlencode($course->title) }}" 
                                        alt="{{ $course->title }}" 
                                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    
                                    <!-- Category Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-brand-500 text-white text-xs font-bold">
                                            {{ $course->category->name }}
                                        </span>
                                    </div>

                                    <!-- Wishlist Button -->
                                    <button type="button" 
                                            @click="toggleWishlist($event, {{ $course->id }})"
                                            :class="{ 'text-red-500 bg-white/80': wishlist.includes({{ $course->id }}), 'text-white/70 hover:text-white': !wishlist.includes({{ $course->id }}) }"
                                            class="absolute top-3 right-3 w-10 h-10 rounded-full bg-black/20 backdrop-blur hover:bg-black/30 transition-all flex items-center justify-center">
                                        <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24">
                                            <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 11.25 9 11.25s9-4.03 9-11.25z" />
                                        </svg>
                                    </button>

                                    <!-- Play Overlay -->
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/0 group-hover:bg-black/20 transition-colors">
                                        <div class="w-12 h-12 rounded-full bg-white/0 group-hover:bg-white group-hover:shadow-lg transition-all flex items-center justify-center">
                                            <svg class="w-6 h-6 text-brand-600 ml-0.5" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-6 flex flex-col h-full">
                                    
                                    <!-- Title -->
                                    <a href="{{ route('courses.show', $course) }}" class="group/title">
                                        <h3 class="text-lg font-black text-slate-900 mb-2 line-clamp-2 group-hover/title:text-brand-600 transition-colors">
                                            {{ $course->title }}
                                        </h3>
                                    </a>

                                    <!-- Instructor -->
                                    <div class="flex items-center gap-3 mb-4">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name) }}&background=brand&color=fff&size=32" 
                                            alt="{{ $course->instructor->name }}" class="w-6 h-6 rounded-full">
                                        <span class="text-xs font-semibold text-slate-600">{{ $course->instructor->name }}</span>
                                    </div>

                                    <!-- Rating -->
                                    <div class="flex items-center gap-2 mb-4">
                                        <div class="flex items-center gap-1">
                                            @php
                                                $courseRating = $course->rating ?? 0;
                                                $ratingCount = $course->reviews_count ?? 0;
                                                $ratingStars = max(0, min(5, round($courseRating)));
                                            @endphp
                                            @for($i = 0; $i < 5; $i++)
                                                <svg class="w-4 h-4 {{ $i < $ratingStars ? 'fill-yellow-400' : 'fill-slate-300' }}" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                </svg>
                                            @endfor
                                        </div>
                                        @if($courseRating > 0 && $ratingCount > 0)
                                            <span class="text-xs font-bold text-slate-700">{{ number_format($courseRating, 1) }} ({{ $ratingCount }})</span>
                                        @else
                                            <span class="text-xs text-slate-500">No ratings</span>
                                        @endif
                                    </div>

                                    <!-- Meta Info -->
                                    <div class="text-xs text-slate-600 font-medium mb-4 pb-4 border-b border-slate-200">
                                        {{ $course->duration ?? '12H 30M' }} · {{ $course->lessons_count ?? rand(10, 30) }} lessons
                                    </div>

                                    <!-- Price & CTA -->
                                    <div class="mt-auto">
                                        <div class="flex items-center justify-between gap-3 mb-3">
                                            @if($course->price == 0)
                                                <span class="text-2xl font-black text-slate-900">Free</span>
                                            @else
                                                <div>
                                                    <span class="text-2xl font-black text-slate-900">${{ number_format($course->price, 0) }}</span>
                                                    @if($course->original_price)
                                                        <span class="text-xs text-slate-400 line-through">${{ number_format($course->original_price) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-1">
                                                <svg class="w-4 h-4 text-slate-400" viewBox="0 0 24 24" fill="currentColor"><path d="M12 12a5 5 0 100-10 5 5 0 000 10zm-7 9a7 7 0 0114 0H5z"/></svg>
                                                <span class="text-xs font-semibold text-slate-600">{{ number_format($course->student_count ?? 0) }}</span>
                                            </div>
                                        </div>

                                        <a href="{{ route('courses.show', $course) }}" 
                                           class="block w-full text-center py-3 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-bold text-sm transition-colors">
                                            View Course
                                        </a>
                                    </div>

                                </div>

                            </div>
                        @empty
                            <div class="col-span-full">
                                <div class="text-center py-20">
                                    <svg class="w-20 h-20 text-slate-300 mx-auto mb-6" viewBox="0 0 24 24" fill="currentColor">
                                        <path d="M19 2H9a2 2 0 00-2 2v14a2 2 0 002 2h10V2zM7 6H5a2 2 0 00-2 2v12a2 2 0 002 2h2V6z"/>
                                    </svg>
                                    <h3 class="text-2xl font-black text-slate-900 mb-2">No Courses Found</h3>
                                    <p class="text-slate-600 mb-6">Try adjusting your filters or search terms</p>
                                    <a href="{{ route('courses.index') }}" class="inline-flex items-center h-11 px-6 rounded-lg bg-brand-600 hover:bg-brand-700 text-white font-bold transition-colors">
                                        View All Courses
                                    </a>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- PAGINATION -->
                    @if($courses->hasPages())
                        <div class="mt-12 flex items-center justify-center">
                            {{ $courses->links() }}
                        </div>
                    @endif

                </main>

            </div>

        </div>

    </div>

    <script>
        function catalogData() {
            return {
                wishlist: @json(auth()->check() ? auth()->user()->wishedCourses->pluck('id')->toArray() : []),
                toggleWishlist(e, courseId) {
                    e.preventDefault();
                    if (this.wishlist.includes(courseId)) {
                        this.wishlist = this.wishlist.filter(id => id !== courseId);
                    } else {
                        this.wishlist.push(courseId);
                    }
                }
            }
        }
    </script>

</x-app-layout>
