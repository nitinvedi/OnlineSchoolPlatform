<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .focus-theme { background-color: #f8fafc; min-height: 100vh; }
        .radio-card input[type="radio"] { display: none; }
        .radio-card label { display: block; padding: 1.5rem; border: 2px solid #e2e8f0; border-radius: 1rem; cursor: pointer; transition: all 0.2s ease; background: white; position: relative; overflow: hidden; }
        .radio-card input[type="radio"]:checked + label { border-color: #8b5cf6; background-color: #f5f3ff; box-shadow: 0 10px 15px -3px rgba(139, 92, 246, 0.1); transform: translateY(-2px); }
        .radio-card input[type="radio"]:checked + label::after { content: '✓'; position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); color: #8b5cf6; font-weight: bold; font-size: 1.25rem; }
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="focus-theme flex flex-col" x-data="{ 
        currentQuestion: 0, 
        totalQuestions: {{ $quiz->questions->count() }},
        answers: {},
        submitting: false,
        get progress() { return ((this.currentQuestion + 1) / this.totalQuestions) * 100; },
        next() { if(this.currentQuestion < this.totalQuestions - 1) this.currentQuestion++; },
        prev() { if(this.currentQuestion > 0) this.currentQuestion--; },
        handleKeydown(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                if (this.answers[this.currentQuestion]) {
                    if (this.currentQuestion < this.totalQuestions - 1) this.next();
                    else this.$refs.form.dispatchEvent(new Event('submit', { cancelable: true }));
                }
            }
        }
    }" @keydown.window="handleKeydown">

        {{-- Progress Header --}}
        <div class="bg-white border-b border-slate-200 sticky top-0 z-50">
            <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
                <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="text-slate-400 hover:text-slate-600 font-bold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    Exit
                </a>
                <div class="font-bold text-slate-800">
                    Question <span x-text="currentQuestion + 1"></span> of <span x-text="totalQuestions"></span>
                </div>
            </div>
            <div class="h-1.5 w-full bg-slate-100">
                <div class="h-full bg-purple-600 transition-all duration-300 ease-out" :style="`width: ${progress}%`"></div>
            </div>
        </div>

        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6">
            <div class="max-w-3xl mx-auto w-full">
                
                @if($maxAttemptsReached)
                    <div class="bg-white rounded-3xl p-10 text-center shadow-xl border border-rose-100">
                        <div class="w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <h2 class="text-3xl font-black text-slate-900 mb-4">Maximum Attempts Reached</h2>
                        <p class="text-lg text-slate-500 font-medium mb-8">You've used all {{ $quiz->max_attempts }} attempts for this quiz.</p>
                        <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="inline-flex items-center gap-2 px-8 py-4 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition">
                            Return to Lesson
                        </a>
                    </div>
                @else
                    <form x-ref="form" action="{{ route('quizzes.submit', [$course, $lesson, $quiz]) }}" method="POST" @submit="submitting = true">
                        @csrf

                        <div class="relative overflow-hidden min-h-[400px]">
                            @foreach($quiz->questions as $qIndex => $question)
                                <div x-show="currentQuestion === {{ $qIndex }}" 
                                     x-transition:enter="transition ease-out duration-300 delay-100" 
                                     x-transition:enter-start="opacity-0 translate-x-8" 
                                     x-transition:enter-end="opacity-100 translate-x-0" 
                                     x-transition:leave="transition ease-in duration-200 absolute inset-0" 
                                     x-transition:leave-start="opacity-100 translate-x-0" 
                                     x-transition:leave-end="opacity-0 -translate-x-8"
                                     style="display: none;" class="w-full">
                                    
                                    <h2 class="text-2xl sm:text-3xl font-black text-slate-900 mb-8 leading-tight">
                                        {!! $question->question !!}
                                    </h2>

                                    <div class="space-y-4">
                                        @foreach($question->options as $option)
                                            <div class="radio-card">
                                                <input type="radio" id="opt_{{ $option->id }}" name="answers[{{ $question->id }}]" value="{{ $option->id }}" x-model="answers[{{ $qIndex }}]" required>
                                                <label for="opt_{{ $option->id }}">
                                                    <div class="flex items-center gap-4">
                                                        <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold font-mono text-sm border border-slate-200">
                                                            {{ chr(65 + $loop->index) }}
                                                        </div>
                                                        <span class="text-lg font-medium text-slate-700">{{ $option->option_text }}</span>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach
                        </div>

                        {{-- Navigation Actions --}}
                        <div class="flex items-center justify-between mt-12 pt-8 border-t border-slate-200">
                            <button type="button" @click="prev()" :class="{'opacity-50 cursor-not-allowed': currentQuestion === 0}" class="flex items-center gap-2 px-6 py-3 font-bold text-slate-500 hover:text-slate-800 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                                Previous
                            </button>

                            <button type="button" x-show="currentQuestion < totalQuestions - 1" @click="next()" :disabled="!answers[currentQuestion]" :class="{'opacity-50 cursor-not-allowed': !answers[currentQuestion]}" class="flex items-center gap-2 px-8 py-4 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition">
                                Next
                                <kbd class="hidden sm:inline-block ml-2 px-2 py-1 bg-white/20 rounded text-xs">Enter ↵</kbd>
                            </button>

                            <button type="submit" x-show="currentQuestion === totalQuestions - 1" style="display:none;" :disabled="!answers[currentQuestion] || submitting" :class="{'opacity-50 cursor-not-allowed': !answers[currentQuestion] || submitting}" class="flex items-center gap-2 px-8 py-4 bg-purple-600 text-white font-black rounded-xl shadow-lg shadow-purple-600/30 hover:bg-purple-500 transition relative overflow-hidden group">
                                <span x-show="!submitting" class="relative z-10 flex items-center gap-2">Submit Assessment <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></span>
                                <span x-show="submitting" class="relative z-10 flex items-center gap-2">Processing...</span>
                                <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite]"></div>
                            </button>
                        </div>
                    </form>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
