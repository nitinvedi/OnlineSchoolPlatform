<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-100 leading-tight dark:text-slate-100">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-[#111827]/80 dark:bg-[#111827]/80 backdrop-blur-md shadow sm:rounded-lg border border-white/10 dark:border-white/10">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-[#111827]/80 dark:bg-[#111827]/80 backdrop-blur-md shadow sm:rounded-lg border border-white/10 dark:border-white/10">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-[#111827]/80 dark:bg-[#111827]/80 backdrop-blur-md shadow sm:rounded-lg border border-white/10 dark:border-white/10">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
