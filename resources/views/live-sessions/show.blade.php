<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .session-page { min-height: calc(100vh - 65px); background: #F8FAFC; }
        @keyframes float-up {
            0%   { transform: translateY(0) scale(1);   opacity: 1; }
            100% { transform: translateY(-180px) scale(1.4); opacity: 0; }
        }
        .reaction-float { animation: float-up 3s ease-out forwards; }
        @keyframes pulse-ring {
            0%   { transform: scale(1);   opacity: 0.6; }
            100% { transform: scale(1.6); opacity: 0; }
        }
        .live-ring { animation: pulse-ring 1.4s ease-out infinite; }
    </style>

    <div class="session-page flex flex-col" x-data="{
        reactions: [],
        addReaction(emoji) {
            const id = Date.now();
            const left = 10 + Math.random() * 80;
            this.reactions.push({ id, emoji, left });
            setTimeout(() => this.reactions = this.reactions.filter(r => r.id !== id), 3200);
        }
    }">

        {{-- ── Top Bar ── --}}
        <div class="sticky top-0 z-30 px-5 py-3 bg-white border-b border-slate-200 flex items-center justify-between shadow-sm">
            <div class="flex items-center gap-3">
                <a href="{{ route('courses.show', $course) }}"
                   class="w-9 h-9 rounded-xl bg-slate-100 hover:bg-slate-200 flex items-center justify-center text-slate-500 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div class="min-w-0">
                    <h1 class="text-base font-bold text-slate-900 truncate leading-tight">{{ $liveSession->title }}</h1>
                    <p class="text-xs text-slate-500 font-medium truncate">{{ $course->title }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @if($liveSession->isLive())
                    {{-- Live badge --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-red-50 border border-red-200 rounded-lg">
                        <span class="relative flex h-2.5 w-2.5">
                            <span class="live-ring absolute inline-flex h-full w-full rounded-full bg-red-400"></span>
                            <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-red-500"></span>
                        </span>
                        <span class="text-xs font-black text-red-600 uppercase tracking-widest">Live</span>
                    </div>
                    {{-- Enrolled count (real — total students in course) --}}
                    <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 border border-slate-200 rounded-lg">
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        <span class="text-xs font-bold text-slate-600">{{ $course->enrollments()->count() }} enrolled</span>
                    </div>
                @elseif($liveSession->isEnded())
                    <span class="px-3 py-1.5 text-xs font-black uppercase tracking-widest rounded-lg bg-slate-100 text-slate-500 border border-slate-200">
                        Ended
                    </span>
                @else
                    <span class="px-3 py-1.5 text-xs font-black uppercase tracking-widest rounded-lg bg-blue-50 text-blue-600 border border-blue-100">
                        Scheduled
                    </span>
                @endif

                @if(auth()->id() === $liveSession->instructor_id || auth()->user()->isAdmin())
                    @if($liveSession->isLive())
                        <form action="{{ route('instructor.live-sessions.end', $liveSession) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('End this live session for everyone?')"
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-xs font-bold rounded-lg transition">
                                End Session
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        {{-- ── Content ── --}}
        <div class="flex-1 relative flex">

            @if(!$liveSession->isLive() && !$liveSession->isEnded())
            {{-- ════ LOBBY STATE ════ --}}
            <div class="flex-1 flex items-center justify-center p-6">
                <div class="w-full max-w-lg text-center">

                    {{-- Clock icon --}}
                    <div class="w-20 h-20 mx-auto mb-6 bg-white border border-slate-200 rounded-2xl shadow-sm flex items-center justify-center">
                        <svg class="w-10 h-10 text-[#2255FF]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>

                    <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2">Session Not Started Yet</h2>
                    <p class="text-slate-500 font-medium mb-1">
                        Scheduled for <span class="text-[#2255FF] font-bold">{{ $liveSession->scheduled_at->format('l, F j \a\t g:i A') }}</span>
                    </p>
                    <p class="text-sm text-slate-400 mb-8">Stay on this page — it will update when the session goes live.</p>

                    @if(auth()->id() === $liveSession->instructor_id || auth()->user()->isAdmin())
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm text-left mb-4">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Host Controls</p>
                            <p class="text-sm text-slate-600 mb-5">Ready to go? Start the session early — students will be notified.</p>
                            <form action="{{ route('instructor.live-sessions.start', $liveSession) }}" method="POST">
                                @csrf
                                <button type="submit"
                                        class="w-full py-3.5 bg-[#2255FF] hover:bg-[#1a44dd] text-white font-bold rounded-xl transition shadow shadow-blue-200 flex items-center justify-center gap-2">
                                    <span class="relative flex h-2.5 w-2.5">
                                        <span class="live-ring absolute inline-flex h-full w-full rounded-full bg-white opacity-60"></span>
                                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-white"></span>
                                    </span>
                                    Start Live Now
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="inline-flex items-center gap-3 px-6 py-4 bg-white border border-slate-200 rounded-2xl shadow-sm text-slate-600 font-medium text-sm">
                            <svg class="animate-spin w-5 h-5 text-[#2255FF]" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                            </svg>
                            Waiting for the instructor to start...
                        </div>
                    @endif

                    {{-- Enrolled students chips --}}
                    @php $enrolledStudents = $course->enrollments()->with('user:id,name')->get(); @endphp
                    @if($enrolledStudents->count() > 0)
                        <div class="mt-8 p-5 bg-white border border-slate-200 rounded-2xl shadow-sm text-left">
                            <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">
                                {{ $enrolledStudents->count() }} students will see this session
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($enrolledStudents->take(12) as $enrollment)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-50 border border-slate-200 rounded-full text-xs font-semibold text-slate-700">
                                        <span class="w-4 h-4 rounded-full bg-[#2255FF] text-white flex items-center justify-center text-[9px] font-black">
                                            {{ strtoupper(substr($enrollment->user->name, 0, 1)) }}
                                        </span>
                                        {{ $enrollment->user->name }}
                                    </span>
                                @endforeach
                                @if($enrolledStudents->count() > 12)
                                    <span class="px-3 py-1 bg-slate-100 border border-slate-200 rounded-full text-xs font-bold text-slate-500">
                                        +{{ $enrolledStudents->count() - 12 }} more
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            @elseif($liveSession->isEnded())
            {{-- ════ ENDED STATE ════ --}}
            <div class="flex-1 flex items-center justify-center p-6">
                <div class="w-full max-w-md text-center">
                    <div class="w-20 h-20 mx-auto mb-6 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-center">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black text-slate-900 mb-2">Session Ended</h2>
                    <p class="text-slate-500 font-medium mb-2">
                        This session ran for
                        @if($liveSession->started_at && $liveSession->ended_at)
                            <strong class="text-slate-700">{{ $liveSession->started_at->diffInMinutes($liveSession->ended_at) }} minutes</strong>
                        @else
                            a while
                        @endif
                    </p>
                    <p class="text-sm text-slate-400 mb-8">Thank you for joining {{ $course->title }}.</p>
                    <div class="flex items-center justify-center gap-3">
                        <a href="{{ route('courses.show', $course) }}"
                           class="px-6 py-3 bg-[#2255FF] text-white font-bold rounded-xl hover:bg-[#1a44dd] transition text-sm">
                            Back to Course
                        </a>
                        <a href="{{ route('dashboard') }}"
                           class="px-6 py-3 bg-slate-100 text-slate-700 font-bold rounded-xl hover:bg-slate-200 transition text-sm">
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>

            @else
            {{-- ════ LIVE ROOM STATE ════ --}}
            <div class="flex-1 flex gap-4 p-4 relative">

                {{-- Video Area --}}
                <div class="flex-1 bg-black rounded-2xl overflow-hidden relative border border-slate-800 shadow-xl" style="min-height: calc(100vh - 140px);">
                    <div id="meet" class="w-full h-full absolute inset-0"></div>

                    {{-- Floating reactions --}}
                    <div class="absolute bottom-20 left-0 right-0 h-64 pointer-events-none overflow-hidden z-50">
                        <template x-for="reaction in reactions" :key="reaction.id">
                            <div class="reaction-float absolute bottom-0 text-4xl select-none"
                                 :style="`left: ${reaction.left}%`"
                                 x-text="reaction.emoji"></div>
                        </template>
                    </div>

                    {{-- Reaction bar --}}
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/80 backdrop-blur-md border border-white/10 px-3 py-2 rounded-2xl flex items-center gap-1.5 shadow-xl z-40">
                        @foreach(['👍','❤️','👏','🔥','🎉','😮'] as $emoji)
                            <button @click="addReaction('{{ $emoji }}')"
                                    class="w-11 h-11 flex items-center justify-center text-2xl hover:bg-white/10 rounded-xl transition hover:scale-125 active:scale-95">
                                {{ $emoji }}
                            </button>
                        @endforeach
                    </div>
                </div>

                {{-- Sidebar --}}
                <div class="hidden lg:flex w-72 flex-col gap-4 flex-shrink-0">

                    {{-- Session Info --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Session Info</p>
                        <h3 class="font-bold text-slate-900 text-sm mb-1 leading-snug">{{ $liveSession->title }}</h3>
                        <p class="text-xs text-slate-500 mb-4">{{ $course->title }}</p>
                        @if($liveSession->description)
                            <p class="text-xs text-slate-500 leading-relaxed border-t border-slate-100 pt-3">{{ $liveSession->description }}</p>
                        @endif
                        <div class="mt-4 flex items-center gap-2 text-xs text-slate-500 font-medium">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Max {{ $liveSession->max_duration_minutes }} min
                        </div>
                    </div>

                    {{-- Instructor --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Instructor</p>
                        <div class="flex items-center gap-3">
                            <img src="{{ $course->instructor->avatar_url ? Storage::url($course->instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=2255FF&color=fff' }}"
                                 class="w-11 h-11 rounded-xl object-cover border border-slate-100"
                                 alt="{{ $course->instructor->name }}">
                            <div>
                                <p class="font-bold text-slate-900 text-sm">{{ $course->instructor->name }}</p>
                                <p class="text-xs text-[#2255FF] font-semibold">Instructor</p>
                            </div>
                        </div>
                        @if($course->instructor->bio)
                            <p class="text-xs text-slate-500 leading-relaxed mt-3 line-clamp-3">{{ $course->instructor->bio }}</p>
                        @endif
                    </div>

                    {{-- Enrolled (real count) --}}
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Audience</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-blue-50 border border-blue-100 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5 text-[#2255FF]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-slate-900">{{ $course->enrollments()->count() }}</p>
                                <p class="text-xs text-slate-500 font-medium">enrolled students</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- Jitsi SDK --}}
            <script src="https://meet.jit.si/external_api.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const api = new JitsiMeetExternalAPI('meet.jit.si', {
                        roomName: "{{ $liveSession->jitsi_room }}",
                        width: '100%',
                        height: '100%',
                        parentNode: document.querySelector('#meet'),
                        userInfo: {
                            email: "{{ auth()->user()->email }}",
                            displayName: "{{ auth()->user()->name }}"
                        },
                        configOverwrite: {
                            startWithAudioMuted: true,
                            startWithVideoMuted: true,
                            prejoinPageEnabled: false,
                            disableDeepLinking: true,
                            defaultLanguage: 'en',
                        },
                        interfaceConfigOverwrite: {
                            TOOLBAR_BUTTONS: [
                                'microphone', 'camera', 'desktop', 'fullscreen',
                                'fodeviceselection', 'chat', 'raisehand',
                                'videoquality', 'tileview', 'mute-everyone', 'security'
                            ],
                            SHOW_CHROME_EXTENSION_BANNER: false,
                            DEFAULT_BACKGROUND: '#000000',
                        }
                    });
                    api.addEventListener('videoConferenceLeft', () => {
                        window.location.href = "{{ route('dashboard') }}";
                    });
                });
            </script>
            @endif

        </div>
    </div>
</x-app-layout>
