<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-card dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <header>
                        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            {{ __('Profile Information') }}
                        </h2>

                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {{ __("Update your account's profile information and email address.") }}
                        </p>
                    </header>

                    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Name</label>
                            <input id="name" name="name" type="text" class="mt-1 block w-full rounded-md border-border dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary focus:ring-primary shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input id="email" name="email" type="email" class="mt-1 block w-full rounded-md border-border dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary focus:ring-primary shadow-sm" value="{{ old('email', $user->email) }}" required autocomplete="username" />
                            @error('email')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Profile Photo -->
                        <div>
                            <label for="photo" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Profile Photo</label>
                            
                            @if($user->profile_photo_path)
                                <div class="mt-2 mb-4">
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Current Photo" class="w-20 h-20 rounded-full object-cover">
                                </div>
                            @endif

                            <input id="photo" name="photo" type="file" class="mt-1 block w-full text-sm text-transparent border border-border rounded-lg cursor-pointer bg-background dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-l-lg file:border-0 file:text-sm file:font-semibold file:bg-primary file:text-white hover:file:bg-primary/90" />
                            @error('photo')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:opacity-90 dark:hover:bg-white focus:bg-primary dark:focus:bg-white active:bg-primary dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                {{ __('Save') }}
                            </button>

                            @if (session('status') === 'profile-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600 dark:text-gray-400"
                                >{{ __('Saved.') }}</p>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
