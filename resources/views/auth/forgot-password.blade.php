<x-guest-layout>
    {{-- ── Page title ─────────────────────────────── --}}
    <div class="mb-10">
        <h2 class="font-display text-[#F0EDE6] leading-none"
            style="font-size: clamp(2.8rem, 4vw, 4rem);">
            RESET YOUR<br>PASSWORD.
        </h2>
        <p class="font-mono text-[13px] text-[#555] mt-4">
            We'll send you a link to reset your password.
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

    {{-- ── Forgot password form ───────────────────── --}}
    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        {{-- ── Email ──────────────────────────────– --}}
        <div>
            <label for="email" class="ls-label">Email Address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                class="ls-input {{ $errors->get('email') ? 'error' : '' }}"
                placeholder="name@company.com"
            />
            @error('email')
                <span class="ls-error field-error-slide">{{ $message }}</span>
            @enderror
        </div>

        {{-- ── Submit button ──────────────────────– --}}
        <button type="submit" class="ls-btn-primary">
            Send Reset Link →
        </button>
    </form>

    {{-- ── Back to login link ────────────────────── --}}
    <div class="mt-10 pt-8 border-t border-[#1E1E1E]">
        <p class="font-mono text-[11px] uppercase tracking-[0.15em] text-[#555] text-center">
            Remember your password?
            <a href="{{ route('login') }}" class="link-underline">
                Sign in →
            </a>
        </p>
    </div>
</x-guest-layout>
