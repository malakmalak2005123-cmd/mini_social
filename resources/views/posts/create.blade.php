<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Create Post</h2>
<form action="{{ route('posts.store') }}" method="POST">
    @csrf
    <textarea name="content" placeholder="Write something..."></textarea>
    <button type="submit">Save</button>
</form>

</body>
</html>