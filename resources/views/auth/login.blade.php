<x-guest-layout>
    <x-slot name="pageTitle">Sign In</x-slot>

    <div x-data="{
        showPassword: false,
        email: '',
        password: '',
        loading: false,
        hasError: {{ session('errors') ? 'true' : 'false' }},
    }">

        {{-- ── Page title ─────────────────────────────── --}}
        <div class="mb-10">
            <h2 class="font-display text-[#0F172A] leading-none"
                style="font-size: clamp(2.8rem, 4vw, 4rem);">
                WELCOME<br>BACK.
            </h2>
            <p class="font-mono text-[13px] text-slate-500 mt-4">
                Sign in to continue your learning journey
            </p>
        </div>

        {{-- ── Session status ──────────────────────────── --}}
        @if (session('status'))
            <div class="mb-6 border border-[#1DB954] bg-[#1DB954]/10 px-4 py-3 animate-in fade-in slide-in-from-top-2 duration-300">
                <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-[#1DB954]">
                    ✓ {{ session('status') }}
                </p>
            </div>
        @endif

        {{-- ── Global error ────────────────────────────── --}}
        @if ($errors->any())
            <div class="mb-6 border border-[#FF3B30] bg-[#FF3B30]/8 px-4 py-3 flex items-center gap-3 field-error-slide"
                 x-data x-init="
                     $el.style.animation = 'none';
                     requestAnimationFrame(() => {
                         $el.style.animation = 'shake .4s cubic-bezier(.36,.07,.19,.97) both';
                     });
                 ">
                <span class="font-mono text-[#FF3B30] text-lg flex-shrink-0">✕</span>
                <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-[#FF3B30]">
                    These credentials don't match our records.
                </p>
            </div>
        @endif

        {{-- ── Google OAuth ────────────────────────────– --}}
        <a href="#"
           class="ls-btn-ghost mb-5 flex items-center justify-center gap-3 no-underline"
           style="text-decoration:none;">
            {{-- Google G SVG --}}
            <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none">
                <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
            </svg>
            <span>Continue with Google</span>
        </a>

        {{-- ── Divider ─────────────────────────────────── --}}
        <div class="or-divider my-6">or sign in with email</div>

        {{-- ── Login form ──────────────────────────────── --}}
        <form method="POST" action="{{ route('login') }}"
              @submit="loading = true"
              class="space-y-5">
            @csrf

            {{-- Email --}}
            <div>
                <label for="email" class="ls-label">Email Address</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    x-model="email"
                    class="ls-input {{ $errors->get('email') ? 'error' : '' }}"
                    placeholder="you@example.com"
                />
                @error('email')
                    <span class="ls-error field-error-slide">{{ $message }}</span>
                @enderror
            </div>

            {{-- Password --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label for="password" class="ls-label" style="margin-bottom:0;">Password</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="link-underline font-mono text-[10px] uppercase tracking-[0.2em]">
                            Forgot?
                        </a>
                    @endif
                </div>
                <div class="ls-input-wrapper has-icon">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        x-model="password"
                        class="ls-input {{ $errors->get('password') ? 'error' : '' }}"
                        placeholder="••••••••"
                    />
                    {{-- Eye toggle --}}
                    <button type="button"
                            class="eye-btn"
                            @click="showPassword = !showPassword"
                            :aria-label="showPassword ? 'Hide password' : 'Show password'">
                        {{-- Eye open --}}
                        <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{-- Eye closed --}}
                        <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
                @error('password')
                    <span class="ls-error field-error-slide">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remember me --}}
            <div class="flex items-center gap-3 pt-1">
                <input id="remember_me"
                       type="checkbox"
                       name="remember"
                       class="ls-checkbox" />
                <label for="remember_me"
                       class="font-mono text-[11px] uppercase tracking-[0.15em] text-slate-500 cursor-pointer select-none">
                    Remember me
                </label>
            </div>

            {{-- Submit --}}
            <div class="pt-4">
                <button type="submit"
                        class="ls-btn-primary"
                        data-submit-btn
                        :disabled="loading"
                        :class="loading ? 'opacity-50 cursor-not-allowed' : ''">
                    <span x-show="!loading">Sign In →</span>
                    <span x-show="loading" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Signing in...
                    </span>
                </button>
            </div>
        </form>

        {{-- ── Register link ───────────────────────────── --}}
        <div class="mt-8 pt-6 border-t border-slate-200">
            <p class="font-mono text-[11px] uppercase tracking-[0.15em] text-slate-500 text-center">
                Don't have an account?
                <a href="{{ route('register') }}"
                   class="text-[#2255FF] hover:text-[#0F172A] transition-colors duration-150 ml-2">
                    Create one →
                </a>
            </p>
        </div>

        {{-- ── Trust signals ───────────────────────────── --}}
        <div class="mt-8 flex items-center justify-center gap-6">
            @foreach(['🔒 SSL Secured', '30-Day Guarantee', 'No Spam'] as $signal)
                <span class="font-mono text-[10px] uppercase tracking-[0.15em] text-[#1E1E1E]">{{ $signal }}</span>
            @endforeach
        </div>
    </div>

    {{-- ── Page-specific shake animation ──────────────── --}}
    @push('styles')
    <style>
        @keyframes shake {
            10%, 90% { transform: translateX(-2px); }
            20%, 80% { transform: translateX(3px); }
            30%, 50%, 70% { transform: translateX(-4px); }
            40%, 60% { transform: translateX(4px); }
        }
    </style>
    @endpush
</x-guest-layout>