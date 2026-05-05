<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LiveSchool') }} — Editorial Learning</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#0A0A0A] text-[#F0EDE6] font-sans antialiased selection:bg-brand-500/20 selection:text-[#F0EDE6]">

    @php
        $stats = [
            'students' => \App\Models\User::where('role', 'student')->count() + 1500,
            'courses' => \App\Models\Course::where('status', 'published')->count() + 45,
            'instructors' => \App\Models\User::where('role', 'instructor')->count() + 20,
        ];
        $topCourses = \App\Models\Course::where('status', 'published')->with('instructor', 'category')->orderByDesc('student_count')->take(4)->get();
    @endphp

    <nav x-data="{ scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 60"
         :class="{ 'bg-[#0A0A0A]/95 backdrop-blur-xl border-b border-white/10': scrolled, 'bg-transparent': !scrolled }"
         class="fixed inset-x-0 top-0 z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-6">
                    <span class="font-display text-[14px] uppercase tracking-[0.35em] text-white">LiveSchool</span>
                </div>
                <div class="hidden xl:flex items-center gap-10 text-[11px] uppercase tracking-[0.2em] font-black">
                    <a href="#" class="relative text-slate-500 hover:text-[#F0EDE6] transition-colors after:absolute after:-top-2 after:left-0 after:h-px after:w-0 after:bg-[#2255FF] after:transition-all after:duration-300 hover:after:w-full">Platform</a>
                    <a href="#features" class="relative text-slate-500 hover:text-[#F0EDE6] transition-colors after:absolute after:-top-2 after:left-0 after:h-px after:w-0 after:bg-[#2255FF] after:transition-all after:duration-300 hover:after:w-full">Features</a>
                    <a href="#" class="relative text-slate-500 hover:text-[#F0EDE6] transition-colors after:absolute after:-top-2 after:left-0 after:h-px after:w-0 after:bg-[#2255FF] after:transition-all after:duration-300 hover:after:w-full">Studio</a>
                </div>
                <div class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="border border-[#F0EDE6] px-6 py-3 uppercase text-[11px] tracking-[0.25em] font-black hover:bg-[#F0EDE6] hover:text-[#0A0A0A] transition-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-[11px] uppercase tracking-[0.25em] font-black text-slate-500 hover:text-[#F0EDE6] transition-colors">Login</a>
                        <a href="{{ route('register') }}" class="border border-[#F0EDE6] px-6 py-3 uppercase text-[11px] tracking-[0.25em] font-black hover:bg-[#F0EDE6] hover:text-[#0A0A0A] transition-none">Join Free</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="relative overflow-hidden">
        <section class="relative min-h-screen pt-28 pb-24">
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute top-0 left-0 h-[280px] w-[280px] rounded-full bg-[#2255FF]/10 blur-[100px]"></div>
                <div class="absolute right-0 bottom-0 h-[320px] w-[320px] rounded-full bg-[#2255FF]/8 blur-[120px]"></div>
            </div>

            <div class="relative z-10 max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-1 xl:grid-cols-[minmax(0,1fr)_260px] gap-14 items-start">
                    <div>
                        <div class="flex items-center gap-4 text-[11px] uppercase tracking-[0.3em] font-black text-slate-500">
                            <span class="h-5 w-px bg-[#F0EDE6] block"></span>
                            EST. 2024 · NEXT-GEN EDUCATION
                        </div>
                        <div class="mt-10 max-w-3xl">
                            <div class="text-[12vw] leading-[0.9] font-display uppercase tracking-[-0.03em] text-white">UNLEASH YOUR</div>
                            <div class="mt-3 flex flex-wrap text-[12vw] leading-[0.9] font-display uppercase tracking-[-0.03em] text-[#2255FF]" style="max-width: 14ch;">
                                @foreach(str_split('TRUE POTENTIAL') as $letter)
                                    <span class="inline-block {{ $loop->index % 2 === 0 ? '-rotate-2' : 'rotate-2' }} {{ $loop->index % 3 === 0 ? 'translate-y-2' : '' }} {{ $loop->index % 4 === 0 ? '-translate-y-1' : '' }}">{{ $letter }}</span>
                                @endforeach
                            </div>
                        </div>
                        <p class="mt-10 max-w-[55ch] text-[16px] leading-7 text-slate-400 font-medium">Build a practice-led education system that feels handcrafted, fiercely focused, and built for people who want to leave a mark.</p>
                        <div class="mt-12 flex flex-col sm:flex-row sm:items-center gap-8">
                            <a href="{{ route('register') }}" class="group inline-flex items-center gap-3 text-[11px] uppercase tracking-[0.3em] font-black text-[#F0EDE6] border-b border-transparent hover:border-[#F0EDE6] transition-all duration-300">
                                <span>Join the cohort</span>
                                <span class="transform transition-transform duration-300 group-hover:translate-x-2">→</span>
                            </a>
                            <div class="flex items-center gap-3 text-[12px] uppercase tracking-[0.25em] font-black text-[#F0EDE6]">
                                <span class="inline-flex items-center gap-2">
                                    <span class="h-2 w-2 rounded-full bg-[#2255FF] animate-pulse"></span>
                                    12,500 enrolled
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="hidden xl:flex justify-end">
                        <div class="text-[11px] uppercase tracking-[0.3em] text-slate-600 font-black rotate-90 origin-top-right">MASTERY THROUGH IMMERSION</div>
                    </div>
                </div>
            </div>

            <div class="absolute inset-x-0 bottom-0 pointer-events-none py-6">
                <div class="mx-auto max-w-7xl overflow-hidden border-t border-white/5">
                    <div class="marquee-track text-[11px] uppercase tracking-[0.3em] text-[#333] font-black opacity-90">
                        GOOGLE · META · AMAZON · APPLE · NETFLIX · GOOGLE · META · AMAZON · APPLE · NETFLIX ·
                    </div>
                </div>
            </div>
        </section>

        <section class="py-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row justify-between gap-10 items-start mb-10">
                    <div>
                        <p class="text-[10px] uppercase tracking-[0.35em] text-[#2255FF] font-black mb-6">▸ Elite Curriculum</p>
                        <h2 class="text-[8vw] leading-[0.85] font-display uppercase tracking-[-0.03em] text-white max-w-2xl">Signature Programs</h2>
                    </div>
                    <a href="{{ route('courses.index') }}" class="inline-flex items-center gap-2 text-[11px] uppercase tracking-[0.3em] font-black text-[#F0EDE6] border border-[#F0EDE6] px-5 py-3 hover:bg-[#F0EDE6] hover:text-[#0A0A0A] transition-none">[ View All → ]</a>
                </div>

                <div class="overflow-x-auto no-scrollbar py-6">
                    <div class="flex gap-6 pb-4 min-w-[1440px]">
                        @foreach($topCourses as $course)
                            <article class="flex-shrink-0 w-[400px] h-[520px] bg-[#0A0A0A] border border-[#111] p-8 flex flex-col justify-between">
                                <span class="text-[10px] uppercase tracking-[0.25em] text-slate-500 font-black">{{ strtoupper($course->category->name) }}</span>
                                <div class="mt-8 text-[8rem] leading-none font-display text-[#111]" style="transform: rotate(5deg);">{{ strtoupper(substr($course->title, 0, 1)) }}</div>
                                <div>
                                    <h3 class="text-3xl font-display uppercase tracking-[-0.02em] text-white leading-tight">{{ $course->title }}</h3>
                                </div>
                                <div class="flex items-center justify-between text-[11px] uppercase tracking-[0.25em] font-black text-slate-500">
                                    <span>{{ $course->instructor->name }}</span>
                                    <span>4.9/5</span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-24">
            <div class="max-w-7xl mx-auto px-6 lg:px-8">
                <div class="grid grid-cols-12 gap-[2px]">
                    <div class="col-span-7 row-span-2 min-h-[520px] bg-[#2255FF] p-12 text-white overflow-hidden" data-reveal>
                        <span class="text-[10px] uppercase tracking-[0.3em] text-white/80">Immersive Masterclasses</span>
                        <h3 class="mt-8 text-[4vw] leading-[0.9] font-display uppercase tracking-[-0.03em]">Immersive Masterclasses</h3>
                        <p class="mt-8 max-w-lg text-[14px] leading-7 text-white/90">A deliberate program built to train technique, thinking, and execution in the same breath — no fluff, no ritual.</p>
                        <div class="pointer-events-none absolute inset-x-0 bottom-0 h-24 bg-[radial-gradient(circle,_rgba(255,255,255,0.05),transparent_40%)]"></div>
                    </div>

                    <div class="col-span-5 min-h-[240px] bg-[#0A0A0A] border border-[#111] p-10" data-reveal>
                        <div class="flex items-center justify-center mb-10">
                            <div class="w-20 h-20 border border-[#444] flex items-center justify-center">
                                <div class="w-10 h-10 border border-[#444] rounded-full animate-[spin_18s_linear_infinite]"></div>
                            </div>
                        </div>
                        <h4 class="text-2xl font-display uppercase tracking-[-0.02em] mb-6">Elite Recognition</h4>
                        <p class="text-[14px] leading-7 text-slate-400">A credential system designed with rigor, clarity, and the kind of detail that matters in elite networks.</p>
                    </div>

                    <div class="col-span-7 min-h-[240px] bg-[#111] p-10" data-reveal>
                        <div class="mb-10">
                            <div class="w-16 h-16 grid grid-cols-3 grid-rows-3 gap-1">
                                @for($i = 0; $i < 5; $i++)
                                    <div class="bg-slate-700"></div>
                                @endfor
                            </div>
                        </div>
                        <h4 class="text-2xl font-display uppercase tracking-[-0.02em] mb-6">Portfolio Architecture</h4>
                        <p class="text-[14px] leading-7 text-slate-400">Thoughtful project structure that turns work into evidence of craft and capability.</p>
                    </div>

                    <div class="col-span-5 row-span-2 min-h-[520px] bg-white p-12" data-reveal>
                        <h4 class="text-[3vw] font-display uppercase tracking-[-0.03em] text-black leading-[0.9]">Vibrant Community</h4>
                        <p class="mt-8 max-w-md text-[13px] leading-7 text-black/75">The only white surface on the page — a sharp editorial contrast that makes the message feel more urgent and alive.</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-[#0F0F0F] py-16">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div class="border-b border-white/10 pb-10 md:border-r md:border-b-0 md:pr-10">
                    <div class="text-[7vw] md:text-[6rem] font-display leading-[0.9] text-[#2255FF]" data-count="12500">0</div>
                    <div class="mt-4 text-[11px] uppercase tracking-[0.3em] text-[#666] font-black">enrolled globally</div>
                </div>
                <div class="border-b border-white/10 pb-10 md:border-r md:border-b-0 md:px-10">
                    <div class="text-[7vw] md:text-[6rem] font-display leading-[0.9] text-[#2255FF]" data-count="98">0</div>
                    <div class="mt-4 text-[11px] uppercase tracking-[0.3em] text-[#666] font-black">completion rate</div>
                </div>
                <div class="pt-6 md:pt-0 md:pl-10">
                    <div class="text-[7vw] md:text-[6rem] font-display leading-[0.9] text-[#2255FF]" data-count="49">0</div>
                    <div class="mt-4 text-[11px] uppercase tracking-[0.3em] text-[#666] font-black">average rating</div>
                </div>
            </div>
        </section>

        <section class="py-28">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
                <h2 class="text-[10vw] md:text-[8rem] leading-[0.9] font-display uppercase tracking-[-0.03em]">Ready to build the <span class="text-[#2255FF]">future?</span></h2>
                <div class="mt-12 flex flex-col sm:flex-row items-center justify-center gap-6">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center w-[200px] h-[52px] bg-[#2255FF] text-[#F0EDE6] uppercase tracking-[0.25em] font-black border border-[#2255FF] hover:bg-[#F0EDE6] hover:text-[#0A0A0A] transition-none">Create Account →</a>
                    <p class="text-[11px] uppercase tracking-[0.3em] text-[#555] font-black">No credit card · Instant access</p>
                </div>
            </div>
        </section>

        <footer class="border-t border-[#1A1A1A] pt-16 pb-10">
            <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 md:grid-cols-12 gap-12">
                <div class="md:col-span-4">
                    <div class="font-display text-2xl uppercase tracking-[-0.03em] text-white mb-6">LiveSchool</div>
                    <p class="text-[13px] leading-7 text-slate-500 max-w-sm">A platform built for people who want to move faster, think deeper, and create work that matters.</p>
                </div>
                <div class="md:col-span-3">
                    <div class="text-[11px] uppercase tracking-[0.3em] text-slate-500 mb-8">Platform</div>
                    <ul class="space-y-4 text-[11px] leading-[2.4] uppercase tracking-[0.2em] text-[#444]">
                        <li><a href="#" class="hover:text-[#F0EDE6] transition-colors">Catalog</a></li>
                        <li><a href="#" class="hover:text-[#F0EDE6] transition-colors">Instructors</a></li>
                        <li><a href="#" class="hover:text-[#F0EDE6] transition-colors">Enterprise</a></li>
                    </ul>
                </div>
                <div class="md:col-span-3">
                    <div class="text-[11px] uppercase tracking-[0.3em] text-slate-500 mb-8">Community</div>
                    <ul class="space-y-4 text-[11px] leading-[2.4] uppercase tracking-[0.2em] text-[#444]">
                        <li><a href="#" class="hover:text-[#F0EDE6] transition-colors">Events</a></li>
                        <li><a href="#" class="hover:text-[#F0EDE6] transition-colors">Forum</a></li>
                        <li><a href="#" class="hover:text-[#F0EDE6] transition-colors">Alumni</a></li>
                    </ul>
                </div>
                <div class="md:col-span-2">
                    <div class="text-[11px] uppercase tracking-[0.3em] text-slate-500 mb-8">Social</div>
                    <div class="space-y-4 text-[11px] leading-[2.4] uppercase tracking-[0.2em] text-[#444]">
                        <a href="#" class="hover:text-[#F0EDE6] transition-colors">TW</a>
                        <a href="#" class="hover:text-[#F0EDE6] transition-colors">IG</a>
                    </div>
                </div>
            </div>
            <div class="mt-16 pt-8 border-t border-[#1A1A1A] flex flex-col md:flex-row justify-between items-center gap-4 text-[10px] uppercase tracking-[0.2em] text-[#333]">
                <span>© {{ date('Y') }} LiveSchool Platform</span>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-[#F0EDE6] transition-colors">Privacy</a>
                    <a href="#" class="hover:text-[#F0EDE6] transition-colors">Terms</a>
                    <a href="#" class="hover:text-[#F0EDE6] transition-colors">Contact</a>
                </div>
            </div>
        </footer>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const revealElements = document.querySelectorAll('[data-reveal]');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry) => {
                    if (!entry.isIntersecting) return;
                    entry.target.classList.add('reveal');
                    observer.unobserve(entry.target);
                });
            }, { threshold: 0.2 });

            revealElements.forEach((el) => {
                el.classList.add('reveal-hidden');
                observer.observe(el);
            });

            const counters = document.querySelectorAll('[data-count]');
            counters.forEach((counter) => {
                const target = Number(counter.dataset.count);
                const duration = 1200;
                let start = null;
                const step = (timestamp) => {
                    if (!start) start = timestamp;
                    const progress = Math.min((timestamp - start) / duration, 1);
                    counter.textContent = Math.floor(progress * target).toLocaleString();
                    if (progress < 1) {
                        window.requestAnimationFrame(step);
                    }
                };
                window.requestAnimationFrame(step);
            });
        });
    </script>
</body>
</html>
