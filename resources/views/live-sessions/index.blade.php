<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Live Classes') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-transparent rounded-lg shadow p-6">
                <h3 class="text-xl font-semibold mb-6 flex items-center gap-2 text-gray-900">
                    <svg class="w-4 h-4 text-red-600" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>
                    Upcoming & Live Classes
                </h3>

                @if($sessions->count() > 0)
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        @foreach($sessions as $session)
                            <div class="rounded-xl overflow-hidden hover:border-violet-500/30 transition bg-white border border-gray-100 flex flex-col justify-between">
                                <div class="p-5">
                                    <div class="flex justify-between items-start mb-3">
                                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full 
                                            {{ $session->isLive() ? 'bg-red-100 text-red-700 animate-pulse' : 'bg-violet-50 text-violet-700' }}">
                                            {{ $session->isLive() ? 'LIVE NOW' : 'Scheduled' }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-lg text-gray-900 mb-1 line-clamp-1">{{ $session->title }}</h4>
                                    <p class="text-sm text-gray-600 mb-2 line-clamp-1">Course: <a href="{{ route('courses.show', $session->course) }}" class="text-violet-600 hover:underline">{{ $session->course->title }}</a></p>
                                    @if(!auth()->user()->isInstructor())
                                        <div class="flex items-center gap-2 mb-4 text-xs text-gray-600">
                                            <div class="w-6 h-6 bg-violet-50 text-violet-700 flex items-center justify-center rounded-full font-bold text-sm">
                                                {{ substr($session->course->instructor->name, 0, 1) }}
                                            </div>
                                            <span>{{ $session->course->instructor->name }}</span>
                                        </div>
                                    @endif
                                    
                                    @if($session->description)
                                        <p class="text-sm text-slate-400 mb-4 line-clamp-2">{{ $session->description }}</p>
                                    @endif

                                    <div class="flex items-center gap-2 text-sm text-gray-600 font-medium mb-4">
                                        <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3M3 11h18M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $session->scheduled_at->format('D, M d, Y - h:i A') }}
                                    </div>
                                </div>
                                
                                <div class="p-5 bg-white border-t border-gray-100 mt-auto">
                                    @if($session->isLive())
                                        <a href="{{ route('live-sessions.show', $session) }}" class="block w-full text-center py-2.5 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-lg transition shadow-sm">
                                            Join Live Class
                                        </a>
                                    @else
                                        <button disabled class="w-full text-center py-2.5 bg-gray-100 text-gray-400 font-semibold rounded-lg cursor-not-allowed border border-gray-100">
                                            Waiting to Start
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-4xl mb-4">
                            <svg class="w-12 h-12 mx-auto text-gray-400" viewBox="0 0 24 24" fill="currentColor"><path d="M20 6H8l-2 2H4v10h16V6zM6 8V6h2v2H6zm10 4h-4v2h4v-2z"/></svg>
                        </div>
                            <p class="text-gray-600 text-lg">No upcoming live classes for your enrolled courses.</p>
                            <a href="{{ route('dashboard') }}" class="inline-block mt-4 text-violet-600 hover:underline">Return to Dashboard</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
