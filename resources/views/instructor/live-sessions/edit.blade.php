<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('dashboard') }}"
               class="text-slate-400 hover:text-slate-300 transition">← Back to Dashboard</a>
            <h2 class="font-semibold text-xl text-slate-100 leading-tight">
                Edit Session — <span class="text-violet-400">{{ $liveSession->title }}</span>
            </h2>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/20 rounded-lg">
                    <p class="text-red-400 font-semibold mb-2">Please fix the following errors:</p>
                    <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-transparent rounded-xl shadow-sm p-8">
                <form id="update-session-form" action="{{ route('instructor.live-sessions.update', $liveSession) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    {{-- Title --}}
                    <div>
                        <label for="title" class="block text-sm font-semibold text-slate-300 mb-1.5">
                            Session Title <span class="text-red-400">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title', $liveSession->title) }}"
                               class="w-full px-4 py-2.5 border border-slate-700 bg-slate-800 text-slate-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('title') border-red-400 @enderror"
                               required>
                        @error('title') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Course Selection --}}
                    <div>
                        <label for="course_id" class="block text-sm font-semibold text-slate-300 mb-1.5">
                            Course <span class="text-red-400">*</span>
                        </label>
                        <select id="course_id" name="course_id"
                                class="w-full px-4 py-2.5 border border-slate-700 bg-slate-800 text-slate-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('course_id') border-red-400 @enderror"
                                required>
                            <option value="">— Select a course —</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $liveSession->course_id) == $course->id ? 'selected' : '' }}>
                                    {{ $course->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('course_id') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Description --}}
                    <div>
                        <label for="description" class="block text-sm font-semibold text-slate-300 mb-1.5">
                            Description
                        </label>
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-4 py-2.5 border border-slate-700 bg-slate-800 text-slate-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 resize-y @error('description') border-red-400 @enderror">{{ old('description', $liveSession->description) }}</textarea>
                        @error('description') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Scheduled Time --}}
                        <div>
                            <label for="scheduled_at" class="block text-sm font-semibold text-slate-300 mb-1.5">
                                Scheduled Date & Time <span class="text-red-400">*</span>
                            </label>
                            <input type="datetime-local" id="scheduled_at" name="scheduled_at" 
                                   value="{{ old('scheduled_at', $liveSession->scheduled_at->format('Y-m-d\TH:i')) }}"
                                   class="w-full px-4 py-2.5 border border-slate-700 bg-slate-800 text-slate-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('scheduled_at') border-red-400 @enderror"
                                   required>
                            @error('scheduled_at') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- Duration --}}
                        <div>
                            <label for="max_duration_minutes" class="block text-sm font-semibold text-slate-300 mb-1.5">
                                Estimated Duration (minutes) <span class="text-red-400">*</span>
                            </label>
                            <input type="number" id="max_duration_minutes" name="max_duration_minutes" 
                                   value="{{ old('max_duration_minutes', $liveSession->max_duration_minutes) }}" min="15" max="300"
                                   class="w-full px-4 py-2.5 border border-slate-700 bg-slate-800 text-slate-100 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-violet-500 focus:border-violet-500 @error('max_duration_minutes') border-red-400 @enderror"
                                   required>
                            @error('max_duration_minutes') <p class="text-red-400 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center justify-between pt-4 border-t border-slate-700">
                        <button type="submit" form="delete-session-form"
                                onclick="return confirm('Cancel and delete this live session?')"
                                class="px-4 py-2 text-sm text-red-400 hover:text-red-300 font-medium">
                            Cancel Session
                        </button>
                        
                        <div class="flex items-center gap-4">
                            <a href="{{ route('dashboard') }}"
                               class="px-5 py-2.5 text-sm text-slate-400 hover:text-slate-300 transition">Cancel</a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-violet-600 hover:bg-violet-500 text-white text-sm font-semibold rounded-lg transition">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
                
                {{-- Separate delete form --}}
                <form id="delete-session-form" action="{{ route('instructor.live-sessions.destroy', $liveSession) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
