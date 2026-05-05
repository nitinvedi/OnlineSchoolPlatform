<x-guest-layout>
    <div x-data="{ submitting: false }" class="w-full">
        
        <div class="mb-12">
            <h2 class="text-4xl font-display font-black text-white mb-4 tracking-tighter leading-tight">Welcome back</h2>
            <p class="text-slate-500 font-medium text-lg">Enter your credentials to access your workspace.</p>
        </div>

        <x-auth-session-status class="mb-6" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" @submit="submitting = true" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email address</label>
                <div class="relative group">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus 
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-brand-500/50 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300"
                           placeholder="name@company.com">
                    @if($errors->has('email'))
                        <p class="mt-2 text-xs font-bold text-rose-500 ml-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <div class="flex items-center justify-between px-1">
                    <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Password</label>
                    @if (Route::has('password.request'))
                        <a class="text-[10px] font-black text-brand-500 uppercase tracking-widest hover:text-brand-400 transition-colors" href="{{ route('password.request') }}">
                            Forgot?
                        </a>
                    @endif
                </div>
                <div class="relative group">
                    <input id="password" type="password" name="password" required 
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-brand-500/50 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300"
                           placeholder="••••••••">
                    @if($errors->has('password'))
                        <p class="mt-2 text-xs font-bold text-rose-500 ml-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>
            </div>

            <!-- Remember Me -->
            <div class="flex items-center px-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                    <div class="relative flex items-center justify-center w-5 h-5 rounded-lg border-2 border-white/10 bg-white/5 group-hover:border-brand-500/50 transition-colors mr-3">
                        <input id="remember_me" type="checkbox" class="peer opacity-0 absolute inset-0 cursor-pointer w-full h-full" name="remember">
                        <svg class="w-3.5 h-3.5 text-brand-500 opacity-0 peer-checked:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="4"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="text-xs font-bold text-slate-500 group-hover:text-slate-300 transition-colors">Keep me signed in</span>
                </label>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary w-full py-4 text-sm relative group overflow-hidden">
                <span x-show="!submitting" class="relative z-10 flex items-center justify-center gap-2">
                    Sign In to Workspace
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
                <span x-show="submitting" style="display: none;" class="relative z-10 flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Authenticating...
                </span>
            </button>
        </form>

        <div class="mt-12 pt-12 border-t border-white/5 text-center">
            <p class="text-sm font-bold text-slate-500">
                New to the platform? 
                <a href="{{ route('register') }}" class="text-brand-500 hover:text-brand-400 transition-colors ml-2">Create an account</a>
            </p>
        </div>
    </div>
</x-guest-layout>
