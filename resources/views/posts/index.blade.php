<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Search Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-none border border-gray-200 dark:border-gray-700 rounded-lg p-6 mb-6">
                <form action="{{ route('posts.search') }}" method="GET" class="flex gap-2">
                    <input type="text" name="query" value="{{ request('query') }}" placeholder="Search posts..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                    <button type="submit" class="px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">Search</button>
                </form>
            </div>

            <!-- Posts Feed -->
            @foreach($posts as $post)
                <x-post-card :post="$post" />
            @endforeach
        </div>
    </div>
</x-app-layout>