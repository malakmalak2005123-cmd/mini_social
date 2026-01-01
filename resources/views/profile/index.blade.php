<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('profile.search') }}" method="GET">
    <input type="text" name="keyword" placeholder="Search your posts..." value="{{ request('keyword') }}">
    <button type="submit">Search</button>
</form>

    <h2>{{ $user->name }}'s Profile</h2>
<p>Posts: {{ $user->posts->count() }}</p>
<p>Comments: {{ $user->comments->count() }}</p>
<p>Likes given: {{ $user->likes->count() }}</p>

<h3>Your Posts:</h3>
@foreach($posts as $post)
    <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
        <p>{{ $post->content }}</p>
        Likes: {{ $post->likes->count() }}
        <h4>Comments:</h4>
        @foreach($post->comments as $comment)
            <p>{{ $comment->user->name }}: {{ $comment->content }}</p>
        @endforeach
    </div>
@endforeach


<p>{{ $post->content }}</p>
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

</body>
</html>