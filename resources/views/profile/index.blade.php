<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- User Stats Header -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-none mb-8">
                <div class="flex flex-col md:flex-row items-center md:items-start md:space-x-12 p-4 md:p-8">
                    <!-- Profile Avatar -->
                    <div class="flex-shrink-0 mb-6 md:mb-0">
                        <div class="h-32 w-32 md:h-40 md:w-40 rounded-full bg-indigo-500 flex items-center justify-center text-white text-5xl font-bold border-4 border-white dark:border-gray-900 shadow-sm overflow-hidden">
                            @if($user->profile_photo_path)
                                <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            @else
                                {{ substr($user->name, 0, 1) }}
                            @endif
                        </div>
                    </div>

                    <!-- Info & Stats -->
                    <div class="flex-1 flex flex-col items-center md:items-start text-center md:text-left space-y-4">
                        <div class="flex flex-col md:flex-row items-center space-y-2 md:space-y-0 md:space-x-6">
                            <h2 class="text-2xl font-light text-gray-800 dark:text-gray-200">{{ $user->name }}</h2>
                            
                            @if(auth()->id() === $user->id)
                                <div class="flex space-x-2">
                                    <a href="{{ route('profile.edit') }}" class="px-4 py-1.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm font-semibold rounded border border-gray-300 dark:border-gray-600">Edit Profile</a>
                                    <a href="#" class="p-1.5 text-gray-800 dark:text-gray-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="flex space-x-8 text-base">
                            <div><span class="font-bold text-gray-900 dark:text-white">{{ $user->posts->count() }}</span> posts</div>
                            <div><span class="font-bold text-gray-900 dark:text-white">{{ $user->posts->sum(function($p){ return $p->likes->count(); }) }}</span> likes</div>
                            <div><span class="font-bold text-gray-900 dark:text-white">{{ $user->posts->sum(function($p){ return $p->comments->count(); }) }}</span> comments</div>
                        </div>
                        
                        <div class="pt-2">
                            <span class="font-bold text-gray-900 dark:text-white block">{{ $user->name }}</span>
                            <span class="text-gray-600 dark:text-gray-400">{{ $user->email }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Post Section (Only for Owner) -->
            @if(auth()->id() === $user->id)
                <div id="create-post" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea name="content" rows="3" placeholder="What's on your mind?" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm"></textarea>
                        
                        <div class="mt-2 flex justify-between items-center">
                            <input type="file" name="image" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:text-gray-400 dark:file:bg-gray-700 dark:file:text-indigo-300">
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Post
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- User's Posts Grid -->
            <div x-data="{ tab: 'posts' }">
                <div class="flex justify-center items-center space-x-12 mb-8 uppercase text-xs font-bold tracking-widest text-gray-500 border-t border-gray-200 dark:border-gray-700 pt-4">
                     <span class="cursor-pointer" :class="{ 'text-gray-900 dark:text-white border-t border-gray-900 dark:border-white pt-1 -mt-1': tab === 'posts', 'text-gray-500': tab !== 'posts' }" @click="tab = 'posts'">Posts</span>
                 <span class="cursor-pointer" :class="{ 'text-gray-900 dark:text-white border-t border-gray-900 dark:border-white pt-1 -mt-1': tab === 'saved', 'text-gray-500': tab !== 'saved' }" @click="tab = 'saved'">Saved</span>
            </div>

            <div x-show="tab === 'posts'" class="grid grid-cols-3 gap-4">
                 @foreach($user->posts as $post)
                    <div class="relative group bg-white dark:bg-gray-800 aspect-square border-gray-200 dark:border-gray-700 border flex items-center justify-center overflow-hidden hover:opacity-90 transition cursor-pointer">
                        @if($post->image_path)
                            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" class="h-full w-full object-cover">
                        @else
                            <div class="p-4 text-center">
                                <p class="text-sm text-gray-800 dark:text-gray-200 line-clamp-3">{{ $post->content }}</p>
                            </div>
                        @endif
                        <a href="{{ route('posts.show', $post) }}" class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                            <div class="flex space-x-6 text-white font-bold">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                    {{ $post->likes->count() }}
                                </div>
                                <div class="flex items-center">
                                     <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21a9 9 0 10-9-9c0 4.97 9 9 9 9z" opacity="0.5" /><path d="M12 11h-2v2h2v-2zm-2-2h2V7h-2v2zm4 4h-2v2h2v-2zm-2-2h2v-2h-2v2z" /></svg>
                                    {{ $post->comments->count() }}
                                </div>
                            </div>
                        </a>
                        @if(auth()->id() === $user->id)
                            <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition duration-200 z-10">
                                @csrf @method('DELETE')
                                <button type="submit" class="bg-white text-red-500 p-2 rounded-full hover:bg-red-50 focus:outline-none shadow-sm"><svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg></button>
                            </form>
                        @endif
                    </div>
                 @endforeach
            </div>

            <!-- Saved Posts Grid -->
             <div x-show="tab === 'saved'" class="grid grid-cols-3 gap-4" style="display: none;">
                 @if($user->savedPosts->count() > 0)
                     @foreach($user->savedPosts as $post)
                        <div class="relative group bg-white dark:bg-gray-800 aspect-square border-gray-200 dark:border-gray-700 border flex items-center justify-center overflow-hidden hover:opacity-90 transition cursor-pointer">
                            <div class="p-4 text-center">
                                <p class="text-sm text-gray-800 dark:text-gray-200 line-clamp-3">{{ $post->content }}</p>
                            </div>
                            <a href="{{ route('posts.show', $post) }}" class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-200">
                                <div class="flex space-x-6 text-white font-bold">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /></svg>
                                        {{ $post->likes->count() }}
                                    </div>
                                    <div class="flex items-center">
                                         <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21a9 9 0 10-9-9c0 4.97 9 9 9 9z" opacity="0.5" /><path d="M12 11h-2v2h2v-2zm-2-2h2V7h-2v2zm4 4h-2v2h2v-2zm-2-2h2v-2h-2v2z" /></svg>
                                        {{ $post->comments->count() }}
                                    </div>
                                </div>
                            </a>
                        </div>
                     @endforeach
                 @else
                    <div class="col-span-3 text-center py-8 text-gray-500">No saved posts yet.</div>
                 @endif
            </div>
        </div>
    </div>
</x-app-layout>