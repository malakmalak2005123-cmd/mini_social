<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>All Posts</h2>
<a href="{{ route('posts.create') }}">Create New Post</a>

@foreach($posts as $post)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <p>{{ $post->content }}</p>
        <small>By: {{ $post->user->name }}</small>
        <br>
        Likes: {{ $post->likes->count() }}
        <form action="{{ route('likes.store') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <button type="submit">
                @if($post->likes->contains('user_id', auth()->id()))
                    Unlike
                @else
                    Like
                @endif
            </button>
        </form>

        <h4>Comments:</h4>
        @foreach($post->comments as $comment)
            <p>{{ $comment->user->name }}: {{ $comment->content }}</p>
        @endforeach

        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <input type="hidden" name="post_id" value="{{ $post->id }}">
            <input type="text" name="content" placeholder="Add a comment">
            <button type="submit">Comment</button>
        </form>

        <a href="{{ route('posts.edit', $post->id) }}">Edit</a>
        <form action="{{ route('posts.destroy', $post->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
@endforeach

</body>
</html>