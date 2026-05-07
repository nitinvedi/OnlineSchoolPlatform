<section>
    <header>
        <h2 class="text-lg font-medium text-slate-900">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="avatar_file" :value="__('Profile Picture')" />
            <div class="flex items-center gap-4 mt-2">
                @if($user->avatar_url)
                    <img src="{{ asset('storage/' . $user->avatar_url) }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover">
                @else
                    <div class="w-12 h-12 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
                <input type="file" id="avatar_file" name="avatar_file" accept="image/*" class="text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-600 file:text-white hover:file:bg-violet-500 cursor-pointer">
            </div>
            <x-input-error class="mt-2" :messages="$errors->get('avatar_file')" />
        </div>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-600">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-violet-400 hover:text-violet-300 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="headline" :value="__('Headline')" />
            <x-text-input id="headline" name="headline" type="text" class="mt-1 block w-full" :value="old('headline', $user->headline)" placeholder="e.g. Senior Software Engineer" />
            <x-input-error class="mt-2" :messages="$errors->get('headline')" />
        </div>

        <div>
            <x-input-label for="bio" :value="__('Bio')" />
            <textarea id="bio" name="bio" rows="4" class="mt-1 block w-full border-slate-200 focus:border-violet-500 focus:ring-violet-500 rounded-md shadow-sm px-4 py-3 text-slate-900 bg-white placeholder-slate-400">{{ old('bio', $user->bio) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('bio')" />
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <x-input-label for="social_twitter" :value="__('Twitter Profile URL')" />
                <x-text-input id="social_twitter" name="social_links[twitter]" type="url" class="mt-1 block w-full" :value="old('social_links.twitter', $user->social_links['twitter'] ?? '')" />
            </div>
            <div>
                <x-input-label for="social_linkedin" :value="__('LinkedIn Profile URL')" />
                <x-text-input id="social_linkedin" name="social_links[linkedin]" type="url" class="mt-1 block w-full" :value="old('social_links.linkedin', $user->social_links['linkedin'] ?? '')" />
            </div>
            <div>
                <x-input-label for="social_github" :value="__('GitHub Profile URL')" />
                <x-text-input id="social_github" name="social_links[github]" type="url" class="mt-1 block w-full" :value="old('social_links.github', $user->social_links['github'] ?? '')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-emerald-600"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
