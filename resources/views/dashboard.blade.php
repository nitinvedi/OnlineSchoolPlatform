<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold">{{ __('Welcome back!') }}</h3>
                    <p class="mt-2 text-sm text-gray-600">{{ __('You are signed in as :role.', ['role' => auth()->user()->role]) }}</p>
                    <div class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="font-semibold">{{ __('Your next step') }}</h4>
                            <p class="mt-2 text-sm text-gray-500">{{ __('Browse courses, join live sessions, or build a new lesson depending on your role.') }}</p>
                        </div>
                        <div class="rounded-lg border border-gray-200 p-4">
                            <h4 class="font-semibold">{{ __('Profile') }}</h4>
                            <p class="mt-2 text-sm text-gray-500">{{ __('Keep your account details up-to-date from your profile settings.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
