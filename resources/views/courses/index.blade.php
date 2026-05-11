<x-app-layout>
    <div x-data="catalogData()" class="min-h-screen bg-[#F8FAFC]">
        <!-- PAGE HEADER -->
        <div class="catalog-header max-w-none">
            <div class="w-full max-w-7xl mx-auto flex items-center justify-between gap-12 px-4 sm:px-8">
                <!-- Left: Title & Subtitle -->
                <div class="flex-shrink-0">
                    <h1 class="catalog-title">CATALOG</h1>
                    <p class="catalog-subtitle">BROWSE_ALL_COURSES</p>
                </div>

                <!-- Right: Search Bar -->
                <form action="{{ route('courses.index') }}" method="GET" class="flex-1 max-w-[480px]">
                    <div class="relative flex items-center">
                        <svg class="absolute left-3 w-5 h-5 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search courses..." 
                            class="catalog-search pl-9 pr-4">
                        @if(request('search'))
                            <button type="button" @click="document.querySelector('input[name=search]').value=''; $el.parentElement.parentElement.submit();" 
                                class="absolute right-3 text-slate-500 hover:text-[#0F172A] transition-colors">
                                ✕
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Border divider under header -->
        <div class="w-full h-px bg-gray-200"></div>

        <!-- MAIN CONTENT AREA -->
        <div class="flex relative max-w-7xl mx-auto w-full px-4 sm:px-8 py-12 gap-8">
            <!-- FILTER SIDEBAR (Desktop) -->
            <aside class="filter-sidebar">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-[10px] font-sans text-slate-500 uppercase tracking-widest">FILTERS</h2>
                    @if(request('category') || request('search') || request('sort'))
                        <a href="{{ route('courses.index') }}" class="text-[10px] font-sans text-[#2255FF] cursor-pointer hover:text-[#0F172A] transition-colors">
                            CLEAR ALL
                        </a>
                    @endif
                </div>

                <!-- Category Filter -->
                <div class="filter-group">
                    <div class="filter-group-title">
                        <span>CATEGORIES</span>
                    </div>
                    <div class="filter-group-divider"></div>
                    <div class="space-y-1">
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>All Categories</span>
                        </label>
                        @foreach($categories as $category)
                            <label class="filter-option">
                                <a href="{{ route('courses.index', array_merge(request()->except('page'), ['category' => $category->id])) }}" class="inline-block w-full">
                                    <input type="checkbox" class="filter-checkbox"
                                        {{ request('category') == $category->id ? 'checked' : '' }}
                                        data-category="{{ $category->id }}" disabled>
                                    <span>{{ $category->name }}</span>
                                </a>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Price Filter -->
                <div class="filter-group">
                    <div class="filter-group-title">
                        <span>PRICE</span>
                    </div>
                    <div class="filter-group-divider"></div>
                    <div class="space-y-1">
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>Free</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>Under $50</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>$50 - $100</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>Over $100</span>
                        </label>
                    </div>
                </div>

                <!-- Level Filter -->
                <div class="filter-group">
                    <div class="filter-group-title">
                        <span>LEVEL</span>
                    </div>
                    <div class="filter-group-divider"></div>
                    <div class="space-y-1">
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>Beginner</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>Intermediate</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" class="filter-checkbox">
                            <span>Advanced</span>
                        </label>
                    </div>
                </div>
            </aside>

            <!-- MAIN GRID AREA -->
            <main class="flex-1 w-full">
                <!-- ACTIVE FILTER CHIPS -->
                @if(request('category') || request('search'))
                    <div class="flex flex-wrap gap-2 mb-8">
                        @if(request('search'))
                            <form action="{{ route('courses.index') }}" method="GET" class="inline">
                                <button class="filter-chip">
                                    <span>Search: {{ request('search') }}</span>
                                    <span class="filter-chip-remove">✕</span>
                                </button>
                            </form>
                        @endif
                        @if(request('category'))
                            @php
                                $selectedCategory = $categories->where('id', request('category'))->first();
                            @endphp
                            <form action="{{ route('courses.index') }}" method="GET" class="inline">
                                <button class="filter-chip">
                                    <span>{{ $selectedCategory?->name }}</span>
                                    <span class="filter-chip-remove">✕</span>
                                </button>
                            </form>
                        @endif
                        <a href="{{ route('courses.index') }}" class="filter-chip opacity-50 hover:opacity-100 transition-opacity">
                            <span>Clear all</span>
                            <span>→</span>
                        </a>
                    </div>
                @endif

                <!-- SORT DROPDOWN & CONTROLS -->
                <div class="flex items-center justify-between mb-8 gap-4">
                    <div class="text-[11px] font-sans text-slate-500 uppercase tracking-widest">
                        {{ $courses->total() }} RESULTS
                    </div>
                    <div class="ml-auto relative" x-data="{ sortOpen: false }">
                        <button @click="sortOpen = !sortOpen" class="sort-dropdown flex items-center gap-2">
                            <span>SORT BY: 
                                @switch(request('sort', 'popular'))
                                    @case('newest') NEWEST @break
                                    @case('rating') HIGHEST RATED @break
                                    @default MOST POPULAR
                                @endswitch
                            </span>
                            <span class="text-[9px]">↓</span>
                        </button>
                        
                        <div x-show="sortOpen" @click.away="sortOpen = false" 
                            class="sort-dropdown-menu absolute top-full right-0 mt-1 min-w-max">
                            <form action="{{ route('courses.index') }}" method="GET" class="contents">
                                @if(request('search')) <input type="hidden" name="search" value="{{ request('search') }}"> @endif
                                @if(request('category')) <input type="hidden" name="category" value="{{ request('category') }}"> @endif
                                
                                <button type="submit" name="sort" value="popular" 
                                    class="sort-option {{ request('sort', 'popular') === 'popular' ? 'active' : '' }}">
                                    Most Popular
                                </button>
                                <button type="submit" name="sort" value="newest" 
                                    class="sort-option {{ request('sort') === 'newest' ? 'active' : '' }}">
                                    Newest Releases
                                </button>
                                <button type="submit" name="sort" value="rating" 
                                    class="sort-option {{ request('sort') === 'rating' ? 'active' : '' }}">
                                    Highest Rated
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- COURSES GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                    @forelse($courses as $course)
                        <div class="course-card group relative">
                            <!-- Category Badge (Top-Left) -->
                            <div class="absolute top-3 left-3 z-10">
                                <div class="course-card-category">
                                    {{ $course->category->name }}
                                </div>
                            </div>

                            <!-- Wishlist Heart (Top-Right) -->
                            <button type="button" class="course-card-wishlist"
                                @click="toggleWishlist($event, {{ $course->id }})"
                                :class="{ 'text-[#FF3B30]': wishlist.includes({{ $course->id }}) }">
                                <svg class="w-6 h-6 fill-none stroke-current" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 11.25 9 11.25s9-4.03 9-11.25z" />
                                </svg>
                            </button>

                            <!-- Thumbnail -->
                            <div class="course-card-thumbnail">
                                <img src="{{ $course->thumbnail_src ?? 'https://via.placeholder.com/600x400?text='.urlencode($course->title) }}" 
                                    alt="{{ $course->title }}" class="group">
                            </div>

                            <!-- Card Body -->
                            <div class="course-card-body">
                                <!-- Title -->
                                <h3 class="course-card-title">
                                    <a href="{{ route('courses.show', $course) }}" class="hover:text-[#2255FF] transition-colors">
                                        {{ $course->title }}
                                    </a>
                                </h3>

                                <!-- Instructor -->
                                <div class="course-card-instructor">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($course->instructor->name) }}&background=2255FF&color=fff" 
                                        alt="{{ $course->instructor->name }}" class="w-4 h-4 rounded-full">
                                    <span>{{ $course->instructor->name }}</span>
                                </div>

                                <!-- Rating -->
                                <div class="course-card-rating mb-3">
                                    @php
                                        $courseRating = $course->rating ?? 0;
                                        $ratingCount = $course->reviews_count ?? 0;
                                        $ratingStars = max(0, min(5, round($courseRating)));
                                    @endphp
                                    @for($i = 0; $i < 5; $i++)
                                        <svg class="w-3 h-3 {{ $i < $ratingStars ? 'fill-[#2255FF]' : 'fill-gray-300' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                    @if($courseRating > 0 && $ratingCount > 0)
                                        <span class="ml-1">{{ number_format($courseRating, 1) }} ({{ number_format($ratingCount) }} reviews)</span>
                                    @else
                                        <span class="ml-1 text-gray-500">No ratings yet</span>
                                    @endif
                                </div>

                                <!-- Meta Row -->
                                <div class="course-card-meta">
                                    {{ $course->duration ?? '12H 30M' }} · {{ $course->lessons_count ?? rand(10, 30) }} LESSONS
                                </div>

                                <!-- Price Row -->
                                <div class="flex items-center gap-2 mt-auto pt-3 border-t border-slate-200">
                                    @if($course->price == 0)
                                        <span class="course-card-free">FREE</span>
                                    @else
                                        <span class="course-card-price-original">${{ number_format($course->price, 0) }}</span>
                                        <span class="course-card-price">${{ number_format($course->price * 0.7, 0) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <!-- EMPTY STATE -->
                        <div class="col-span-full py-24 text-center">
                            <div class="empty-state-icon mb-6">∅</div>
                            <h2 class="empty-state-title mb-4">NO COURSES FOUND</h2>
                            <p class="empty-state-subtitle mb-8">Try adjusting your filters</p>
                            <a href="{{ route('courses.index') }}" class="text-[#2255FF] text-[11px] font-sans uppercase tracking-widest hover:text-[#0F172A] transition-colors">
                                CLEAR FILTERS →
                            </a>
                        </div>
                    @endforelse
                </div>

                <!-- PAGINATION -->
                @if($courses->count())
                    <div class="pagination-container">
                        <!-- Previous Button -->
                        @if($courses->onFirstPage())
                            <span class="pagination-prev-next opacity-50">← PREV</span>
                        @else
                            <a href="{{ $courses->previousPageUrl() }}" class="pagination-prev-next">← PREV</a>
                        @endif

                        <!-- Page Numbers -->
                        @php
                            $start = max(1, $courses->currentPage() - 2);
                            $end = min($courses->lastPage(), $courses->currentPage() + 2);
                        @endphp
                        @for($page = $start; $page <= $end; $page++)
                            @if($page == $courses->currentPage())
                                <span class="pagination-button active">{{ $page }}</span>
                            @else
                                <a href="{{ $courses->url($page) }}" class="pagination-button">{{ $page }}</a>
                            @endif
                        @endfor

                        <!-- Next Button -->
                        @if($courses->hasMorePages())
                            <a href="{{ $courses->nextPageUrl() }}" class="pagination-prev-next">NEXT →</a>
                        @else
                            <span class="pagination-prev-next opacity-50">NEXT →</span>
                        @endif
                    </div>
                @endif
            </main>
        </div>

        <!-- MOBILE FILTERS DRAWER -->
        <div x-show="filterDrawerOpen" class="filter-drawer" x-transition>
            <div @click="filterDrawerOpen = false" class="filter-drawer-overlay"></div>
            <div class="filter-drawer-content">
                <button @click="filterDrawerOpen = false" class="filter-drawer-close">✕</button>
                <div class="p-6">
                    <h2 class="text-[10px] font-sans text-[#0F172A] uppercase tracking-widest mb-6">FILTERS</h2>
                    
                    <!-- Category Filter Mobile -->
                    <div class="filter-group">
                        <div class="filter-group-title">CATEGORIES</div>
                        <div class="filter-group-divider"></div>
                        <div class="space-y-2">
                            @foreach($categories as $category)
                                <a href="{{ route('courses.index', ['category' => $category->id]) }}" class="filter-option block">
                                    <input type="checkbox" class="filter-checkbox" {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <span>{{ $category->name }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alpine.js Catalog Data -->
    <script>
        function catalogData() {
            return {
                filterDrawerOpen: false,
                wishlist: {{ json_encode(auth()?->user()?->wishlist_ids ?? []) }},
                
                toggleWishlist(e, courseId) {
                    e.preventDefault();
                    const index = this.wishlist.indexOf(courseId);
                    if (index > -1) {
                        this.wishlist.splice(index, 1);
                    } else {
                        this.wishlist.push(courseId);
                    }
                    // TODO: Send to server with fetch
                }
            };
        }
    </script>
</x-app-layout>
