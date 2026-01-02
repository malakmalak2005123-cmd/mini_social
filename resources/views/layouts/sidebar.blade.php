<div class="hidden md:flex flex-col w-64 bg-primary dark:bg-gray-800 border-r border-border dark:border-gray-700 h-screen fixed left-0 top-0 z-50">
        <a href="{{ route('posts.index') }}" class="text-3xl font-bold text-main dark:text-gray-200" style="font-family: 'Grand Hotel', cursive;">
            Mini Social
        </a>

    <div class="flex-1 flex flex-col p-4 space-y-4">
        <x-nav-link :href="route('posts.index')" :active="request()->routeIs('posts.*')" class="flex items-center space-x-3 text-lg">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Home</span>
        </x-nav-link>

        <a href="{{ route('posts.search') }}" class="flex items-center space-x-3 text-main hover:text-white dark:text-gray-400 dark:hover:text-main px-1 pt-1 border-b-2 border-transparent text-lg font-medium leading-5 transition duration-150 ease-in-out">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                 <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span>Search</span>
        </a>

        @auth
            <a href="{{ route('notifications.index') }}" class="flex items-center space-x-3 text-main hover:text-white dark:text-gray-400 dark:hover:text-main px-1 pt-1 border-b-2 border-transparent text-lg font-medium leading-5 transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
                <span>Notifications</span>
                @if(auth()->user()->unreadNotifications()->count() > 0)
                    <span class="ml-2 bg-like text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                        {{ auth()->user()->unreadNotifications()->count() }}
                    </span>
                @endif
            </a>

            <a href="{{ route('profile.index') }}#create-post" class="flex items-center space-x-3 text-main hover:text-white dark:text-gray-400 dark:hover:text-main px-1 pt-1 border-b-2 border-transparent text-lg font-medium leading-5 transition duration-150 ease-in-out">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Create</span>
            </a>

            <x-nav-link :href="route('profile.index')" :active="request()->routeIs('profile.*')" class="flex items-center space-x-3 text-lg">
                 <div class="h-7 w-7 rounded-full bg-gray-300 overflow-hidden">
                     @if(Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" class="h-full w-full object-cover">
                     @else
                        <svg class="h-full w-full text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                     @endif
                 </div>
                <span>Profile</span>
            </x-nav-link>

             <form method="POST" action="{{ route('logout') }}" class="mt-auto">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="flex items-center space-x-3 text-main hover:text-white dark:text-gray-400 dark:hover:text-main px-1 pt-1 border-b-2 border-transparent text-lg font-medium leading-5 transition duration-150 ease-in-out">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Log Out</span>
                </a>
            </form>
        @endauth

        @guest
            <div class="mt-auto space-y-4">
                <a href="{{ route('login') }}" class="flex items-center space-x-3 text-muted hover:text-main dark:text-gray-400 dark:hover:text-gray-100 px-1 pt-1 border-b-2 border-transparent text-lg font-medium leading-5 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                    <span>Log In</span>
                </a>
                <a href="{{ route('register') }}" class="flex items-center space-x-3 text-muted hover:text-main dark:text-gray-400 dark:hover:text-gray-100 px-1 pt-1 border-b-2 border-transparent text-lg font-medium leading-5 transition duration-150 ease-in-out">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                    <span>Register</span>
                </a>
            </div>
        @endguest
    </div>
</div>
