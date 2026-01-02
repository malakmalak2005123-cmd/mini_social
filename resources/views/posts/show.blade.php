<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Post by {{ $post->user->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-xl text-gray-800 dark:text-gray-200">{{ $post->content }}</p>
                <div class="mt-4 text-sm text-gray-500">
                    Posted {{ $post->created_at->diffForHumans() }}
                </div>

                <!-- Interaction -->
                <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-4">
                    @auth
                     <form action="{{ route('likes.store') }}" method="POST" class="inline-block">
                        @csrf
                        <input type="hidden" name="post_id" value="{{ $post->id }}">
                        <button type="submit" class="flex items-center text-gray-600 hover:text-indigo-600">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1 {{ $post->likes->contains('user_id', auth()->id()) ? 'text-indigo-600 fill-current' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                             </svg>
                            {{ $post->likes->count() }} Likes
                        </button>
                    </form>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center text-gray-600 hover:text-indigo-600">
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                             </svg>
                            {{ $post->likes->count() }} Likes
                        </a>
                    @endauth
                </div>

                <!-- Comments Section -->
                <div class="mt-8">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Comments</h3>
                    
                    @foreach($post->comments as $comment)
                        <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-800 dark:text-gray-200">{{ $comment->user->name }}</span>
                                <span class="text-xs text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="mt-1 text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                            
                            @auth
                                @if(auth()->id() === $comment->user_id)
                                    <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="mt-2 text-right">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                @endif
                            @endauth
                        </div>
                    @endforeach

                    @auth
                        <form action="{{ route('comments.store') }}" method="POST" class="mt-6">
                            @csrf
                            <input type="hidden" name="post_id" value="{{ $post->id }}">
                            <textarea name="content" rows="2" placeholder="Write a comment..." class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                            <div class="mt-2 text-right">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                                    Comment
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="mt-6 p-4 bg-gray-100 dark:bg-gray-900 rounded text-center">
                            Please <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-500">log in</a> to leave a comment.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</x-app-layout>