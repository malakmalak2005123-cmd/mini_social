<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $user->name }}'s Profile
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- User Profile Header -->
            <div class="bg-card dark:bg-gray-800 shadow-sm rounded-xl overflow-hidden mb-8 border border-border dark:border-gray-700">
                <!-- Cover Image (Neutral) -->
                <div class="h-48 bg-gray-200 dark:bg-gray-700 w-full relative"></div>

                <!-- Profile Info -->
                <div class="px-6 pb-6">
                    <div class="relative flex flex-col md:flex-row items-center md:items-end -mt-16 md:-mt-12 mb-6 space-y-4 md:space-y-0 text-center md:text-left">
                        <!-- Avatar -->
                        <div class="relative flex-shrink-0">
                            <div class="h-32 w-32 rounded-full border-4 border-card dark:border-gray-800 bg-white dark:bg-gray-700 p-1 shadow-lg overflow-hidden">
                                @if($user->profile_photo_path)
                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="h-full w-full object-cover rounded-full">
                                @else
                                    <div class="h-full w-full rounded-full bg-gray-100 dark:bg-gray-600 flex items-center justify-center text-3xl font-bold text-gray-500 dark:text-gray-400">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- User Details -->
                        <div class="md:ml-6 flex-1 pt-16 md:pt-0">
                            <div class="flex flex-col md:flex-row justify-between items-center">
                                <div>
                                    <h1 class="text-2xl font-bold text-main dark:text-white">{{ $user->name }}</h1>
                                    <p class="text-muted dark:text-gray-400 text-sm">{{ $user->email }}</p>
                                </div>
                                
                                @if(auth()->id() === $user->id)
                                    <div class="mt-4 md:mt-0">
                                        <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-background hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 border border-border dark:border-gray-500 rounded-lg text-sm font-medium text-main dark:text-gray-200 transition-colors shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Edit Profile
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="border-t border-border dark:border-gray-700 pt-6 flex justify-center md:justify-start space-x-12">
                        <div class="text-center md:text-left">
                            <span class="block text-xl font-bold text-main dark:text-white">{{ $user->posts->count() }}</span>
                            <span class="text-sm text-muted dark:text-gray-400 uppercase tracking-wider">Posts</span>
                        </div>
                        <div class="text-center md:text-left">
                            <span class="block text-xl font-bold text-main dark:text-white">{{ $user->posts->sum(function($p){ return $p->likes->count(); }) }}</span>
                            <span class="text-sm text-muted dark:text-gray-400 uppercase tracking-wider">Likes</span>
                        </div>
                        <div class="text-center md:text-left">
                            <span class="block text-xl font-bold text-main dark:text-white">{{ $user->posts->sum(function($p){ return $p->comments->count(); }) }}</span>
                            <span class="text-sm text-muted dark:text-gray-400 uppercase tracking-wider">Comments</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Post Section (Only for Owner) -->
            @if(auth()->id() === $user->id)
                <div id="create-post" class="bg-card dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea name="content" rows="3" placeholder="What's on your mind?" class="w-full rounded-md border-border dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-primary focus:ring-primary shadow-sm"></textarea>
                        
                        <div class="mt-2 flex justify-between items-center">
                            <input type="file" name="image" class="block w-full text-sm text-transparent file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary/10 file:text-primary hover:file:bg-primary/20 dark:text-gray-400 dark:file:bg-gray-700 dark:file:text-indigo-300">
                            
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-primary dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:opacity-90 dark:hover:bg-white focus:bg-primary dark:focus:bg-white active:bg-primary dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
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
                    <div class="relative group bg-card dark:bg-gray-800 aspect-square border-border dark:border-gray-700 border flex items-center justify-center overflow-hidden hover:opacity-90 transition cursor-pointer">
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
                        <div class="relative group bg-card dark:bg-gray-800 aspect-square border-border dark:border-gray-700 border flex items-center justify-center overflow-hidden hover:opacity-90 transition cursor-pointer">
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