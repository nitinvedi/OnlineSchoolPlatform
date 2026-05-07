<x-guest-layout>
    <div x-data="{ 
        submitting: false, 
        password: '', 
        showPassword: false,
        showPasswordConfirm: false,
        get strength() { 
            let val = 0; 
            if(this.password.length > 7) val += 1; 
            if(this.password.length > 10) val += 1; 
            if(/[A-Z]/.test(this.password)) val += 1; 
            if(/[0-9]/.test(this.password)) val += 1; 
            if(/[^A-Za-z0-9]/.test(this.password)) val += 1; 
            return Math.min(val, 4); 
        }
    }" class="w-full">
        
        {{-- ── Page title ─────────────────────────────── --}}
        <div class="mb-12">
            <h2 class="font-display text-[#F0EDE6] leading-none" style="font-size: clamp(2.8rem, 4vw, 4rem);">
                JOIN<br>LIVESCHOOL.
            </h2>
            <p class="font-mono text-[13px] text-[#555] mt-4">
                Start your journey with world-class education.
            </p>
        </div>

        {{-- ── Global errors ──────────────────────────── --}}
        @if ($errors->any())
            <div class="mb-6 border border-[#FF3B30] bg-[#FF3B30]/8 px-4 py-3 field-error-slide">
                <p class="font-mono text-[11px] uppercase tracking-[0.2em] text-[#FF3B30]">
                    Please fix the errors below
                </p>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" @submit="submitting = true" class="space-y-6">
            @csrf

            {{-- ── Name ───────────────────────────────── --}}
            <div>
                <label for="name" class="ls-label">Full Name</label>
                <input 
                    id="name" 
                    type="text" 
                    name="name" 
                    value="{{ old('name') }}" 
                    required 
                    autofocus
                    class="ls-input {{ $errors->has('name') ? 'error' : '' }}"
                    placeholder="John Doe"
                />
                @error('name')
                    <span class="ls-error field-error-slide">{{ $message }}</span>
                @enderror
            </div>

            {{-- ── Email ──────────────────────────────– --}}
            <div>
                <label for="email" class="ls-label">Email Address</label>
                <input 
                    id="email" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required
                    class="ls-input {{ $errors->has('email') ? 'error' : '' }}"
                    placeholder="name@company.com"
                />
                @error('email')
                    <span class="ls-error field-error-slide">{{ $message }}</span>
                @enderror
            </div>

            {{-- ── Role ───────────────────────────────– --}}
            <div>
                <label for="role" class="ls-label">I want to</label>
                <select 
                    id="role" 
                    name="role" 
                    required
                    class="ls-input {{ $errors->has('role') ? 'error' : '' }}"
                >
                    <option value="">Select a role...</option>
                    <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Learn (Student)</option>
                    <option value="instructor" {{ old('role') === 'instructor' ? 'selected' : '' }}>Teach (Instructor)</option>
                </select>
                @error('role')
                    <span class="ls-error field-error-slide">{{ $message }}</span>
                @enderror
            </div>

            {{-- ── Password ───────────────────────────– --}}
            <div>
                <label for="password" class="ls-label">Password</label>
                <div class="ls-input-wrapper has-icon">
                    <input 
                        id="password" 
                        :type="showPassword ? 'text' : 'password'" 
                        name="password" 
                        required 
                        x-model="password"
                        class="ls-input {{ $errors->has('password') ? 'error' : '' }}"
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
                
                {{-- Strength meter --}}
                <div x-show="password.length > 0" class="strength-bar-container mt-3">
                    <div class="strength-bar">
                        <div class="strength-seg" :class="strength >= 1 ? 'weak' : ''"></div>
                        <div class="strength-seg" :class="strength >= 2 ? 'fair' : ''"></div>
                        <div class="strength-seg" :class="strength >= 3 ? 'good' : ''"></div>
                        <div class="strength-seg" :class="strength >= 4 ? 'strong' : ''"></div>
                    </div>
                    <span class="strength-label"
                          :class="strength === 1 ? 'weak' : strength === 2 ? 'fair' : strength === 3 ? 'good' : strength === 4 ? 'strong' : ''"
                          x-text="strength === 1 ? 'WEAK' : strength === 2 ? 'FAIR' : strength === 3 ? 'GOOD' : strength === 4 ? 'STRONG' : ''">
                    </span>
                </div>
                
                @error('password')
                    <span class="ls-error field-error-slide">{{ $message }}</span>
                @enderror
            </div>

            {{-- ── Confirm Password ───────────────────– --}}
            <div>
                <label for="password_confirmation" class="ls-label">Confirm Password</label>
                <div class="ls-input-wrapper has-icon">
                    <input 
                        id="password_confirmation" 
                        :type="showPasswordConfirm ? 'text' : 'password'" 
                        name="password_confirmation" 
                        required
                        class="ls-input"
                        placeholder="••••••••"
                    />
                    {{-- Eye toggle --}}
                    <button type="button"
                            class="eye-btn"
                            @click="showPasswordConfirm = !showPasswordConfirm"
                            :aria-label="showPasswordConfirm ? 'Hide password' : 'Show password'">
                        {{-- Eye open --}}
                        <svg x-show="!showPasswordConfirm" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{-- Eye closed --}}
                        <svg x-show="showPasswordConfirm" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- ── Submit button ──────────────────────– --}}
            <button type="submit" 
                    class="ls-btn-primary"
                    :disabled="submitting"
                    :class="submitting ? 'opacity-50 cursor-not-allowed' : ''">
                <span x-show="!submitting">Create Account →</span>
                <span x-show="submitting" class="flex items-center justify-center gap-2">
                    <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    Creating...
                </span>
            </button>
        </form>

        {{-- ── Sign in link ────────────────────────────– --}}
        <div class="mt-10 pt-8 border-t border-[#1E1E1E]">
            <p class="font-mono text-[11px] uppercase tracking-[0.15em] text-[#555] text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="link-underline">
                    Sign in →
                </a>
            </p>
        </div>

        {{-- ── Terms note ──────────────────────────────– --}}
        <div class="mt-6 text-center">
            <p class="font-mono text-[10px] text-[#333] leading-relaxed">
                By creating an account, you agree to our
                <a href="#" class="link-underline">Terms of Service</a>
                and
                <a href="#" class="link-underline">Privacy Policy</a>
            </p>
        </div>
    </div>
</x-guest-layout>
