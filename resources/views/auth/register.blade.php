<x-guest-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .input-group { position: relative; margin-bottom: 1.5rem; }
        .input-group input, .input-group select { width: 100%; padding: 1.25rem 1rem 0.5rem; border: 2px solid #e2e8f0; border-radius: 0.75rem; background: #f8fafc; outline: none; transition: all 0.3s ease; appearance: none; }
        .input-group input:focus, .input-group select:focus { border-color: #0ea5e9; background: #fff; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); }
        .input-group label { position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); color: #94a3b8; font-weight: 600; pointer-events: none; transition: all 0.3s ease; }
        .input-group input:focus + label, .input-group input:not(:placeholder-shown) + label,
        .input-group select:focus + label, .input-group select:not([value=""]) + label { top: 0.7rem; font-size: 0.75rem; color: #0ea5e9; }
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }
        @keyframes shake { 10%, 90% { transform: translate3d(-1px, 0, 0); } 20%, 80% { transform: translate3d(2px, 0, 0); } 30%, 50%, 70% { transform: translate3d(-4px, 0, 0); } 40%, 60% { transform: translate3d(4px, 0, 0); } }
        select { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e"); background-position: right 0.5rem center; background-repeat: no-repeat; background-size: 1.5em 1.5em; }
    </style>

    <div x-data="{ submitting: false, password: '', get strength() { 
        let val = 0; 
        if(this.password.length > 5) val += 1; 
        if(this.password.length > 7) val += 1; 
        if(/[A-Z]/.test(this.password)) val += 1; 
        if(/[0-9]/.test(this.password)) val += 1; 
        if(/[^A-Za-z0-9]/.test(this.password)) val += 1; 
        return Math.min(val, 4); 
    }}" class="w-full" x-init="$el.classList.add('animate-fade-in-up')">
        
        <div class="mb-8">
            <h2 class="text-3xl font-black text-slate-900 mb-2">Create an account</h2>
            <p class="text-slate-500 font-medium">Join LiveSchool and start your learning journey.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" @submit="submitting = true">
            @csrf

            <!-- Name -->
            <div class="input-group {{ $errors->has('name') ? 'shake' : '' }}">
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder=" " />
                <label for="name">Full Name</label>
                @if($errors->has('name'))
                    <p class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 text-sm text-rose-500 font-bold pointer-events-none">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <!-- Email Address -->
            <div class="input-group {{ $errors->has('email') ? 'shake' : '' }}">
                <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder=" " />
                <label for="email">Email address</label>
                @if($errors->has('email'))
                    <p class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 text-sm text-rose-500 font-bold pointer-events-none">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <!-- Role -->
            <div class="input-group {{ $errors->has('role') ? 'shake' : '' }}">
                <select id="role" name="role" required>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>Instructor</option>
                </select>
                <label for="role" style="top: 0.7rem; font-size: 0.75rem;">Account Type</label>
                @if($errors->has('role'))
                    <p class="absolute right-8 top-1/2 -translate-y-1/2 pr-3 text-sm text-rose-500 font-bold pointer-events-none">{{ $errors->first('role') }}</p>
                @endif
            </div>

            <!-- Password -->
            <div class="input-group {{ $errors->has('password') ? 'shake' : '' }} mb-2">
                <input id="password" type="password" name="password" required placeholder=" " x-model="password" />
                <label for="password">Password</label>
                @if($errors->has('password'))
                    <p class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 text-sm text-rose-500 font-bold pointer-events-none">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <!-- Password Strength Meter -->
            <div class="flex gap-1 h-1.5 mb-4" x-show="password.length > 0">
                <div class="flex-1 rounded-full transition-colors duration-300" :class="strength >= 1 ? 'bg-rose-500' : 'bg-slate-200'"></div>
                <div class="flex-1 rounded-full transition-colors duration-300" :class="strength >= 2 ? 'bg-amber-400' : 'bg-slate-200'"></div>
                <div class="flex-1 rounded-full transition-colors duration-300" :class="strength >= 3 ? 'bg-emerald-400' : 'bg-slate-200'"></div>
                <div class="flex-1 rounded-full transition-colors duration-300" :class="strength >= 4 ? 'bg-emerald-600' : 'bg-slate-200'"></div>
            </div>

            <!-- Confirm Password -->
            <div class="input-group {{ $errors->has('password_confirmation') ? 'shake' : '' }}">
                <input id="password_confirmation" type="password" name="password_confirmation" required placeholder=" " />
                <label for="password_confirmation">Confirm Password</label>
                @if($errors->has('password_confirmation'))
                    <p class="absolute right-0 top-1/2 -translate-y-1/2 pr-3 text-sm text-rose-500 font-bold pointer-events-none">{{ $errors->first('password_confirmation') }}</p>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full bg-slate-900 text-white font-black py-4 rounded-xl shadow-lg hover:bg-slate-800 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 relative overflow-hidden group mt-4">
                <span x-show="!submitting" class="relative z-10 flex items-center justify-center gap-2">
                    Create Account
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </span>
                <span x-show="submitting" style="display: none;" class="relative z-10 flex items-center justify-center">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Creating account...
                </span>
                <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
            </button>
        </form>

        <div class="mt-8 text-center text-sm font-semibold text-slate-500">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-sky-500 hover:text-sky-600 transition-colors ml-1">Sign in here</a>
        </div>
    </div>
</x-guest-layout>
