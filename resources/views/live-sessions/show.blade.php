<x-app-layout>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        .control-room { background-color: #020617; min-height: calc(100vh - 65px); color: #f8fafc; }
        .jitsi-container { box-shadow: 0 0 100px rgba(14, 165, 233, 0.1); }
        .mesh-lobby { background-color: #0f172a; background-image: radial-gradient(at 0% 0%, hsla(215,100%,20%,1) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(200,100%,15%,1) 0px, transparent 50%); }
    </style>

    <div class="control-room -mt-8 pt-8 flex flex-col" x-data="{
        reactions: [],
        addReaction(emoji) {
            const id = Date.now();
            const left = 10 + Math.random() * 80; // random position 10% to 90%
            this.reactions.push({ id, emoji, left });
            setTimeout(() => {
                this.reactions = this.reactions.filter(r => r.id !== id);
            }, 3000); // Remove after animation
        }
    }">
        
        {{-- Header Bar --}}
        <div class="px-6 py-4 bg-slate-900/80 backdrop-blur-md border-b border-white/5 flex items-center justify-between z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('courses.show', $course) }}" class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-slate-400 hover:bg-white/10 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div>
                    <h1 class="text-lg font-bold text-white">{{ $liveSession->title }}</h1>
                    <p class="text-sm font-medium text-slate-400">{{ $course->title }}</p>
                </div>
            </div>

            <div class="flex items-center gap-4">
                @if($liveSession->isLive())
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-rose-500/10 border border-rose-500/20 rounded-lg">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                        </span>
                        <span class="text-sm font-bold text-rose-500 uppercase tracking-widest">Live</span>
                    </div>
                    
                    {{-- Mock Participant Counter --}}
                    <div class="flex items-center gap-2 px-3 py-1.5 bg-white/5 border border-white/10 rounded-lg text-sm font-bold text-slate-300">
                        <svg class="w-4 h-4 text-sky-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path></svg>
                        <span>24</span>
                    </div>
                @else
                    <span class="px-3 py-1.5 text-sm font-bold uppercase tracking-widest rounded-lg bg-white/5 text-slate-400 border border-white/10">
                        {{ ucfirst($liveSession->status) }}
                    </span>
                @endif
                
                @if(auth()->id() === $liveSession->instructor_id || auth()->user()->isAdmin())
                    @if($liveSession->isLive())
                        <form action="{{ route('instructor.live-sessions.end', $liveSession) }}" method="POST">
                            @csrf
                            <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white text-sm font-bold rounded-lg transition shadow-lg shadow-rose-600/20">
                                End Broadcast
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>

        <div class="flex-1 relative flex">
            
            @if(!$liveSession->isLive() && !$liveSession->isEnded())
                {{-- LOBBY VIEW (Scheduled) --}}
                <div class="absolute inset-0 mesh-lobby flex flex-col items-center justify-center p-6 text-center z-20">
                    
                    <div class="relative w-32 h-32 mb-8" x-data="{ seconds: 0 }" x-init="setInterval(() => seconds++, 1000)">
                        <div class="absolute inset-0 bg-sky-500 rounded-full blur-3xl opacity-20"></div>
                        <div class="relative w-full h-full bg-slate-900/50 backdrop-blur-xl border border-white/10 rounded-full flex items-center justify-center shadow-2xl">
                            <svg class="w-12 h-12 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <svg class="absolute inset-0 w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="48" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="4"></circle>
                            <circle cx="50" cy="50" r="48" fill="none" stroke="#38bdf8" stroke-width="4" stroke-dasharray="301" :stroke-dashoffset="301 - ((seconds % 60) / 60) * 301" class="transition-all duration-1000 linear"></circle>
                        </svg>
                    </div>

                    <h2 class="text-4xl sm:text-5xl font-black text-white mb-4 tracking-tight">The broadcast will begin shortly</h2>
                    <p class="text-xl text-slate-400 font-medium mb-12 max-w-2xl">
                        Scheduled for <span class="text-sky-400">{{ $liveSession->scheduled_at->format('l, F j \a\t g:i A') }}</span>
                    </p>

                    @if(auth()->id() === $liveSession->instructor_id || auth()->user()->isAdmin())
                        <div class="bg-slate-900/50 backdrop-blur-md border border-white/10 p-8 rounded-2xl max-w-md w-full">
                            <h3 class="font-bold text-white mb-2">Host Controls</h3>
                            <p class="text-sm text-slate-400 mb-6">You are the instructor. You can start the broadcast whenever you're ready.</p>
                            <form action="{{ route('instructor.live-sessions.start', $liveSession) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full px-8 py-4 bg-sky-500 text-white font-black rounded-xl hover:bg-sky-400 transition shadow-lg shadow-sky-500/30 flex items-center justify-center gap-2 group">
                                    <svg class="w-6 h-6 transform group-hover:scale-110 transition" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4l12 6-12 6z"></path></svg>
                                    Go Live Now
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="px-6 py-4 bg-white/5 border border-white/10 backdrop-blur text-slate-300 rounded-xl font-medium flex items-center gap-3">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-sky-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Waiting for the host to start...
                        </div>
                    @endif
                </div>

            @elseif($liveSession->isEnded())
                {{-- ENDED VIEW --}}
                <div class="absolute inset-0 bg-slate-900 flex flex-col items-center justify-center p-6 text-center z-20">
                    <div class="w-24 h-24 bg-slate-800 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-3xl font-black text-white mb-2">Broadcast Ended</h2>
                    <p class="text-slate-400 font-medium mb-8">This live session has concluded. Thank you for joining.</p>
                    <a href="{{ route('courses.show', $course) }}" class="px-8 py-3 bg-white text-slate-900 font-bold rounded-xl hover:bg-slate-200 transition">
                        Return to Course
                    </a>
                </div>

            @else
                {{-- LIVE ROOM VIEW (Jitsi Embed) --}}
                <div class="flex-1 p-4 lg:p-6 pb-24 lg:pb-6 relative z-0 flex gap-6">
                    
                    {{-- Main Video Area --}}
                    <div class="flex-1 bg-black rounded-2xl overflow-hidden jitsi-container relative border border-white/5">
                        <div id="meet" class="w-full h-full"></div>
                        
                        {{-- Floating Reactions Container --}}
                        <div class="absolute bottom-20 left-0 right-0 h-64 pointer-events-none overflow-hidden z-50">
                            <template x-for="reaction in reactions" :key="reaction.id">
                                <div class="absolute bottom-0 text-4xl animate-[float_3s_ease-out_forwards]"
                                     :style="`left: ${reaction.left}%`"
                                     x-text="reaction.emoji"></div>
                            </template>
                        </div>
                    </div>

                    {{-- Host Spotlight / Info Sidebar (Hidden on mobile) --}}
                    <div class="hidden lg:flex w-80 flex-col gap-4">
                        <div class="bg-slate-900/80 backdrop-blur-md border border-white/5 rounded-2xl p-6">
                            <h3 class="font-bold text-white mb-4 text-sm uppercase tracking-widest text-slate-400">About Host</h3>
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-12 h-12 rounded-full overflow-hidden border border-white/10">
                                    <img src="{{ $course->instructor->avatar_url ? Storage::url($course->instructor->avatar_url) : 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name).'&background=0ea5e9&color=fff' }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-white">{{ $course->instructor->name }}</p>
                                    <p class="text-xs text-sky-400">Instructor</p>
                                </div>
                            </div>
                            <p class="text-sm text-slate-400 leading-relaxed line-clamp-3 mb-4">
                                {{ $course->instructor->bio ?? 'Lead instructor for this course. Bringing years of industry experience directly to you.' }}
                            </p>
                            <button class="w-full py-2 bg-white/5 hover:bg-white/10 border border-white/10 text-white text-sm font-bold rounded-lg transition">
                                View Profile
                            </button>
                        </div>

                        <div class="bg-slate-900/80 backdrop-blur-md border border-white/5 rounded-2xl p-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="font-bold text-white text-sm uppercase tracking-widest text-slate-400">Resources</h3>
                            </div>
                            <div class="flex-1 flex flex-col items-center justify-center text-center">
                                <svg class="w-10 h-10 text-slate-700 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                <p class="text-sm font-medium text-slate-500">No resources shared yet.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reaction Bar (Bottom floating) --}}
                <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 bg-slate-900/90 backdrop-blur-xl border border-white/10 p-2 rounded-2xl flex items-center gap-2 shadow-2xl z-40">
                    @foreach(['👍', '❤️', '👏', '🔥', '🎉'] as $emoji)
                        <button @click="addReaction('{{ $emoji }}')" class="w-12 h-12 flex items-center justify-center text-2xl hover:bg-white/10 rounded-xl transition hover:scale-110 active:scale-95">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>

                <style>
                    @keyframes float {
                        0% { transform: translateY(0) scale(1); opacity: 1; }
                        100% { transform: translateY(-200px) scale(1.5); opacity: 0; }
                    }
                </style>

                {{-- Jitsi Meet API SDK --}}
                <script src="https://meet.jit.si/external_api.js"></script>
                <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const domain = 'meet.jit.si';
                        const options = {
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
                                ENFORCE_NOTIFICATION_AUTO_DISMISS_TIMEOUT: 5000,
                                DEFAULT_BACKGROUND: '#000000',
                            }
                        };
                        const api = new JitsiMeetExternalAPI(domain, options);

                        api.addEventListener('videoConferenceLeft', () => {
                            window.location.href = "{{ route('dashboard') }}";
                        });
                    });
                </script>
            @endif
        </div>
    </div>
</x-app-layout>
