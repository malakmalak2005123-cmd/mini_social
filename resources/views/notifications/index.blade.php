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
                            <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex items-center space-x-4 {{ is_null($notification->read_at) ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                                @if(is_null($notification->read_at))
                                    <span class="w-2 h-2 bg-blue-600 rounded-full flex-shrink-0" title="New Notification"></span>
                                @endif
                                <!-- Actor Avatar -->
                                <div class="flex-shrink-0">
                                     <div class="h-10 w-10 rounded-full bg-gray-200 overflow-hidden">
                                        @if($notification->actor->profile_photo_url)
                                            <img src="{{ $notification->actor->profile_photo_url }}" alt="{{ $notification->actor->name }}" class="h-full w-full object-cover">
                                        @else
                                            <div class="h-full w-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ substr($notification->actor->name, 0, 1) }}
                                            </div>
                                        @endif
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

                                <div class="ml-4">
                                     <form action="{{ route('notifications.destroy', $notification) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-400 hover:text-red-500 transition" title="Delete Notification">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                     </form>
                                 </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
