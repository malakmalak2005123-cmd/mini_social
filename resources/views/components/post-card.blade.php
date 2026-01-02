@props(['post', 'editable' => false])

<div class="bg-card dark:bg-gray-800 border bg-card dark:bg-gray-800 border-border dark:border-gray-700 rounded-lg mb-8">
    <!-- Header -->
    <div class="flex justify-between items-center p-4 border-b border-border dark:border-gray-700">
        <div class="flex items-center space-x-3">
             <div class="h-8 w-8 rounded-full bg-border overflow-hidden">
                <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->name }}" class="h-full w-full object-cover">
            </div>
            <div>
                <span class="font-bold text-primary dark:text-gray-200 text-sm">{{ $post->user->name }}</span>
                <span class="text-xs text-muted block">{{ $post->created_at->diffForHumans() }}</span>
            </div>
        </div>
        
        @if($editable && auth()->id() === $post->user_id)
            <div class="flex space-x-2">
                 <a href="{{ route('posts.edit', $post) }}" class="text-muted hover:text-gray-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </a>
                <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-muted hover:text-like">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                             <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </button>
                </form>
            </div>
        @endif
    </div>

    <!-- Content (Standard Image placeholder or just text) -->
    <!-- Content -->
    <div class="p-0">
        @if($post->image_path)
            <img src="{{ asset('storage/' . $post->image_path) }}" alt="Post Image" class="max-h-[500px] w-auto mx-auto object-contain mb-4 bg-black">
        @endif
        <div class="px-4 pb-4">
            <p class="text-main dark:text-gray-200 text-base leading-relaxed">{{ $post->content }}</p>
        </div>
    </div>

    <!-- Actions (Like/Comment) -->
    <div class="p-4 pt-0">
        <div class="flex items-center space-x-4 mb-2">
            <button onclick="toggleLike(this, {{ $post->id }}, {{ auth()->check() ? 'true' : 'false' }})" class="hover:opacity-75 transition group">
                 <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 {{ auth()->check() && $post->likes->contains('user_id', auth()->id()) ? 'text-like fill-current' : 'text-main dark:text-gray-200' }} transition duration-300 ease-in-out transform group-active:scale-125" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            </button>

            <a href="{{ route('posts.show', $post) }}" class="hover:opacity-75 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-main dark:text-gray-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
            </a>
            
             @auth
             <form action="{{ route('save.store') }}" method="POST" class="ml-auto">
                 @csrf
                 <input type="hidden" name="post_id" value="{{ $post->id }}">
                 <button type="submit" class="hover:opacity-75 transition">
                     <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 {{ auth()->user()->savedPosts->contains($post->id) ? 'text-main dark:text-white fill-current' : 'text-main dark:text-gray-200' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                     </svg>
                 </button>
             </form>
             @endauth
        </div>
        
        <!-- Stats -->
        <div class="space-y-1">
             <div class="font-bold text-sm text-main dark:text-gray-200">
                <span id="like-count-{{ $post->id }}">{{ $post->likes->count() }}</span> likes
            </div>
             @if($post->comments->count() > 0)
                <a href="{{ route('posts.show', $post) }}" class="text-muted text-sm">
                    View all {{ $post->comments->count() }} comments
                </a>
             @endif
        </div>
    </div>
</div>
<script>
    function toggleLike(button, postId, isAuthenticated) {
        if (!isAuthenticated) {
            window.location.href = "{{ route('login') }}";
            return;
        }

        fetch('{{ route('likes.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ post_id: postId })
        })
        .then(response => response.json())
        .then(data => {
            const svg = button.querySelector('svg');
            const countSpan = document.getElementById('like-count-' + postId);
            
            if (data.liked) {
                svg.classList.add('text-like', 'fill-current');
                svg.classList.remove('text-main', 'dark:text-gray-200');
            } else {
                svg.classList.remove('text-like', 'fill-current');
                svg.classList.add('text-main', 'dark:text-gray-200');
            }
            
            countSpan.textContent = data.count;
        })
        .catch(error => console.error('Error:', error));
    }
</script>
