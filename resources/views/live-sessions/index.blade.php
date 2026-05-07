<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight">
            {{ __('Live Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-transparent rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-6 flex items-center gap-2 text-slate-100">
                    <span class="text-red-500">🔴</span> Upcoming & Live Classes
                </h3>

                @if($sessions->count() > 0)
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($sessions as $session)
                            <div class="border border-white/5 rounded-xl overflow-hidden hover:border-violet-500/30 transition bg-transparent flex flex-col justify-between">
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full 
                                            {{ $session->isLive() ? 'bg-red-500/20 text-red-300 animate-pulse' : 'bg-violet-500/20 text-violet-300' }}">
                                            {{ $session->isLive() ? 'LIVE NOW' : 'Scheduled' }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-lg text-slate-100 mb-1 line-clamp-1">{{ $session->title }}</h4>
                                    <p class="text-sm text-slate-400 mb-2 line-clamp-1">Course: <a href="{{ route('courses.show', $session->course) }}" class="text-violet-400 hover:underline">{{ $session->course->title }}</a></p>
                                    @if(!auth()->user()->isInstructor())
                                        <div class="flex items-center gap-2 mb-4 text-xs text-slate-400">
                                            <div class="w-5 h-5 bg-violet-500/20 text-violet-300 flex items-center justify-center rounded-full font-bold">
                                                {{ substr($session->course->instructor->name, 0, 1) }}
                                            </div>
                                            <span>{{ $session->course->instructor->name }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($session->description)
                                        <p class="text-sm text-slate-400 mb-4 line-clamp-2">{{ $session->description }}</p>
                                    @endif

                                    <div class="flex items-center gap-2 text-sm text-slate-300 font-medium mb-4">
                                        <span>🗓️</span> {{ $session->scheduled_at->format('D, M d, Y - h:i A') }}
                                    </div>
                                </div>
                                
                                <div class="p-5 bg-white/5 border-t border-white/5 mt-auto">
                                    @if($session->isLive())
                                        <a href="{{ route('live-sessions.show', $session) }}" class="block w-full text-center py-2.5 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-lg transition shadow-sm">
                                            Join Live Class
                                        </a>
                                    @else
                                        <button disabled class="w-full text-center py-2.5 bg-slate-700 text-slate-400 font-semibold rounded-lg cursor-not-allowed">
                                            Waiting to Start
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-4xl mb-4">📭</div>
                        <p class="text-slate-400 text-lg">No upcoming live classes for your enrolled courses.</p>
                        <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-violet-400 hover:underline">Return to Dashboard</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
