<x-guest-layout>
    <div x-data="{ submitting: false, password: '', get strength() { 
        let val = 0; 
        if(this.password.length > 5) val += 1; 
        if(this.password.length > 7) val += 1; 
        if(/[A-Z]/.test(this.password)) val += 1; 
        if(/[0-9]/.test(this.password)) val += 1; 
        if(/[^A-Za-z0-9]/.test(this.password)) val += 1; 
        return Math.min(val, 4); 
    }}" class="w-full">
        
        <div class="mb-12">
            <h2 class="text-4xl font-display font-black text-white mb-4 tracking-tighter leading-tight">Join LiveSchool</h2>
            <p class="text-slate-500 font-medium text-lg">Start your journey with world-class education.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" @submit="submitting = true" class="space-y-6">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Full Name</label>
                <div class="relative group">
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus 
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-brand-500/50 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300"
                           placeholder="John Doe">
                    @if($errors->has('name'))
                        <p class="mt-2 text-xs font-bold text-rose-500 ml-1">{{ $errors->first('name') }}</p>
                    @endif
                </div>
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Email address</label>
                <div class="relative group">
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required 
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-brand-500/50 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300"
                           placeholder="name@company.com">
                    @if($errors->has('email'))
                        <p class="mt-2 text-xs font-bold text-rose-500 ml-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>
            </div>

            <!-- Role -->
            <div class="space-y-2">
                <label for="role" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">I want to</label>
                <div class="relative group">
                    <select id="role" name="role" required
                            class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white appearance-none focus:outline-none focus:border-brand-500/50 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300">
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }} class="bg-dark-bg text-white">Learn (Student)</option>
                        <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }} class="bg-dark-bg text-white">Teach (Instructor)</option>
                    </select>
                    <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Password</label>
                <div class="relative group">
                    <input id="password" type="password" name="password" required x-model="password"
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-brand-500/50 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300"
                           placeholder="••••••••">
                    @if($errors->has('password'))
                        <p class="mt-2 text-xs font-bold text-rose-500 ml-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>
                
                <!-- Strength Meter -->
                <div class="flex gap-1.5 h-1 px-1 mt-3" x-show="password.length > 0">
                    <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 1 ? 'bg-rose-500 shadow-[0_0_10px_rgba(244,63,94,0.5)]' : 'bg-white/5'"></div>
                    <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 2 ? 'bg-amber-500 shadow-[0_0_10px_rgba(245,158,11,0.5)]' : 'bg-white/5'"></div>
                    <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 3 ? 'bg-emerald-500 shadow-[0_0_10px_rgba(16,185,129,0.5)]' : 'bg-white/5'"></div>
                    <div class="flex-1 rounded-full transition-all duration-500" :class="strength >= 4 ? 'bg-brand-500 shadow-[0_0_10px_rgba(59,130,246,0.5)]' : 'bg-white/5'"></div>
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Confirm Password</label>
                <div class="relative group">
                    <input id="password_confirmation" type="password" name="password_confirmation" required 
                           class="w-full bg-white/5 border border-white/10 rounded-2xl px-5 py-4 text-white placeholder-slate-600 focus:outline-none focus:border-brand-500/50 focus:ring-4 focus:ring-brand-500/10 transition-all duration-300"
                           placeholder="••••••••">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary w-full py-4 text-sm relative group overflow-hidden mt-4">
                <span x-show="!submitting" class="relative z-10 flex items-center justify-center gap-2">
                    Create Account
                    <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
                <span x-show="submitting" style="display: none;" class="relative z-10 flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating account...
                </span>
            </button>
        </form>

        <div class="mt-12 pt-12 border-t border-white/5 text-center">
            <p class="text-sm font-bold text-slate-500">
                Already have an account? 
                <a href="{{ route('login') }}" class="text-brand-500 hover:text-brand-400 transition-colors ml-2">Sign in here</a>
            </p>
        </div>
    </div>
</x-guest-layout>
