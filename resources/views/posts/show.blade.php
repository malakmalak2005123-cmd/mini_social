<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Post Detail</h2>
<p>{{ $post->content }}</p>
<small>By: {{ $post->user->name }}</small>

Likes: {{ $post->likes->count() }}
<h4>Comments:</h4>
@foreach($post->comments as $comment)
    <p>{{ $comment->user->name }}: {{ $comment->content }}</p>
@endforeach

</body>
</html>