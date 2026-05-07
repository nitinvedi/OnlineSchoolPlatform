<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="min-h-screen bg-white">
        <div class="max-w-[1600px] mx-auto px-6 py-6">

            {{-- Header --}}
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 mb-8">

                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                        Good evening, {{ auth()->user()->name }}
                    </h1>

                    <p class="text-gray-600 mt-2 text-sm">
                        Here's what's happening with your courses today.
                    </p>
                </div>

                <div class="flex items-center gap-3">

                    <a href="{{ route('instructor.courses.create') }}" class="h-11 px-6 rounded-lg bg-violet-600 hover:bg-violet-700 transition text-gray-900 text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Create Course
                    </a>

                    <a href="{{ route('instructor.live-sessions.create') }}" class="h-11 px-6 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 transition text-gray-900 text-sm font-semibold flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Schedule Live
                    </a>

                </div>

            </div>

            {{-- Top Grid --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-6">

                {{-- Revenue Card --}}
                <div class="lg:col-span-8 bg-white border border-gray-200 rounded-2xl p-6">

                    <div class="flex items-center justify-between mb-8">

                        <div>
                            <h2 class="text-xl font-bold text-gray-900">
                                Revenue Overview
                            </h2>

                            <p class="text-sm text-gray-600 mt-1">
                                Revenue and student growth over time
                            </p>
                        </div>