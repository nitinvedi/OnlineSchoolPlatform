<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LiveSchool') }} — Premium learning for builders</title>
    <meta name="description" content="Premium curriculum designed for ambitious learners who want skills, credibility, and real work outcomes.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500;600;700&family=Neue+Haas+Grotesk+Display+Pro:wght@400;500;600&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Fonts fallback ────────────────────────────── */
        .font-display { font-family: 'Bebas Neue', 'Impact', sans-serif; letter-spacing: -0.03em; line-height: 0.9; }
        .font-mono    { font-family: 'JetBrains Mono', monospace; font-variant-numeric: tabular-nums; }

        /* ── Noise grain overlay ───────────────────────── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            opacity: 0.015;
            pointer-events: none;
        }

        /* ── Marquee ───────────────────────────────────── */
        .marquee-wrapper { overflow: hidden; }
        .marquee-track {
            display: flex; white-space: nowrap;
            animation: marquee 22s linear infinite;
        }
        .marquee-track.paused { animation-play-state: paused; }
        @keyframes marquee { from { transform: translateX(0); } to { transform: translateX(-50%); } }

        /* ── Scroll reveal ─────────────────────────────── */
        .reveal-hidden { opacity: 0; transform: translateY(40px); }
        .reveal { opacity: 1; transform: translateY(0); transition: opacity 0.7s cubic-bezier(0.16,1,0.3,1), transform 0.7s cubic-bezier(0.16,1,0.3,1); }

        /* ── Hero bounce arrow ─────────────────────────── */
        @keyframes bounce-down { 0%,100% { transform: translateY(0); } 50% { transform: translateY(8px); } }
        .hero-arrow { display: inline-block; animation: bounce-down 1.8s ease-in-out infinite; }

        /* ── Stat bar ──────────────────────────────────── */
        .stat-bar-fill { transition: width 1.2s cubic-bezier(0.16,1,0.3,1); }

        /* ── Scrollbar hide ────────────────────────────── */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* ── Card snap ─────────────────────────────────── */
        .snap-x { scroll-snap-type: x mandatory; }
        .snap-start { scroll-snap-align: start; }

        /* ── Pulse dot ─────────────────────────────────── */
        @keyframes pulse-dot { 0%,100%{ opacity:1; transform:scale(1); } 50%{ opacity:0.5; transform:scale(1.5); } }
        .pulse-dot { animation: pulse-dot 2s ease-in-out infinite; }

        /* ── Testimonial transition ─────────────────────── */
        .testimonial-card { transition: opacity 0.35s ease, transform 0.35s cubic-bezier(0.16,1,0.3,1); }
        .testimonial-card.hidden { display: none; }

        /* ── Progress bar on card ──────────────────────── */
        .course-progress { height: 2px; background: #E5E7EB; position: relative; overflow: hidden; }
        .course-progress-fill { height: 100%; background: #2255FF; }

        /* ── Mobile touch targets ──────────────────────── */
        @media (max-width: 768px) {
        }

        /* ── Skeleton shimmer (for future use) ─────────── */
        @keyframes shimmer { from { background-position: -200% 0; } to { background-position: 200% 0; } }
        .shimmer {
            background: linear-gradient(90deg, #FFFFFF 25%, #1a1a1a 50%, #FFFFFF 75%);
            background-size: 200% 100%;
            animation: shimmer 1.6s infinite;
        }

        /* ── Hover underline draw-in ─────────────────────── */
        .underline-draw { position: relative; text-decoration: none; }
        .underline-draw::after {
            content: ''; position: absolute; bottom: -2px; left: 0;
            width: 0; height: 1px; background: #2255FF;
            transition: width 0.25s cubic-bezier(0.16,1,0.3,1);
        }
        .underline-draw:hover::after { width: 100%; }

        /* ── Nav link strikethrough on hover ────────────── */
        .nav-strike { position: relative; }
        .nav-strike::after {
            content: ''; position: absolute; top: 50%; left: 0;
            width: 0; height: 1px; background: currentColor;
            transition: width 0.2s ease;
        }
        .nav-strike:hover::after { width: 100%; }

        /* ── Bento grid responsive ───────────────────────── */
        @media (max-width: 1024px) {
            .bento-grid { grid-template-columns: 1fr !important; grid-template-rows: auto !important; }
        }
    </style>
</head>
<body class="bg-[#FFFFFF] text-[#111827] antialiased selection:bg-[#2255FF]/20 selection:text-[#111827] overflow-x-hidden">

    @php
        $hour = date('H');
        $greeting = 'Good morning';
        if ($hour >= 12 && $hour < 17) {
            $greeting = 'Good afternoon';
        } elseif ($hour >= 17) {
            $greeting = 'Good evening';
        }
    @endphp

    {{-- ─────────────────────────────────────────────────────────────
         NAVBAR
    ───────────────────────────────────────────────────────────────── --}}
    <nav x-data="{ scrolled: false, open: false, active: 'catalog' }"
         @scroll.window="scrolled = window.pageYOffset > 40"
         x-on:section-change.window="active = $event.detail"
         :class="scrolled ? 'bg-[#FFFFFF] border-b border-[#E5E7EB]' : 'bg-transparent'"
         class="fixed inset-x-0 top-0 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">

                {{-- Logo --}}
                <a href="#hero" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 bg-[#2255FF] flex items-center justify-center text-white font-display text-lg font-black transition-transform duration-200 group-hover:scale-105">L</div>
                    <span class="font-mono text-xs uppercase tracking-[0.35em] text-[#111827]">LiveSchool</span>
                </a>

                {{-- Desktop nav --}}
                <div class="hidden lg:flex items-center gap-10">
                    @foreach([['href'=>'#programs','key'=>'catalog','label'=>'Catalog'],['href'=>'#features','key'=>'features','label'=>'Features'],['href'=>'#enterprise','key'=>'enterprise','label'=>'Enterprise']] as $link)
                    <a href="{{ $link['href'] }}"
                       @click="active='{{ $link['key'] }}'"
                       :class="active === '{{ $link['key'] }}' ? 'text-[#111827]' : 'text-[#4B5563] hover:text-[#111827]'"
                       class="relative nav-strike font-mono text-[11px] uppercase tracking-[0.2em] transition-colors duration-150">
                        {{ $link['label'] }}
                        <span class="absolute inset-x-0 -bottom-1.5 h-px bg-[#2255FF] transition-opacity duration-150"
                              :class="active === '{{ $link['key'] }}' ? 'opacity-100' : 'opacity-0'"></span>
                    </a>
                    @endforeach
                </div>

                {{-- Desktop CTA --}}
                <div class="hidden lg:flex items-center gap-5">
                    @auth
                        <a href="{{ route('dashboard') }}" class="font-mono text-[11px] uppercase tracking-[0.25em] text-[#4B5563] hover:text-[#111827] transition-colors duration-150">Dashboard</a>
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center border border-[#2255FF] bg-[#2255FF] px-5 py-2.5 font-mono text-[11px] uppercase tracking-[0.25em] text-white hover:bg-[#111827] hover:text-[#FAFAFA] hover:border-[#111827] transition-none">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="font-mono text-[11px] uppercase tracking-[0.25em] text-[#4B5563] hover:text-[#111827] transition-colors duration-150">Login</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center border border-[#2255FF] bg-[#2255FF] px-5 py-2.5 font-mono text-[11px] uppercase tracking-[0.25em] text-white hover:bg-[#111827] hover:text-[#FAFAFA] hover:border-[#111827] transition-none">Join Free</a>
                    @endauth
                </div>

                {{-- Mobile hamburger --}}
                <button @click="open = !open" class="lg:hidden inline-flex items-center justify-center w-10 h-10 border border-[#E5E7EB] text-[#111827] hover:border-[#2255FF] transition-colors duration-200">
                    <svg x-show="!open" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open"  class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Mobile drawer --}}
        <div x-show="open"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-180"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 -translate-y-2"
             @click.outside="open = false"
             class="lg:hidden border-t border-[#E5E7EB] bg-[#FFFFFF]">
                <div class="px-6 pb-8 pt-5 space-y-5 font-mono text-[11px] uppercase tracking-[0.2em]">
                <a href="#programs"   @click="open=false;active='catalog'"    class="block text-[#4B5563] hover:text-[#111827] py-2 transition-colors">Catalog</a>
                <a href="#features"   @click="open=false;active='features'"   class="block text-[#4B5563] hover:text-[#111827] py-2 transition-colors">Features</a>
                <a href="#enterprise" @click="open=false;active='enterprise'" class="block text-[#4B5563] hover:text-[#111827] py-2 transition-colors">Enterprise</a>
                <div class="border-t border-[#E5E7EB] pt-5 flex flex-col gap-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block text-[#4B5563] hover:text-[#111827] py-2 transition-colors">Dashboard</a>
                        <a href="{{ route('dashboard') }}" class="block bg-[#2255FF] px-5 py-3 text-center text-white hover:bg-[#111827] hover:text-[#FAFAFA] transition-none">Go to Dashboard</a>
                    @else
                        <a href="{{ route('login') }}"    class="block text-[#4B5563] hover:text-[#111827] py-2 transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="block bg-[#2255FF] px-5 py-3 text-center text-white hover:bg-[#111827] hover:text-[#FAFAFA] transition-none">Join Free</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="relative overflow-x-hidden">

        {{-- ─────────────────────────────────────────────────────────────
             HERO
        ───────────────────────────────────────────────────────────────── --}}
        <section id="hero" class="relative min-h-screen flex flex-col justify-center pt-24 pb-20">

            {{-- Ambient blobs --}}
            <div class="absolute inset-0 pointer-events-none overflow-hidden">
                <div class="absolute -left-32 top-20 h-96 w-96 rounded-none bg-[#2255FF]/[0.05] blur-[120px]"></div>
                <div class="absolute right-0 bottom-20 h-80 w-80 rounded-none bg-[#2255FF]/[0.03] blur-[100px]"></div>
                <div class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 h-[600px] w-[600px] rounded-none bg-[#2255FF]/[0.02] blur-[160px]"></div>
            </div>

            {{-- Vertical decorative text --}}
            <div class="absolute right-6 top-1/2 -translate-y-1/2 hidden xl:block">
                <span class="writing-mode-vertical font-mono text-[9px] uppercase tracking-[0.4em] text-[#1A1A1A] select-none"
                      style="writing-mode:vertical-rl; letter-spacing:0.4em;">
                    MASTERY THROUGH IMMERSION
                </span>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8 w-full">

                {{-- Top label --}}
                <div class="flex items-center gap-4 mb-10" data-reveal>
                    <div class="w-px h-5 bg-primary-600"></div>
                    <span class="font-mono text-xs uppercase tracking-wider text-slate-600">Est. 2024 · Next-Gen Education Platform</span>
                    <div class="w-2 h-2 bg-accent-500 pulse-dot rounded-full"></div>
                </div>

                <div class="grid gap-16 lg:grid-cols-[1.25fr_0.75fr] items-center">
                    <div>
                        {{-- Main headline --}}
                        <h1 class="font-display font-black leading-none text-slate-900"
                            style="font-size: clamp(3.5rem, 10vw, 9rem); letter-spacing: -0.05em;" data-reveal>
                            UNLEASH<br>
                            YOUR <span class="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">TRUE</span><br>
                            POTENTIAL
                        </h1>

                        <p class="mt-8 max-w-lg text-base leading-relaxed text-slate-600" data-reveal style="transition-delay:80ms">
                            Premium curriculum designed for ambitious learners who want skills, credibility, and real work outcomes — not just another certificate.
                        </p>

                        {{-- CTAs --}}
                        <div class="mt-10 flex flex-col sm:flex-row sm:items-center gap-4" data-reveal style="transition-delay:160ms">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="btn btn-primary btn-lg group">
                                    Go to Dashboard
                                    <span class="inline-block translate-x-0 group-hover:translate-x-1 transition-transform duration-150">→</span>
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="btn btn-primary btn-lg group">
                                    Start Learning Now
                                    <span class="inline-block translate-x-0 group-hover:translate-x-1 transition-transform duration-150">→</span>
                                </a>
                            @endauth
                            <a href="#programs"
                               class="btn btn-ghost btn-lg group">
                                See Programs
                                <span class="inline-block opacity-0 group-hover:opacity-100 transition-opacity duration-150">↓</span>
                            </a>
                        </div>

                        {{-- Social proof --}}
                        <div class="mt-12 flex flex-col sm:flex-row sm:items-center gap-8" data-reveal style="transition-delay:240ms">
                            <div class="flex items-center gap-4">
                                <div class="relative flex -space-x-3">
                                    @foreach([1,2,3,4] as $avatar)
                                        <img src="https://i.pravatar.cc/128?img={{ 10 + $avatar }}"
                                             alt="Learner"
                                             class="w-10 h-10 border-2 border-white bg-white object-cover rounded-full" />
                                    @endforeach
                                </div>
                                <div>
                                    <p class="font-mono text-xs uppercase tracking-wider text-slate-900 font-semibold">12,500+</p>
                                    <p class="font-mono text-xs uppercase tracking-wider text-slate-600">enrolled globally</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 border-l border-slate-200 pl-8">
                                <div class="text-yellow-500 text-sm">★★★★★</div>
                                <span class="font-mono text-xs text-slate-600">4.9 avg rating</span>
                            </div>
                        </div>
                    </div>

                    {{-- Right hero card --}}
                    <div class="relative hidden lg:block" data-reveal style="transition-delay:120ms">
                        <div class="card hover-lift">
                            {{-- Accent corner --}}
                            <div class="absolute top-0 left-0 w-12 h-12 border-t-2 border-l-2 border-primary-600"></div>
                            <div class="absolute bottom-0 right-0 w-12 h-12 border-b-2 border-r-2 border-accent-500"></div>

                            <p class="font-mono text-xs uppercase tracking-wider text-primary-600 font-semibold mb-6">Live cohort experience</p>
                            <h2 class="font-display text-3xl text-slate-900 leading-tight mb-4">Weekly live studio sessions with top mentors.</h2>
                            <p class="text-sm leading-relaxed text-slate-600 mb-8">Practice with peers, get live feedback, and keep every lesson instantly actionable.</p>

                            <div class="border-t border-slate-200 pt-6 space-y-3">
                                @foreach(['Build a high-impact portfolio', 'Earn elite recognition badges', 'Land roles with product teams'] as $outcome)
                                <div class="flex items-center gap-3">
                                    <span class="font-mono text-primary-600 text-sm">→</span>
                                    <span class="font-mono text-xs uppercase tracking-wider text-slate-600">{{ $outcome }}</span>
                                </div>
                                @endforeach
                            </div>

                            {{-- Live indicator --}}
                            <div class="mt-8 flex items-center gap-3 px-4 py-3 border border-slate-200 rounded-md bg-gradient-to-r from-success-50 to-transparent">
                                <div class="w-2 h-2 bg-success-600 pulse-dot rounded-full"></div>
                                <span class="font-mono text-xs uppercase tracking-wider text-slate-600">3 live sessions this week</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Trusted logos marquee --}}
                <div class="mt-20 border-t border-b border-slate-200 py-6 marquee-wrapper" data-reveal style="transition-delay:300ms">
                    <div class="marquee-track font-mono text-xs uppercase tracking-wider text-slate-500"
                         @mouseover="$el.classList.add('paused')"
                         @mouseleave="$el.classList.remove('paused')">
                        @foreach(array_merge($trustedLogos, $trustedLogos, $trustedLogos) as $logo)
                            <span class="pr-16 inline-block">{{ $logo }}</span>
                        @endforeach
                    </div>
                </div>
                <p class="mt-3 font-mono text-xs uppercase tracking-wider text-slate-500 text-center">Trusted by alumni from global leaders</p>

                {{-- Scroll indicator --}}
                <div class="mt-16 flex justify-center">
                    <a href="#programs" class="inline-flex flex-col items-center gap-2 group">
                        <span class="font-mono text-xs uppercase tracking-wider text-slate-500 group-hover:text-slate-600 transition-colors">Scroll to programs</span>
                        <span class="hero-arrow font-mono text-primary-600 text-lg">↓</span>
                    </a>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             SIGNATURE PROGRAMS
        ───────────────────────────────────────────────────────────────── --}}
        <section id="programs" class="py-28 border-t border-[#E5E7EB]">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">

                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between mb-14" data-reveal>
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="font-mono text-xs uppercase tracking-wider text-primary-600 font-semibold">▸ Elite Curriculum</span>
                        </div>
                        <h2 class="font-display text-slate-900 leading-none" style="font-size: clamp(2.5rem, 6vw, 5.5rem);">
                            SIGNATURE<br>PROGRAMS
                        </h2>
                        <p class="mt-5 max-w-xl text-base leading-relaxed text-slate-600">
                            Rigorous learning paths engineered by experts to transform your professional trajectory.
                        </p>
                    </div>
                    <a href="{{ route('courses.index') }}"
                       class="self-start btn btn-ghost group">
                        [ View All Courses
                        <span class="group-hover:translate-x-1 transition-transform duration-150">→</span>
                        ]
                    </a>
                </div>

                {{-- Course cards horizontal scroll --}}
                <div class="overflow-x-auto no-scrollbar py-3">
                    <div class="flex snap-x gap-5 pb-4" style="width: max-content;">
                        @forelse($topCourses as $index => $course)
                            @php
                                $instructorAvatar = $course->instructor->avatar_url
                                    ? asset('storage/'.$course->instructor->avatar_url)
                                    : 'https://i.pravatar.cc/120?u='.urlencode($course->instructor->name ?? 'instructor');
                                $priceLabel  = $course->price > 0 ? '$'.number_format($course->price, 0) : 'Free';
                                $isFree      = $course->price == 0;
                                $rating      = number_format($course->rating ?: 4.8, 1);
                                $students    = number_format($course->student_count ?: rand(500,2000));
                            @endphp
                            <article class="snap-start flex-shrink-0 w-[300px] sm:w-[320px] card hover-lift cursor-pointer"
                                     onclick="window.location='{{ route('courses.show', $course) }}'">

                                {{-- Category + badges --}}
                                <div class="flex items-center justify-between mb-5">
                                    <span class="font-mono text-xs uppercase tracking-wider text-slate-600 border border-slate-200 px-3 py-1.5 rounded-sm">
                                        {{ $course->category->name ?? 'General' }}
                                    </span>
                                    @if($isFree)
                                        <span class="font-mono text-xs uppercase tracking-wider text-success-600 font-semibold">Free</span>
                                    @elseif($index === 0)
                                        <span class="badge badge-warning text-xs font-bold">Bestseller</span>
                                    @endif
                                </div>

                                {{-- Visual --}}
                                <div class="flex h-40 items-center justify-center bg-gradient-to-br from-primary-100 to-accent-100 border border-slate-200 mb-5 overflow-hidden group-hover:border-primary-300 transition-colors duration-300 rounded-md">
                                    <span class="font-display text-6xl leading-none text-primary-600 group-hover:scale-105 transition-transform duration-300">
                                        {{ strtoupper(substr($course->title, 0, 1)) }}
                                    </span>
                                </div>

                                {{-- Title --}}
                                <h3 class="font-display text-slate-900 leading-tight mb-4 line-clamp-2"
                                    style="font-size: clamp(1.4rem, 2.5vw, 1.75rem);">
                                    {{ $course->title }}
                                </h3>

                                {{-- Instructor --}}
                                <div class="flex items-center gap-3 mb-5">
                                    <img src="{{ $instructorAvatar }}"
                                         alt="{{ $course->instructor->name ?? 'Instructor' }}"
                                         class="w-9 h-9 object-cover border border-slate-200 rounded-full" />
                                    <div>
                                        <p class="font-mono text-xs text-slate-900 font-semibold">{{ $course->instructor->name ?? 'Instructor' }}</p>
                                        <p class="font-mono text-xs uppercase tracking-wider text-slate-500">Instructor</p>
                                    </div>
                                </div>

                                {{-- Meta --}}
                                <div class="border-t border-slate-200 pt-4 flex items-center justify-between">
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-yellow-500 text-xs">★</span>
                                        <span class="font-mono text-xs font-semibold text-slate-900">{{ $rating }}</span>
                                        <span class="font-mono text-xs text-slate-500">({{ $students }})</span>
                                    </div>
                                    <span class="font-mono text-xs uppercase tracking-wider text-slate-900 font-semibold tabular-nums">{{ $priceLabel }}</span>
                                </div>
                            </article>
                        @empty
                            {{-- Empty state --}}
                            <div class="flex flex-col items-center justify-center w-full py-24 text-center">
                                <span class="font-display text-6xl text-slate-200">∅</span>
                                <p class="font-display text-2xl text-slate-500 mt-4">NO COURSES YET</p>
                                <p class="font-mono text-xs text-slate-500 mt-2">Check back soon — curriculum is being crafted.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             STATS BAR
        ───────────────────────────────────────────────────────────────── --}}
        <section id="stats" class="border-t border-slate-200 bg-white">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3">
                    @foreach([
                        ['count'=>'12500', 'suffix'=>'+', 'label'=>'Enrolled Globally', 'sublabel'=>'students worldwide'],
                        ['count'=>'98',    'suffix'=>'%', 'label'=>'Completion Rate',   'sublabel'=>'industry avg is 42%'],
                        ['count'=>'49',    'suffix'=>'/5','label'=>'Average Rating',     'sublabel'=>'across all courses'],
                    ] as $i => $stat)
                    <div class="flex flex-col items-center justify-center py-16 px-8
                                {{ $i < 2 ? 'border-b md:border-b-0 md:border-r border-slate-200' : '' }}"
                         data-reveal>
                        <div class="flex items-end gap-1">
                                <span class="font-mono text-primary-600 leading-none tabular-nums"
                                  style="font-size: clamp(3rem, 7vw, 5.5rem);"
                                  data-count="{{ $stat['count'] }}">0</span>
                                <span class="font-mono text-primary-600 text-3xl mb-2 tabular-nums">{{ $stat['suffix'] }}</span>
                        </div>
                        <p class="font-mono text-xs uppercase tracking-wider text-slate-900 font-semibold mt-3">{{ $stat['label'] }}</p>
                        <p class="font-mono text-xs uppercase tracking-wider text-slate-500 mt-1">{{ $stat['sublabel'] }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             FEATURES / BENTO
        ───────────────────────────────────────────────────────────────── --}}
        <section id="features" class="py-28 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">

                <div class="mb-14" data-reveal>
                    <span class="font-mono text-xs uppercase tracking-wider text-primary-600 font-semibold">▸ Experience</span>
                    <h2 class="font-display text-slate-900 mt-3 leading-none" style="font-size: clamp(2.5rem, 6vw, 5rem);">
                        WHY<br>LIVESCHOOL
                    </h2>
                </div>

                {{-- Bento grid --}}
                <div class="grid gap-4 lg:grid-cols-[1.4fr_0.9fr] lg:grid-rows-[280px_280px] bento-grid">

                    {{-- Cell 1: Masterclasses (large, primary gradient) --}}
                    <div class="relative overflow-hidden bg-gradient-to-br from-primary-600 to-primary-700 p-10 flex flex-col justify-between min-h-[280px]" data-reveal>
                        <div class="absolute inset-0 opacity-5"
                             style="background-image: url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E\")"></div>
                        <div>
                            <span class="font-mono text-xs uppercase tracking-wider text-white/70 font-semibold">Masterclasses</span>
                            <h3 class="font-display text-white leading-none mt-5" style="font-size:clamp(2rem,4vw,3rem);">
                                IMMERSIVE<br>MASTERCLASSES
                            </h3>
                        </div>
                        <p class="text-base leading-relaxed text-white/85 max-w-sm">
                            Step into high-fidelity live environments with real-time project collaboration and direct line to industry architects.
                        </p>
                    </div>

                    {{-- Cell 2: Elite Recognition (card) --}}
                    <div class="card hover-lift" data-reveal style="transition-delay:80ms">
                        <div class="w-14 h-14 border border-slate-200 bg-white flex items-center justify-center group-hover:border-primary-300 transition-colors duration-300">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.745 3.745 0 013.296-1.043A3.745 3.745 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-display text-slate-900 text-2xl mb-3">ELITE RECOGNITION</h4>
                            <p class="text-sm leading-relaxed text-slate-600">Earn blockchain-verifiable credentials that open doors at the world's most innovative companies.</p>
                        </div>
                    </div>

                    {{-- Cell 3: Portfolio Architecture --}}
                    <div class="card hover-lift" data-reveal style="transition-delay:160ms">
                        <div class="w-14 h-14 border border-slate-200 bg-white flex items-center justify-center group-hover:border-primary-300 transition-colors duration-300">
                            <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-display text-slate-900 text-2xl mb-3">PORTFOLIO ARCHITECTURE</h4>
                            <p class="text-sm leading-relaxed text-slate-600">Build production-grade projects that demonstrate mastery and solve real-world complexities.</p>
                        </div>
                    </div>

                    {{-- Cell 4: Community (Card themed) --}}
                    <div class="card hover-lift" data-reveal style="transition-delay:240ms">
                        <span class="font-mono text-xs uppercase tracking-wider text-slate-500">Ecosystem</span>
                        <div class="mt-4">
                            <h4 class="font-display text-slate-900 leading-none mb-4" style="font-size:clamp(2rem,4vw,3rem);">
                                VIBRANT<br>COMMUNITY
                            </h4>
                            <p class="text-sm leading-relaxed text-slate-600">
                                Join 15k+ elite builders in a collaborative space designed for high-performance networking and growth.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             TESTIMONIALS
        ───────────────────────────────────────────────────────────────── --}}
        <section id="testimonials" class="py-28 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">

                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between mb-12" data-reveal>
                    <div>
                        <span class="font-mono text-xs uppercase tracking-wider text-primary-600 font-semibold">▸ What Learners Say</span>
                        <h2 class="font-display text-slate-900 mt-4 leading-none" style="font-size: clamp(2rem, 5vw, 4.5rem);">
                            REAL FEEDBACK<br>FROM ALUMNI
                        </h2>
                    </div>
                    {{-- Dots --}}
                    <div class="flex items-center gap-3">
                        @foreach($testimonials as $index => $testimonial)
                            <button type="button"
                                    data-testimonial-dot="{{ $index }}"
                                    class="w-8 h-1 bg-slate-200 transition-all duration-200 hover:bg-slate-600"></button>
                        @endforeach
                    </div>
                </div>

                <div data-testimonials class="card" @mouseenter="clearInterval(window._testimonialTimer)" @mouseleave="startTestimonialTimer()">
                    @foreach($testimonials as $index => $testimonial)
                        <article class="testimonial-card {{ $index !== 0 ? 'hidden' : '' }}">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-accent-100 border border-slate-200 flex items-center justify-center font-display text-2xl text-primary-600 rounded-md">
                                        {{ $testimonial['initial'] }}
                                    </div>
                                    <div>
                                        <p class="font-mono text-sm text-slate-900 font-semibold">{{ $testimonial['name'] }}</p>
                                        <p class="font-mono text-xs uppercase tracking-wider text-slate-500">{{ $testimonial['role'] }} · {{ $testimonial['company'] }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <div class="text-yellow-500 text-sm">★★★★★</div>
                                    <span class="font-mono text-xs text-slate-600 font-semibold">{{ $testimonial['rating'] }}</span>
                                </div>
                            </div>
                            <p class="mt-8 text-lg leading-8 text-slate-600 max-w-3xl border-l-4 border-primary-600 pl-6">
                                "{{ $testimonial['quote'] }}"
                            </p>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             INSTRUCTOR SPOTLIGHT
        ───────────────────────────────────────────────────────────────── --}}
        <section id="instructors" class="py-28 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">

                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between mb-12" data-reveal>
                    <div>
                        <span class="font-mono text-xs uppercase tracking-wider text-primary-600 font-semibold">▸ Instructor Spotlight</span>
                        <h2 class="font-display text-slate-900 mt-4 leading-none" style="font-size: clamp(2rem, 5vw, 4.5rem);">
                            MEET THE<br>INSTRUCTORS
                        </h2>
                    </div>
                    <a href="#" class="self-start btn btn-ghost group">
                        Meet All Instructors
                        <span class="group-hover:translate-x-1 transition-transform duration-150">→</span>
                    </a>
                </div>

                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($instructors as $instructor)
                        <article class="card hover-lift" data-reveal>
                            <div class="flex items-center gap-4 mb-8">
                                <div class="w-14 h-14 bg-gradient-to-br from-primary-100 to-accent-100 border border-slate-200 flex items-center justify-center font-display text-2xl text-primary-600 rounded-md">
                                    {{ $instructor['initial'] }}
                                </div>
                                <div>
                                    <p class="font-mono text-sm text-slate-900 font-semibold">{{ $instructor['name'] }}</p>
                                    <p class="font-mono text-xs uppercase tracking-wider text-slate-600">{{ $instructor['expertise'] }}</p>
                                </div>
                            </div>

                            {{-- Rating bar --}}
                            <div class="mb-6">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="font-mono text-xs uppercase tracking-wider text-slate-500">Rating</span>
                                    <span class="font-mono text-xs text-slate-900 font-semibold">{{ $instructor['rating'] }}/5</span>
                                </div>
                                <div class="h-px bg-slate-200 relative overflow-hidden">
                                    <div class="absolute inset-y-0 left-0 bg-primary-600 stat-bar-fill"
                                         style="width: {{ ($instructor['rating']/5)*100 }}%"></div>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 border-t border-slate-200 pt-5">
                                <span class="badge badge-primary text-xs">{{ $instructor['courses'] }} courses</span>
                                <span class="badge badge-secondary text-xs">{{ $instructor['followers'] }} followers</span>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             FAQ
        ───────────────────────────────────────────────────────────────── --}}
        <section id="faq" class="py-28 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid gap-16 lg:grid-cols-2">
                    <div data-reveal>
                        <span class="font-mono text-xs uppercase tracking-wider text-primary-600 font-semibold">▸ FAQ</span>
                        <h2 class="font-display text-slate-900 mt-4 leading-none" style="font-size: clamp(2rem, 5vw, 4.5rem);">
                            COMMON<br>QUESTIONS
                        </h2>
                        <p class="mt-6 text-base leading-relaxed text-slate-600 max-w-sm">
                            Everything you need to know about LiveSchool before you commit.
                        </p>
                    </div>

                    <div class="space-y-0" x-data="{ open: null }">
                        @foreach($faqItems as $faqIndex => $faq)
                            <div class="border-t border-slate-200 {{ $loop->last ? 'border-b' : '' }}" data-reveal style="transition-delay: {{ $faqIndex * 60 }}ms">
                                <button type="button"
                                        @click="open = open === {{ $faqIndex }} ? null : {{ $faqIndex }}"
                                        class="w-full flex items-center justify-between py-6 text-left group">
                                    <span class="font-mono text-xs uppercase tracking-wider text-slate-900 group-hover:text-primary-600 transition-colors duration-150 font-semibold">{{ $faq['q'] }}</span>
                                    <span class="font-mono text-slate-600 ml-4 flex-shrink-0 transition-transform duration-200 text-lg font-bold"
                                          :class="open === {{ $faqIndex }} ? 'rotate-45' : 'rotate-0'">+</span>
                                </button>
                                <div x-show="open === {{ $faqIndex }}"
                                     x-transition:enter="transition ease-out duration-200"
                                     x-transition:enter-start="opacity-0 -translate-y-2"
                                     x-transition:enter-end="opacity-100 translate-y-0"
                                     x-transition:leave="transition ease-in duration-150"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 -translate-y-2"
                                     class="pb-6">
                                    <p class="text-base leading-relaxed text-slate-600 border-l-4 border-primary-600 pl-4">{{ $faq['a'] }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             FINAL CTA
        ───────────────────────────────────────────────────────────────── --}}
        <section id="enterprise" class="py-28 border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="relative overflow-hidden card text-center p-16">

                    {{-- Corner accents --}}
                    <div class="absolute top-0 left-0 w-16 h-16 border-t-2 border-l-2 border-primary-600"></div>
                    <div class="absolute top-0 right-0 w-16 h-16 border-t-2 border-r-2 border-primary-600"></div>
                    <div class="absolute bottom-0 left-0 w-16 h-16 border-b-2 border-l-2 border-accent-500"></div>
                    <div class="absolute bottom-0 right-0 w-16 h-16 border-b-2 border-r-2 border-accent-500"></div>

                    <div data-reveal>
                        <span class="font-mono text-xs uppercase tracking-wider text-slate-500">Ready when you are</span>
                        <h2 class="font-display text-slate-900 mt-6 leading-none" style="font-size: clamp(2.5rem, 8vw, 7rem);">
                            READY TO BUILD<br>THE <span class="bg-gradient-to-r from-primary-600 to-accent-500 bg-clip-text text-transparent">FUTURE?</span>
                        </h2>
                        <p class="mt-8 max-w-xl mx-auto text-base leading-relaxed text-slate-600">
                            Join LiveSchool and get the curriculum, community, and coaching that turns ambition into market-ready skills.
                        </p>

                        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-5">
                            @auth
                                <a href="{{ route('dashboard') }}"
                                   class="btn btn-primary btn-lg group">
                                    Open Dashboard
                                    <span class="group-hover:translate-x-1 transition-transform duration-150">→</span>
                                </a>
                            @else
                                <a href="{{ route('register') }}"
                                   class="btn btn-primary btn-lg group">
                                    Create Elite Account
                                    <span class="group-hover:translate-x-1 transition-transform duration-150">→</span>
                                </a>
                            @endauth
                            <div class="flex flex-col items-center gap-1">
                                <span class="font-mono text-xs uppercase tracking-wider text-slate-500">No credit card required</span>
                                <span class="font-mono text-xs uppercase tracking-wider text-slate-500">Instant workspace access</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ─────────────────────────────────────────────────────────────
             FOOTER
        ───────────────────────────────────────────────────────────────── --}}
        <footer class="border-t border-[#E5E7EB] pt-16 pb-8">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid gap-12 lg:grid-cols-[2fr_1fr_1fr_1fr]">

                    {{-- Brand --}}
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 bg-[#2255FF] flex items-center justify-center font-display text-white text-base">L</div>
                            <span class="font-mono text-[11px] uppercase tracking-[0.35em] text-[#111827]">LiveSchool</span>
                        </div>
                        <p class="text-[13px] leading-7 text-[#6B7280] max-w-xs">
                            Defining the new standard for professional education through immersion and elite mentorship.
                        </p>
                        {{-- Platform status --}}
                        <div class="mt-8 inline-flex items-center gap-3 border border-[#E5E7EB] px-4 py-2">
                            <div class="w-2 h-2 bg-[#1DB954] pulse-dot"></div>
                            <span class="font-mono text-[10px] uppercase tracking-[0.25em] text-[#6B7280]">All systems operational</span>
                        </div>
                    </div>

                    {{-- Platform links --}}
                    <div>
                        <p class="font-mono text-[10px] uppercase tracking-[0.35em] text-[#6B7280] mb-6">Platform</p>
                        <ul class="space-y-4">
                            @foreach(['Catalog'=>'#programs','Instructions'=>'#features','Enterprise'=>'#enterprise'] as $label => $href)
                                <li><a href="{{ $href }}" class="font-mono text-[12px] text-[#4B5563] hover:text-[#111827] underline-draw transition-colors duration-150">{{ $label }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Community links --}}
                    <div>
                        <p class="font-mono text-[10px] uppercase tracking-[0.35em] text-[#6B7280] mb-6">Community</p>
                        <ul class="space-y-4">
                            @foreach(['Events'=>'#','Forum'=>'#','Alumni'=>'#'] as $label => $href)
                                <li><a href="{{ $href }}" class="font-mono text-[12px] text-[#4B5563] hover:text-[#111827] underline-draw transition-colors duration-150">{{ $label }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Connect --}}
                    <div>
                        <p class="font-mono text-[10px] uppercase tracking-[0.35em] text-[#6B7280] mb-6">Connect</p>
                        <div class="flex items-center gap-4">
                            <a href="#" class="font-mono text-[12px] text-[#4B5563] hover:text-[#111827] transition-colors duration-150">TW</a>
                            <a href="#" class="font-mono text-[12px] text-[#4B5563] hover:text-[#111827] transition-colors duration-150">IG</a>
                        </div>
                    </div>
                </div>

                {{-- Footer bottom --}}
                <div class="mt-16 border-t border-[#E5E7EB] pt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <span class="font-mono text-[10px] uppercase tracking-[0.25em] text-[#6B7280]">© {{ date('Y') }} LiveSchool Platform. Knowledge for excellence.</span>
                    <div class="flex flex-wrap items-center gap-6">
                        @foreach(['Privacy', 'Terms', 'Contact'] as $link)
                            <a href="#" class="font-mono text-[10px] uppercase tracking-[0.25em] text-[#6B7280] hover:text-[#4B5563] transition-colors duration-150">{{ $link }}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </footer>

    </main>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Scroll reveal ──────────────────────────────────
        const revealEls = document.querySelectorAll('[data-reveal]');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                entry.target.classList.add('reveal');
                entry.target.classList.remove('reveal-hidden');
                revealObserver.unobserve(entry.target);
            });
        }, { threshold: 0.15 });
        revealEls.forEach(el => {
            el.classList.add('reveal-hidden');
            revealObserver.observe(el);
        });

        // ── Count-up stats ─────────────────────────────────
        const counters = document.querySelectorAll('[data-count]');
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const el     = entry.target;
                const target = parseInt(el.dataset.count.replace(/,/g, ''), 10);
                const suffix = el.nextElementSibling ? '' : '';
                const duration = 1400;
                let start = null;
                const easeOut = t => 1 - Math.pow(1 - t, 3);
                const step = (ts) => {
                    if (!start) start = ts;
                    const progress = Math.min((ts - start) / duration, 1);
                    const value = Math.floor(easeOut(progress) * target);
                    el.textContent = value >= 1000 ? value.toLocaleString() : value;
                    if (progress < 1) requestAnimationFrame(step);
                    else el.textContent = target >= 1000 ? target.toLocaleString() : target;
                };
                requestAnimationFrame(step);
                counterObserver.unobserve(el);
            });
        }, { threshold: 0.5 });
        counters.forEach(el => counterObserver.observe(el));

        // ── Section active nav ─────────────────────────────
        const sections = document.querySelectorAll('section[id]');
        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                const map = { programs: 'catalog', features: 'features', enterprise: 'enterprise' };
                const key = map[entry.target.id] || entry.target.id;
                document.dispatchEvent(new CustomEvent('section-change', { detail: key }));
            });
        }, { threshold: 0.4 });
        sections.forEach(s => sectionObserver.observe(s));

        // ── Testimonials ───────────────────────────────────
        const cards = document.querySelectorAll('.testimonial-card');
        const dots  = document.querySelectorAll('[data-testimonial-dot]');
        let idx = 0;

        const setTestimonial = (i) => {
            cards.forEach((c, ci) => c.classList.toggle('hidden', ci !== i));
            dots.forEach((d, di) => {
                d.style.background = di === i ? '#2255FF' : '#E5E7EB';
            });
            idx = i;
        };

        window.startTestimonialTimer = () => {
            window._testimonialTimer = setInterval(() => setTestimonial((idx + 1) % cards.length), 4000);
        };
        window.startTestimonialTimer();

        dots.forEach(d => d.addEventListener('click', () => {
            clearInterval(window._testimonialTimer);
            setTestimonial(parseInt(d.dataset.testimonialDot));
            window.startTestimonialTimer();
        }));
        setTestimonial(0);

        // ── FAQ keyboard support ────────────────────────────
        document.querySelectorAll('[data-faq-btn]').forEach(btn => {
            btn.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault(); btn.click();
                }
            });
        });

        // ── Marquee hover ──────────────────────────────────
        document.querySelectorAll('.marquee-track').forEach(track => {
            track.parentElement.addEventListener('mouseenter', () => track.classList.add('paused'));
            track.parentElement.addEventListener('mouseleave', () => track.classList.remove('paused'));
        });

    });
    </script>
</body>
</html>