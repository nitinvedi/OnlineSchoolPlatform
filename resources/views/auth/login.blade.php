<x-guest-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .input-group { position: relative; margin-bottom: 1.5rem; }
        .input-group input { width: 100%; padding: 1.25rem 1rem 0.5rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; background: #f8fafc; outline: none; transition: all 0.3s ease; }
        .input-group input:focus { border-color: #0ea5e9; background: #fff; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }
        .input-group label { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-weight: 600; pointer-events: none; transition: all 0.3s ease; }
        .input-group input:focus + label, .input-group input:not(:placeholder-shown) + label { top: 0.7rem; font-size: 0.75rem; color: #0ea5e9; }
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }
    </style>

    <div x-data="{ submitting: false }" class="w-full" x-init="$el.classList.add('animate-fade-in-up')">
        
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-900 mb-2">Welcome back</h2>
            <p class="text-slate-500 font-medium">Please enter your details to sign in.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" @submit="submitting = true">
            @csrf

            <!-- Email Address -->
            <div class="input-group {{ $errors->has('email') ? 'shake' : '' }}">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus placeholder=" " />
                <label for="email">Email address</label>
                @if($errors->has('email'))
                    <p class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 text-sm text-rose-500 font-bold pointer-events-none">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Password -->
            <div class="input-group {{ $errors->has('password') ? 'shake' : '' }}">
                <input id="password" type="password" name="password" required placeholder=" " />
                <label for="password">Password</label>
                @if($errors->has('password'))
                    <p class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 text-sm text-rose-500 font-bold pointer-events-none">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between mb-8">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <div class="relative flex items-center justify-center w-5 h-5 rounded border-2 border-slate-300 group-hover:border-sky-500 transition-colors mr-2">
                        <input id="remember_me" type="checkbox" class="peer opacity-0 absolute inset-0 cursor-pointer w-full h-full" name="remember">
                        <svg class="w-3.5 h-3.5 text-sky-500 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-sm font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-bold text-sky-500 hover:text-sky-600 transition-colors" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-slate-900 text-white font-black py-4 rounded-xl shadow-lg hover:bg-slate-800 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group">
                <span x-show="!submitting" class="relative z-10 flex items-center justify-center gap-2">
                    Sign In
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
                <span x-show="submitting" style="display: none;" class="relative z-10 flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Authenticating...
                </span>
                <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
            </button>
        </form>

        <div class="mt-8 text-center text-sm font-semibold text-slate-500">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-sky-500 hover:text-sky-600 transition-colors ml-1">Sign up for free</a>
        </div>
    </div>
</x-guest-layout>
