<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Course Certificate
            </h2>
            <a href="{{ route('certificates.download', $certificate) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-lg font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                
                {{-- Preview Header --}}
                <div class="bg-gray-50 border-b p-4 text-center">
                    <p class="text-gray-500 text-sm">Certificate Preview for <strong>{{ $certificate->course->title }}</strong></p>
                </div>

                {{-- Actual Visual Certificate Wrapper --}}
                <div class="p-8 bg-gray-200 flex justify-center">
                    <div class="w-full max-w-4xl aspect-[1.414/1] bg-white shadow-2xl relative" style="padding: 2.5rem;">
                        <div class="w-full h-full border-[10px] border-purple-500 p-8">
                            <div class="w-full h-full border border-purple-200 flex flex-col items-center justify-center p-8 text-center relative">
                                
                                <h1 class="text-4xl md:text-5xl font-black text-purple-900 uppercase tracking-widest mb-6">Certificate of Completion</h1>
                                <p class="text-xl md:text-2xl text-gray-600 mb-8">This is to proudly certify that</p>
                                
                                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 border-b-2 border-gray-300 pb-2 mb-8 px-12 inline-block">
                                    {{ $certificate->user->name }}
                                </h2>
                                
                                <p class="text-xl text-gray-600 mb-6">has successfully completed the course</p>
                                
                                <h3 class="text-3xl font-bold text-purple-600 mb-16">
                                    "{{ $certificate->course->title }}"
                                </h3>

                                <div class="absolute bottom-8 w-full px-12 flex justify-between items-end">
                                    <div class="text-left">
                                        <p class="text-lg font-medium text-gray-800">Issued on: {{ $certificate->issued_at->format('F j, Y') }}</p>
                                    </div>
                                    <div class="text-center pb-2 w-48 border-t-2 border-purple-900">
                                        <p class="text-xl font-bold text-purple-900">{{ $certificate->course->instructor->name }}</p>
                                        <p class="text-sm text-gray-500">Instructor</p>
                                    </div>
                                </div>
                                
                                <p class="absolute bottom-4 right-4 text-xs text-gray-400 font-mono">
                                    ID: {{ $certificate->certificate_number }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
