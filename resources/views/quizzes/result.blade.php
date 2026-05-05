<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Result Header Card --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden mb-8 relative">
                
                {{-- Decorative Background --}}
                <div class="absolute inset-0 z-0 opacity-20">
                    <div class="absolute -top-24 -right-24 w-96 h-96 rounded-full blur-3xl {{ $attempt->passed ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>
                    <div class="absolute -bottom-24 -left-24 w-96 h-96 rounded-full blur-3xl {{ $attempt->passed ? 'bg-sky-400' : 'bg-amber-400' }}"></div>
                </div>

                <div class="relative z-10 p-8 md:p-12 text-center border-b border-slate-100">
                    
                    {{-- Score Ring --}}
                    <div class="relative w-48 h-48 mx-auto mb-8">
                        @php
                            $percentage = $attempt->percentage;
                            $circumference = 2 * pi() * 45;
                            $offset = $circumference - ($percentage / 100) * $circumference;
                            $strokeColor = $attempt->passed ? '#10b981' : '#f43f5e';
                        @endphp
                        <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="#f1f5f9" stroke-width="8"></circle>
                            <circle cx="50" cy="50" r="45" fill="none" stroke="{{ $strokeColor }}" stroke-width="8" stroke-dasharray="{{ $circumference }}" stroke-dashoffset="{{ $offset }}" class="transition-all duration-1000 ease-out delay-300"></circle>
                        </svg>
                        <div class="absolute inset-0 flex flex-col items-center justify-center">
                            <span class="text-4xl font-black text-slate-900">{{ $percentage }}%</span>
                            <span class="text-sm font-bold text-slate-400 mt-1">{{ $attempt->score }}/{{ $attempt->max_score }}</span>
                        </div>
                    </div>

                    <h1 class="text-4xl font-black text-slate-900 mb-4">{{ $attempt->passed ? 'Assessment Passed! 🎉' : 'Keep Trying! 💪' }}</h1>
                    <p class="text-lg text-slate-500 font-medium max-w-lg mx-auto">
                        @if($attempt->passed)
                            Excellent work! You've successfully demonstrated your understanding of the material in <span class="font-bold text-slate-700">"{{ $quiz->title }}"</span>.
                        @else
                            You didn't quite reach the passing mark of {{ $quiz->pass_percentage }}% for <span class="font-bold text-slate-700">"{{ $quiz->title }}"</span>. Review the answers below and try again.
                        @endif
                    </p>
                </div>

                {{-- Action Bar --}}
                <div class="bg-slate-50/50 p-6 flex flex-col sm:flex-row items-center justify-center gap-4 relative z-10">
                    <a href="{{ route('lessons.show', [$course, $lesson]) }}" class="w-full sm:w-auto px-8 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 transition shadow-sm text-center">
                        Back to Lesson
                    </a>
                    
                    @if(!$attempt->passed)
                        @php
                            $attemptsUsed = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', auth()->id())->count();
                            $canRetry = $quiz->max_attempts === 0 || $attemptsUsed < $quiz->max_attempts;
                        @endphp
                        @if($canRetry)
                            <a href="{{ route('quizzes.show', [$course, $lesson, $quiz]) }}" class="w-full sm:w-auto px-8 py-3 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition text-center flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Retake Assessment
                            </a>
                        @endif
                    @endif

                    @if($attempt->passed)
                        <a href="{{ route('courses.show', $course) }}" class="w-full sm:w-auto px-8 py-3 bg-emerald-500 text-white font-black rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-400 transition text-center flex items-center justify-center gap-2">
                            Continue Course
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>
                    @endif
                </div>
            </div>

            {{-- Review Answers Accordion --}}
            <div class="mb-8 flex items-center justify-between">
                <h2 class="text-2xl font-black text-slate-900">Review Answers</h2>
                <span class="text-sm font-bold text-slate-400">{{ $quiz->questions->count() }} Questions</span>
            </div>

            <div class="space-y-4" x-data="{ activeAccordion: null }">
                @foreach($quiz->questions as $qIndex => $question)
                    @php
                        $selectedId    = $attempt->answers[$question->id] ?? null;
                        $correctOption = $question->correctOption();
                        $isCorrect     = $correctOption && (int)$selectedId === $correctOption->id;
                    @endphp

                    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden transition-all duration-300" :class="activeAccordion === {{ $qIndex }} ? 'ring-2 ring-sky-500 ring-offset-2' : ''">
                        
                        {{-- Accordion Header --}}
                        <button @click="activeAccordion = activeAccordion === {{ $qIndex }} ? null : {{ $qIndex }}" class="w-full p-6 flex items-start gap-4 text-left hover:bg-slate-50 transition">
                            <div class="flex-shrink-0 mt-0.5">
                                @if($isCorrect)
                                    <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-500 flex items-center justify-center border border-emerald-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                @else
                                    <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center border border-rose-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-800 text-lg leading-tight mb-1">
                                    <span class="text-slate-400 mr-1">{{ $qIndex + 1 }}.</span>
                                    {!! strip_tags($question->question) !!}
                                </h3>
                            </div>
                            <div class="flex-shrink-0 text-slate-400" :class="activeAccordion === {{ $qIndex }} ? 'transform rotate-180 transition-transform' : 'transition-transform'">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>

                        {{-- Accordion Body --}}
                        <div x-show="activeAccordion === {{ $qIndex }}" x-collapse style="display: none;">
                            <div class="p-6 pt-0 pl-18 bg-slate-50 border-t border-slate-100">
                                <div class="space-y-3 mt-4 ml-12">
                                    @foreach($question->options as $option)
                                        @php
                                            $isSelected = (int)$selectedId === $option->id;
                                            $isRightAnswer = $option->is_correct;
                                            
                                            $bgClass = 'bg-white border-slate-200';
                                            $textClass = 'text-slate-600';
                                            $icon = '';

                                            if ($isRightAnswer) {
                                                $bgClass = 'bg-emerald-50 border-emerald-200';
                                                $textClass = 'text-emerald-700 font-bold';
                                                $icon = '<svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>';
                                            } elseif ($isSelected && !$isRightAnswer) {
                                                $bgClass = 'bg-rose-50 border-rose-200';
                                                $textClass = 'text-rose-700 font-bold';
                                                $icon = '<svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>';
                                            }
                                        @endphp
                                        <div class="flex items-center gap-3 p-4 rounded-xl border {{ $bgClass }}">
                                            <div class="w-6 flex justify-center">
                                                {!! $icon ?: '<div class="w-2 h-2 rounded-full bg-slate-300"></div>' !!}
                                            </div>
                                            <span class="{{ $textClass }} flex-1">{{ $option->option_text }}</span>
                                            @if($isSelected)
                                                <span class="text-xs font-bold uppercase tracking-wider px-2 py-1 rounded bg-black/5 {{ $isRightAnswer ? 'text-emerald-600' : 'text-rose-600' }}">Your Answer</span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        
                    </div>
                @endforeach
            </div>

        </div>
    </div>

    @if($attempt->passed)
        <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var duration = 3 * 1000;
                var animationEnd = Date.now() + duration;
                var defaults = { startVelocity: 30, spread: 360, ticks: 60, zIndex: 10000 };

                function randomInRange(min, max) { return Math.random() * (max - min) + min; }

                var interval = setInterval(function() {
                    var timeLeft = animationEnd - Date.now();
                    if (timeLeft <= 0) { return clearInterval(interval); }
                    var particleCount = 50 * (timeLeft / duration);
                    confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.1, 0.3), y: Math.random() - 0.2 } }));
                    confetti(Object.assign({}, defaults, { particleCount, origin: { x: randomInRange(0.7, 0.9), y: Math.random() - 0.2 } }));
                }, 250);
            });
        </script>
    @endif
</x-app-layout>
