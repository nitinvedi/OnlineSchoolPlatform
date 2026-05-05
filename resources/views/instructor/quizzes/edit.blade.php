<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('instructor.lessons.edit', [$course, $lesson]) }}"
                   class="text-gray-400 hover:text-gray-600 transition">← Back to Lesson</a>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Quiz Builder — <span class="text-purple-600">{{ $lesson->title }}</span>
                </h2>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
                    <span class="text-xl">✅</span> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <p class="font-semibold text-red-700 mb-2">Please fix the errors below:</p>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Quiz Settings + Question Builder --}}
            <form action="{{ route('instructor.quizzes.store', [$course, $lesson]) }}" method="POST">
                @csrf

                {{-- Settings Card --}}
                <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
                    <h3 class="font-bold text-gray-800 mb-4 text-lg">⚙️ Quiz Settings</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Quiz Title *</label>
                            <input type="text" name="title" required
                                   value="{{ old('title', $quiz?->title) }}"
                                   placeholder="e.g. Week 1 Assessment"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Pass Percentage *</label>
                            <input type="number" name="pass_percentage" min="1" max="100" required
                                   value="{{ old('pass_percentage', $quiz?->pass_percentage ?? 70) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1.5">Max Attempts <span class="font-normal text-gray-400">(0 = unlimited)</span></label>
                            <input type="number" name="max_attempts" min="0" required
                                   value="{{ old('max_attempts', $quiz?->max_attempts ?? 3) }}"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description <span class="font-normal text-gray-400">(optional)</span></label>
                        <input type="text" name="description"
                               value="{{ old('description', $quiz?->description) }}"
                               placeholder="Brief intro shown to students before the quiz"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    </div>
                </div>

                {{-- Questions --}}
                <div id="questions-container" class="space-y-4 mb-6">
                    @php
                        $existingQuestions = $quiz?->questions ?? collect();
                    @endphp

                    @forelse($existingQuestions as $qIdx => $question)
                        <div class="question-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-400">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="font-bold text-gray-700">Question <span class="q-num">{{ $qIdx + 1 }}</span></h4>
                                <button type="button" onclick="removeQuestion(this)"
                                        class="text-red-400 hover:text-red-600 text-sm font-medium">Remove</button>
                            </div>

                            <div class="grid grid-cols-4 gap-3 mb-4">
                                <div class="col-span-3">
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Question Text *</label>
                                    <input type="text" name="questions[{{ $qIdx }}][question]"
                                           value="{{ $question->question }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-600 mb-1">Points *</label>
                                    <input type="number" name="questions[{{ $qIdx }}][points]"
                                           value="{{ $question->points }}" min="1" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                                </div>
                            </div>

                            <div class="options-container space-y-2 mb-3">
                                @foreach($question->options as $oIdx => $option)
                                    <div class="flex items-center gap-2">
                                        <input type="radio" name="questions[{{ $qIdx }}][correct]"
                                               value="{{ $oIdx }}" {{ $option->is_correct ? 'checked' : '' }}
                                               required class="text-purple-600 flex-shrink-0">
                                        <input type="text" name="questions[{{ $qIdx }}][options][{{ $oIdx }}][text]"
                                               value="{{ $option->option_text }}" required
                                               placeholder="Option {{ $oIdx + 1 }}"
                                               class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                                        @if($oIdx >= 2)
                                            <button type="button" onclick="removeOption(this)"
                                                    class="text-red-400 hover:text-red-600 text-xs">✕</button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" onclick="addOption(this)"
                                    class="text-xs text-purple-600 hover:text-purple-800 font-medium">+ Add Option</button>
                        </div>
                    @empty
                        {{-- Will be empty until JS adds the first question --}}
                    @endforelse
                </div>

                <button type="button" id="add-question-btn"
                        onclick="addQuestion()"
                        class="w-full py-3 border-2 border-dashed border-purple-300 text-purple-600 font-semibold rounded-xl hover:border-purple-500 hover:bg-purple-50 transition mb-6">
                    + Add Question
                </button>

                <div class="flex items-center justify-end gap-4">
                    @if($quiz)
                        <button type="submit" form="delete-quiz-form"
                                class="px-4 py-2 text-sm text-red-500 hover:text-red-700 font-medium">
                            Delete Quiz
                        </button>
                    @endif
                    <button type="submit"
                            class="px-8 py-2.5 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition">
                        Save Quiz
                    </button>
                </div>
            </form>

            @if($quiz)
                <form id="delete-quiz-form" action="{{ route('instructor.quizzes.destroy', [$course, $lesson]) }}" method="POST" class="hidden">
                    @csrf @method('DELETE')
                </form>
            @endif

        </div>
    </div>

    <script>
    let questionCount = {{ $quiz?->questions->count() ?? 0 }};

    function addQuestion() {
        const idx = questionCount++;
        const container = document.getElementById('questions-container');

        const card = document.createElement('div');
        card.className = 'question-card bg-white rounded-xl shadow-sm p-6 border-l-4 border-purple-400';
        card.innerHTML = `
            <div class="flex items-center justify-between mb-4">
                <h4 class="font-bold text-gray-700">Question <span class="q-num">${idx + 1}</span></h4>
                <button type="button" onclick="removeQuestion(this)" class="text-red-400 hover:text-red-600 text-sm font-medium">Remove</button>
            </div>
            <div class="grid grid-cols-4 gap-3 mb-4">
                <div class="col-span-3">
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Question Text *</label>
                    <input type="text" name="questions[${idx}][question]" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400"
                           placeholder="Type your question here...">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1">Points *</label>
                    <input type="number" name="questions[${idx}][points]" value="1" min="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                </div>
            </div>
            <div class="options-container space-y-2 mb-3">
                ${[0,1,2,3].map(o => `
                <div class="flex items-center gap-2">
                    <input type="radio" name="questions[${idx}][correct]" value="${o}" ${o===0?'checked':''} required class="text-purple-600 flex-shrink-0">
                    <input type="text" name="questions[${idx}][options][${o}][text]" required
                           placeholder="Option ${o+1}"
                           class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                    ${o>=2 ? '<button type="button" onclick="removeOption(this)" class="text-red-400 hover:text-red-600 text-xs">✕</button>' : ''}
                </div>`).join('')}
            </div>
            <button type="button" onclick="addOption(this)" class="text-xs text-purple-600 hover:text-purple-800 font-medium">+ Add Option</button>
        `;
        container.appendChild(card);
        renumberQuestions();
    }

    function removeQuestion(btn) {
        btn.closest('.question-card').remove();
        renumberQuestions();
    }

    function addOption(btn) {
        const card = btn.closest('.question-card');
        const container = card.querySelector('.options-container');
        const qIdx = [...document.querySelectorAll('.question-card')].indexOf(card);
        const oIdx = container.querySelectorAll('.flex').length;

        const row = document.createElement('div');
        row.className = 'flex items-center gap-2';
        row.innerHTML = `
            <input type="radio" name="questions[${qIdx}][correct]" value="${oIdx}" class="text-purple-600 flex-shrink-0">
            <input type="text" name="questions[${qIdx}][options][${oIdx}][text]" required
                   placeholder="Option ${oIdx+1}"
                   class="flex-1 px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
            <button type="button" onclick="removeOption(this)" class="text-red-400 hover:text-red-600 text-xs">✕</button>
        `;
        container.appendChild(row);
    }

    function removeOption(btn) {
        btn.closest('.flex').remove();
    }

    function renumberQuestions() {
        document.querySelectorAll('.question-card').forEach((card, i) => {
            card.querySelector('.q-num').textContent = i + 1;
        });
    }

    // Auto-add a first question if none exist
    if (questionCount === 0) { addQuestion(); }
    </script>
</x-app-layout>
