<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'LiveSchool') }} — {{ $pageTitle ?? 'Welcome' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* ── Reset & base ───────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { cursor: none; }
        .font-display { font-family: 'Bebas Neue', 'Impact', sans-serif; letter-spacing: 0.05em; }
        .font-mono    { font-family: 'JetBrains Mono', monospace; }

        /* ── Custom cursor ──────────────────────────────── */
        #cursor-dot {
            position: fixed; top: 0; left: 0; z-index: 9999;
            width: 8px; height: 8px; border-radius: 50%;
            background: #F0EDE6; pointer-events: none;
            transform: translate(-50%, -50%);
            transition: width .2s ease, height .2s ease;
            mix-blend-mode: difference;
        }
        #cursor-ring {
            position: fixed; top: 0; left: 0; z-index: 9998;
            width: 40px; height: 40px; border-radius: 50%;
            border: 1.5px solid rgba(240,237,230,.45);
            pointer-events: none;
            transform: translate(-50%, -50%);
            transition: width .25s ease, height .25s ease, opacity .2s ease;
            mix-blend-mode: difference;
        }
        body.cur-hover #cursor-dot  { width: 12px; height: 12px; }
        body.cur-hover #cursor-ring { width: 56px; height: 56px; opacity: .55; }

        /* ── Noise overlay ──────────────────────────────── */
        body::before {
            content: ''; position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
            opacity: .025;
        }

        /* ── Grid dot pattern (left panel) ─────────────── */
        .dot-grid {
            background-image: radial-gradient(rgba(255,255,255,.04) 1px, transparent 1px);
            background-size: 32px 32px;
        }

        /* ── Ambient glow ───────────────────────────────── */
        .glow-blob {
            position: absolute; border-radius: 50%; filter: blur(100px);
            pointer-events: none;
        }

        /* ── Stat ticker animation ──────────────────────── */
        @keyframes tick-up {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        .ticker { animation: tick-up .5s cubic-bezier(.16,1,.3,1) both; }

        /* ── Pulsing live dot ───────────────────────────── */
        @keyframes pulse-ring {
            0%   { transform: scale(1);   opacity: 1; }
            100% { transform: scale(2.2); opacity: 0; }
        }
        .live-dot::after {
            content: ''; position: absolute; inset: 0; border-radius: 50%;
            background: #1DB954;
            animation: pulse-ring 1.8s ease-out infinite;
        }
        .live-dot { position: relative; }

        /* ── Page-load stagger ──────────────────────────── */
        .fade-up {
            opacity: 0; transform: translateY(32px);
            animation: fade-up-anim .7s cubic-bezier(.16,1,.3,1) forwards;
        }
        @keyframes fade-up-anim {
            to { opacity: 1; transform: translateY(0); }
        }
        .delay-1 { animation-delay: .08s; }
        .delay-2 { animation-delay: .16s; }
        .delay-3 { animation-delay: .24s; }
        .delay-4 { animation-delay: .32s; }
        .delay-5 { animation-delay: .40s; }

        /* ── Input styles (global for slot forms) ───────── */
        .ls-input {
            width: 100%;
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #0f172a;
            padding: 14px 16px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
            border-radius: 6px;
            -webkit-appearance: none;
        }
        .ls-input:focus {
            border-color: #2255FF;
            box-shadow: 0 0 0 3px rgba(34, 85, 255, 0.1);
        }
        .ls-input::placeholder { color: #9ca3af; }
        .ls-input.error {
            border-color: #FF3B30;
            box-shadow: 0 0 0 3px rgba(255, 59, 48, 0.1);
        }
        .ls-label {
            display: block;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .15em;
            color: #374151;
            margin-bottom: 8px;
            font-weight: 600;
        }
        .ls-error {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            color: #FF3B30;
            margin-top: 6px;
            display: block;
            font-weight: 500;
        }
        .ls-btn-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 16px 24px;
            background: #2255FF;
            color: #ffffff;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .25em;
            border: none;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 600;
            transition: all .2s ease;
        }
        .ls-btn-primary:hover {
            background: #1f4ce2;
            box-shadow: 0 4px 12px rgba(34, 85, 255, 0.3);
            transform: translateY(-2px);
        }
        .ls-btn-primary:active {
            transform: translateY(0);
        }
        .ls-btn-primary:disabled {
            opacity: .6;
            cursor: not-allowed;
            transform: none;
        }
        .ls-btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 14px 24px;
            background: transparent;
            color: #374151;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .2em;
            border: 1px solid #d1d5db;
            cursor: pointer;
            border-radius: 6px;
            font-weight: 600;
            transition: all .15s ease;
        }
        .ls-btn-ghost:hover {
            border-color: #2255FF;
            color: #2255FF;
            background: rgba(34, 85, 255, 0.05);
        }

        /* ── Password strength bar ──────────────────────── */
        .strength-bar {
            display: flex;
            gap: 4px;
            height: 4px;
            margin-top: 8px;
            border-radius: 2px;
            overflow: hidden;
        }
        .strength-seg {
            flex: 1;
            height: 100%;
            background: #e5e7eb;
            border-radius: 2px;
            transition: background .3s ease;
        }
        .strength-seg.weak   { background: #FF3B30; }
        .strength-seg.fair   { background: #F5A623; }
        .strength-seg.good   { background: #1DB954; }
        .strength-seg.strong { background: #1DB954; }

        /* ── Show/hide eye icon ─────────────────────────── */
        .eye-btn {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6b7280;
            padding: 4px;
            transition: color .15s ease;
        }
        .eye-btn:hover { color: #1f2937; }

        /* ── Divider ────────────────────────────────────── */
        .or-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .2em;
            color: #6b7280;
            font-weight: 500;
        }
        .or-divider::before,
        .or-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e5e7eb;
        }

        /* ── Vertical decorative type ───────────────────── */
        .vert-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            transform: rotate(180deg);
            letter-spacing: .4em;
            user-select: none;
        }

        /* ── Checkbox ───────────────────────────────────── */
        .ls-checkbox {
            width: 16px;
            height: 16px;
            min-width: 16px;
            background: #ffffff;
            border: 1.5px solid #d1d5db;
            appearance: none;
            cursor: pointer;
            border-radius: 4px;
            transition: all .15s ease;
        }
        .ls-checkbox:hover {
            border-color: #9ca3af;
        }
        .ls-checkbox:checked {
            background: #2255FF;
            border-color: #2255FF;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cpath fill='none' stroke='%23fff' stroke-width='2' d='M3 8l3.5 3.5L13 4'/%3E%3C/svg%3E");
            background-size: 12px;
            background-repeat: no-repeat;
            background-position: center;
        }
        .ls-checkbox:focus {
            border-color: #2255FF;
            outline: none;
            box-shadow: 0 0 0 3px rgba(34, 85, 255, 0.1);
        }

        /* ── Password strength bar — enhanced ────────────── */
        .strength-bar-container {
            display: flex; align-items: center; gap: 8px;
            margin-top: 8px;
        }
        .strength-bar { 
            display: flex; gap: 2px; height: 2px; flex: 1; 
        }
        .strength-seg {
            flex: 1; height: 100%; background: #1E1E1E;
            transition: background .3s cubic-bezier(.16,1,.3,1);
            border-radius: 1px;
        }
        .strength-seg.weak   { background: #FF3B30; box-shadow: 0 0 6px rgba(255,59,48,0.4); }
        .strength-seg.fair   { background: #F5A623; box-shadow: 0 0 6px rgba(245,166,35,0.4); }
        .strength-seg.good   { background: #1DB954; box-shadow: 0 0 6px rgba(29,185,84,0.4); }
        .strength-seg.strong { background: #1DB954; box-shadow: 0 0 6px rgba(29,185,84,0.4); }

        .strength-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #6b7280;
            width: 50px;
            text-align: right;
            transition: color .3s ease;
        }
        .strength-label.weak   { color: #FF3B30; }
        .strength-label.fair   { color: #F5A623; }
        .strength-label.good   { color: #1DB954; }
        .strength-label.strong { color: #1DB954; }

        /* ── Success state animations ──────────────────────── */
        .success-checkmark {
            position: relative; width: 60px; height: 60px;
            margin: 0 auto 20px;
        }
        .success-checkmark .check-icon {
            width: 60px; height: 60px;
            position: relative; border-radius: 50%;
            box-sizing: content-box;
            border: 4px solid #1DB954;
            display: flex; align-items: center; justify-content: center;
        }
        .success-checkmark .icon-line {
            height: 5px; background-color: #1DB954;
            display: block; border-radius: 2px;
            position: absolute; z-index: 10;
        }
        .success-checkmark .icon-line.line-tip {
            top: 27px; left: 14px; width: 17px;
            transform: scaleX(0); animation: icon-line-tip .75s cubic-bezier(.16,1,.3,1) forwards; animation-delay: .5s;
        }
        .success-checkmark .icon-line.line-long {
            top: 41px; right: 8px; width: 29px;
            transform: scaleX(0); animation: icon-line-long .75s cubic-bezier(.16,1,.3,1) forwards; animation-delay: .7s;
        }

        @keyframes icon-line-tip {
            0% { width: 0; left: 1px; opacity: 0; }
            50% { width: 0; left: 0; opacity: 1; }
            100% { width: 17px; left: 0; opacity: 1; }
        }
        @keyframes icon-line-long {
            0% { width: 0; right: 46px; opacity: 0; }
            50% { width: 0; right: 0; opacity: 1; }
            100% { width: 29px; right: 8px; opacity: 1; }
        }

        /* ── Onboarding wizard styles ──────────────────────── */
        .wizard-container {
            position: fixed; inset: 0; background: #0A0A0A;
            z-index: 9999; display: flex; flex-direction: column;
        }
        .wizard-progress-bar {
            height: 1px; background: #1E1E1E;
            position: relative; width: 100%;
        }
        .wizard-progress-fill {
            height: 100%; background: #2255FF;
            transition: width .3s cubic-bezier(.16,1,.3,1);
        }
        .wizard-header {
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 32px; border-bottom: 1px solid #1E1E1E;
        }
        .wizard-step-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px; text-transform: uppercase;
            letter-spacing: 0.25em; color: #64748B;
        }
        .wizard-content {
            flex: 1; display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 64px 32px; overflow-y: auto;
        }
        .wizard-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: clamp(2.5rem, 5vw, 3.5rem);
            color: #F0EDE6; text-align: center; margin-bottom: 40px;
        }
        .wizard-tag-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
            gap: 12px; max-width: 600px; width: 100%;
        }
        .wizard-tag {
            padding: 12px 16px; border: 1px solid #1E1E1E;
            background: transparent; color: #F0EDE6;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px; text-transform: uppercase;
            letter-spacing: 0.1em; cursor: pointer;
            transition: all .2s ease;
            border-radius: 0;
        }
        .wizard-tag:hover { border-color: #F0EDE6; }
        .wizard-tag.selected {
            border-color: #2255FF; background: rgba(34, 85, 255, 0.1);
            color: #2255FF;
        }
        .wizard-tag.selected::after {
            content: '✓'; display: inline-block; margin-left: 8px;
        }
        .wizard-level-cards {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px; max-width: 600px; width: 100%;
        }
        .wizard-level-card {
            height: 200px; border: 1px solid #1E1E1E;
            background: #111; padding: 24px; cursor: pointer;
            transition: all .2s ease; display: flex;
            flex-direction: column; justify-content: space-between;
        }
        .wizard-level-card:hover { border-color: #F0EDE6; }
        .wizard-level-card.selected {
            border-color: #2255FF; background: rgba(34, 85, 255, 0.08);
        }
        .wizard-level-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 24px; color: #F0EDE6;
        }
        .wizard-level-desc {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px; color: #64748B; text-transform: uppercase;
            letter-spacing: 0.15em;
        }
        .wizard-slider-container {
            max-width: 400px; width: 100%;
        }
        .wizard-slider-label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px; text-transform: uppercase;
            letter-spacing: 0.2em; color: #64748B; margin-bottom: 16px;
        }
        .wizard-slider {
            width: 100%; height: 4px; border-radius: 0;
            background: #1E1E1E; outline: none;
            -webkit-appearance: none;
            appearance: none;
        }
        .wizard-slider::-webkit-slider-thumb {
            -webkit-appearance: none;
            appearance: none;
            width: 20px; height: 20px;
            border-radius: 0; background: #2255FF;
            cursor: pointer; transition: background .2s ease;
        }
        .wizard-slider::-moz-range-thumb {
            width: 20px; height: 20px;
            border-radius: 0; background: #2255FF;
            cursor: pointer; border: none;
        }
        .wizard-slider::-webkit-slider-thumb:hover { background: #1f4ce2; }
        .wizard-slider-value {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 2.5rem; color: #F0EDE6;
            text-align: center; margin-top: 16px;
        }
        .wizard-nav {
            display: flex; align-items: center; justify-content: space-between;
            padding: 20px 32px; border-top: 1px solid #1E1E1E;
        }
        .wizard-nav-btn {
            padding: 12px 24px; font-family: 'JetBrains Mono', monospace;
            font-size: 11px; text-transform: uppercase;
            letter-spacing: 0.2em; border: none; cursor: pointer;
            transition: all .2s ease; border-radius: 0;
        }
        .wizard-nav-btn.back {
            background: transparent; color: #94A3B8;
            border: 1px solid #1E1E1E;
        }
        .wizard-nav-btn.back:hover { border-color: #F0EDE6; color: #F0EDE6; }
        .wizard-nav-btn.next { background: #2255FF; color: white; }
        .wizard-nav-btn.next:hover { background: #1f4ce2; }
        .wizard-skip {
            position: absolute; top: 20px; right: 32px;
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px; text-transform: uppercase;
            letter-spacing: 0.2em; color: #94A3B8;
            cursor: pointer; border: none; background: transparent;
            transition: color .2s ease;
        }
        .wizard-skip:hover { color: #64748B; }

        /* ── Form field animation ──────────────────────────── */
        .field-error-slide {
            animation: error-slide-down .2s cubic-bezier(.36,.07,.19,.97) forwards;
        }
        @keyframes error-slide-down {
            from { opacity: 0; transform: translateY(-8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Link underline animation ──────────────────────── */
        .link-underline {
            position: relative; text-decoration: none; color: #2255FF;
            transition: color .2s ease;
        }
        .link-underline::after {
            content: ''; position: absolute; bottom: -2px;
            left: 0; width: 0; height: 1px; background: #2255FF;
            transition: width .3s cubic-bezier(.16,1,.3,1);
        }
        .link-underline:hover::after { width: 100%; }

        /* ── Input field enhancements ──────────────────────── */
        .ls-input-wrapper {
            position: relative;
        }
        .ls-input-wrapper.has-icon input {
            padding-right: 44px;
        }
        .ls-input[type="email"],
        .ls-input[type="password"],
        .ls-input[type="text"] {
            height: 48px;
        }

        /* ── Button size enhancements ──────────────────────── */
        .ls-btn-primary {
            height: 52px;
            font-size: 12px;
        }

        @media (max-width: 767px) {
            body { cursor: auto; }
            #cursor-dot, #cursor-ring { display: none; }
            
            .wizard-content { padding: 32px 20px; }
            .wizard-header { padding: 12px 16px; }
            .wizard-nav { padding: 16px; }
            .wizard-skip { top: 16px; right: 16px; }
            .wizard-title { font-size: clamp(1.8rem, 4vw, 2.5rem); margin-bottom: 24px; }
            .wizard-tag-grid { grid-template-columns: repeat(2, 1fr); }
            .wizard-level-cards { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#0F172A] antialiased selection:bg-[#2255FF]/10 selection:text-[#2255FF] min-h-screen">

    {{-- Custom cursor --}}
    <div id="cursor-dot"></div>
    <div id="cursor-ring"></div>

    <div class="min-h-screen flex w-full">

        {{-- ─────────────────────────────────────────────────
             LEFT PANEL — Decorative branding (desktop only)
        ───────────────────────────────────────────────────── --}}
        <div class="hidden lg:flex w-[52%] relative flex-col overflow-hidden border-r border-slate-200 bg-white">

            {{-- Dot grid --}}
            <div class="absolute inset-0 dot-grid"></div>

            {{-- Ambient glows --}}
            <div class="glow-blob w-96 h-96 bg-[#2255FF]/8 -top-20 -left-20"></div>
            <div class="glow-blob w-72 h-72 bg-[#2255FF]/5 bottom-20 right-10"></div>

            {{-- Top bar --}}
            <div class="relative z-10 flex items-center justify-between px-14 pt-12">
                <a href="/" class="flex items-center gap-3 group">
                    <div class="w-9 h-9 bg-violet-600 flex items-center justify-center font-display text-white text-lg transition-transform duration-200 group-hover:scale-105">L</div>
                    <span class="font-mono text-[11px] uppercase tracking-[0.35em] text-gray-900">LiveSchool</span>
                </a>
                {{-- Live status --}}
                <div class="flex items-center gap-2.5">
                    <div class="live-dot w-2 h-2 rounded-full bg-[#1DB954]"></div>
                    <span class="font-mono text-[10px] uppercase tracking-[0.25em] text-[#94A3B8]">Platform live</span>
                </div>
            </div>

            {{-- Center content --}}
            <div class="relative z-10 flex-1 flex flex-col items-start justify-center px-14 py-16">

                {{-- Vertical decorative label --}}
                <div class="absolute right-10 top-1/2 -translate-y-1/2">
                    <span class="vert-text font-mono text-[9px] uppercase text-gray-600">Knowledge for excellence</span>
                </div>

                {{-- Headline --}}
                <div class="fade-up">
                    <span class="font-mono text-[10px] uppercase tracking-[0.4em] text-[#64748B] flex items-center gap-3">
                        <span class="w-px h-4 bg-[#2255FF] inline-block"></span>
                        Est. 2024 · Next-Gen Education
                    </span>
                    <h1 class="font-display text-gray-900 leading-none mt-6"
                        style="font-size: clamp(3rem, 6vw, 5.5rem);">
                        BUILD THE<br>
                        <span class="text-violet-600">FUTURE</span><br>
                        OF LEARNING.
                    </h1>
                    <p class="mt-6 text-[14px] leading-8 text-[#64748B] max-w-sm">
                        Join an elite community of builders and scholars. Access world-class curriculum and interactive live environments.
                    </p>
                </div>

                {{-- Stats row --}}
                <div class="mt-14 flex items-stretch gap-0 fade-up delay-2">
                    @foreach([
                        ['value'=>'12,500+','label'=>'Learners'],
                        ['value'=>'4.9★',   'label'=>'Avg Rating'],
                        ['value'=>'98%',    'label'=>'Completion'],
                    ] as $i => $stat)
                    <div class="flex flex-col items-center justify-center px-8 py-5 {{ $i < 2 ? 'border-r border-gray-200' : '' }} {{ $i > 0 ? '' : '' }}">
                        <span class="font-display text-gray-900 text-2xl">{{ $stat['value'] }}</span>
                        <span class="font-mono text-[10px] uppercase tracking-[0.25em] text-[#94A3B8] mt-1">{{ $stat['label'] }}</span>
                    </div>
                    @endforeach
                </div>

                {{-- Avatar social proof --}}
                <div class="mt-10 flex items-center gap-5 fade-up delay-3">
                    <div class="relative flex -space-x-3">
                        @foreach([1,2,3,4,5] as $i)
                            <img src="https://i.pravatar.cc/80?img={{ $i + 8 }}"
                                 alt="Learner"
                                 class="w-9 h-9 object-cover border-2 border-white" />
                        @endforeach
                    </div>
                    <div>
                        <p class="font-mono text-[11px] text-gray-900">+12k learners</p>
                        <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-[#94A3B8]">trusting LiveSchool today</p>
                    </div>
                </div>

                {{-- Feature chips --}}
                <div class="mt-10 flex flex-wrap gap-3 fade-up delay-4">
                    @foreach(['🎥 Live sessions','📜 Certificates','🏆 Elite badges','👥 Community'] as $chip)
                        <span class="font-mono text-[10px] uppercase tracking-[0.15em] text-gray-600 border border-gray-200 px-3 py-2">{{ $chip }}</span>
                    @endforeach
                </div>
            </div>

            {{-- Bottom testimonial strip --}}
            <div class="relative z-10 mx-14 mb-12 border border-gray-200 bg-gray-50 p-6 fade-up delay-5">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-white border border-gray-200 flex items-center justify-center font-display text-violet-600 text-xl flex-shrink-0">M</div>
                    <div>
                        <p class="text-[13px] leading-6 text-gray-600 border-l-2 border-violet-600 pl-3">
                            "LiveSchool helped me ship my first SaaS product while learning design, strategy, and mentorship."
                        </p>
                        <p class="font-mono text-[10px] uppercase tracking-[0.2em] text-[#94A3B8] mt-3">Maya Patel · Product Designer · Spark Labs</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─────────────────────────────────────────────────
             RIGHT PANEL — Form slot
        ───────────────────────────────────────────────────── --}}
        <div class="w-full lg:w-[48%] flex flex-col min-h-screen bg-[#F8FAFC] relative">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center justify-between px-6 pt-8 pb-0">
                <a href="/" class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-violet-600 flex items-center justify-center font-display text-white text-lg">L</div>
                    <span class="font-mono text-[11px] uppercase tracking-[0.35em] text-gray-900">LiveSchool</span>
                </a>
                <a href="/" class="font-mono text-[10px] uppercase tracking-[0.25em] text-gray-600 hover:text-gray-900 transition-colors duration-150">← Back</a>
            </div>

            {{-- Ambient blob --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-[#2255FF]/5 blur-[80px] pointer-events-none"></div>

            {{-- Form container --}}
            <div class="flex-1 flex items-center justify-center px-8 py-14 lg:px-16">
                <div class="w-full max-w-[420px]">

                    {{-- Back to home (desktop) --}}
                    <a href="/"
                       class="hidden lg:inline-flex items-center gap-2 font-mono text-[10px] uppercase tracking-[0.25em] text-[#94A3B8] hover:text-[#64748B] transition-colors duration-150 mb-10 fade-up">
                        ← liveschool.com
                    </a>

                    {{-- Slot: the actual auth form (login, register, etc.) --}}
                    <div class="fade-up delay-1">
                        {{ $slot }}
                    </div>

                    {{-- Footer links --}}
                    <div class="mt-12 pt-8 border-t border-[#1E1E1E] fade-up delay-4">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-4">
                                <a href="#" class="font-mono text-[10px] uppercase tracking-[0.2em] text-[#94A3B8] hover:text-[#64748B] transition-colors">Privacy</a>
                                <a href="#" class="font-mono text-[10px] uppercase tracking-[0.2em] text-[#94A3B8] hover:text-[#64748B] transition-colors">Terms</a>
                            </div>
                            <span class="font-mono text-[10px] uppercase tracking-[0.2em] text-[#1E1E1E]">© {{ date('Y') }} LiveSchool</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Custom cursor ──────────────────────────────────
        const dot  = document.getElementById('cursor-dot');
        const ring = document.getElementById('cursor-ring');
        if (dot && ring) {
            document.addEventListener('mousemove', e => {
                dot.style.left  = e.clientX + 'px';
                dot.style.top   = e.clientY + 'px';
                ring.style.left = e.clientX + 'px';
                ring.style.top  = e.clientY + 'px';
            });
            document.querySelectorAll('a,button,[role="button"],input,textarea,select').forEach(el => {
                el.addEventListener('mouseenter', () => document.body.classList.add('cur-hover'));
                el.addEventListener('mouseleave', () => document.body.classList.remove('cur-hover'));
            });
        }

        // ── Password strength meter ─────────────────────────
        const pwdInputs = document.querySelectorAll('input[type="password"][data-strength]');
        pwdInputs.forEach(input => {
            const barId = input.dataset.strength;
            const bar   = document.getElementById(barId);
            const lbl   = document.getElementById(barId + '-label');
            if (!bar) return;

            const segs = bar.querySelectorAll('.strength-seg');
            const getStrength = (v) => {
                let score = 0;
                if (v.length >= 8)                    score++;
                if (/[A-Z]/.test(v))                  score++;
                if (/[0-9]/.test(v))                  score++;
                if (/[^A-Za-z0-9]/.test(v))           score++;
                return score; // 0-4
            };
            const labels  = ['', 'WEAK', 'FAIR', 'GOOD', 'STRONG'];
            const classes  = ['', 'weak', 'fair', 'good', 'strong'];
            const colors   = ['', '#FF3B30', '#F5A623', '#1DB954', '#1DB954'];

            input.addEventListener('input', () => {
                const s = getStrength(input.value);
                segs.forEach((seg, i) => {
                    seg.className = 'strength-seg';
                    if (i < s) seg.classList.add(classes[s]);
                });
                if (lbl) {
                    lbl.textContent = input.value.length > 0 ? labels[s] : '';
                    lbl.style.color = colors[s];
                }
            });
        });

        // ── Show/hide password toggle ───────────────────────
        document.querySelectorAll('[data-toggle-password]').forEach(btn => {
            btn.addEventListener('click', () => {
                const targetId = btn.dataset.togglePassword;
                const input    = document.getElementById(targetId);
                if (!input) return;
                const isText = input.type === 'text';
                input.type   = isText ? 'password' : 'text';
                const eyeOpen  = btn.querySelector('.eye-open');
                const eyeClosed = btn.querySelector('.eye-closed');
                if (eyeOpen)  eyeOpen.classList.toggle('hidden', !isText);
                if (eyeClosed) eyeClosed.classList.toggle('hidden', isText);
            });
        });

        // ── Submit button loading state ─────────────────────
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', () => {
                const btn = form.querySelector('[data-submit-btn]');
                if (!btn) return;
                btn.disabled = true;
                const original = btn.innerHTML;
                btn.innerHTML = `
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span>Processing...</span>
                `;
                // Restore if validation fails (500ms fallback)
                setTimeout(() => {
                    if (document.querySelectorAll('.ls-error:not(:empty)').length > 0) {
                        btn.disabled  = false;
                        btn.innerHTML = original;
                    }
                }, 2000);
            });
        });

        // ── Error field shake ───────────────────────────────
        document.querySelectorAll('.ls-input').forEach(input => {
            const observer = new MutationObserver(() => {
                if (input.classList.contains('error')) {
                    input.style.animation = 'none';
                    requestAnimationFrame(() => {
                        input.style.animation = 'shake .4s cubic-bezier(.36,.07,.19,.97) both';
                    });
                }
            });
            observer.observe(input, { attributes: true, attributeFilter: ['class'] });
        });

        // ── Cursor re-bind after Alpine/livewire updates ────
        document.addEventListener('livewire:load', () => {
            document.querySelectorAll('a,button,[role="button"],input,textarea').forEach(el => {
                el.addEventListener('mouseenter', () => document.body.classList.add('cur-hover'));
                el.addEventListener('mouseleave', () => document.body.classList.remove('cur-hover'));
            });
        });
    });
    </script>

    <style>
    @keyframes shake {
        10%,90% { transform: translateX(-2px); }
        20%,80% { transform: translateX(3px); }
        30%,50%,70% { transform: translateX(-4px); }
        40%,60% { transform: translateX(4px); }
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .animate-spin { animation: spin 1s linear infinite; }
    </style>
</body>
</html>