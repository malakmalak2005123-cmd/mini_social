<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Notifications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                @if($notifications->isEmpty())
                    <div class="p-6 text-gray-500 text-center">
                        No new notifications.
                    </div>
                @else
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($notifications as $notification)
                            <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center space-x-4">
                                <!-- Actor Avatar -->
                                <div class="flex-shrink-0">
                                     <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($notification->actor->name, 0, 1) }}
                                    </div>
                                </div>
                                
                                <!-- Message -->
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $notification->data['message'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $notification->created_at->diffForHumans() }}
                                    </p>
                                </div>

                                <!-- Action (Link) -->
                                @if(isset($notification->data['post_id']))
                                    <a href="{{ route('posts.show', $notification->data['post_id']) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">
                                        View
                                    </a>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
