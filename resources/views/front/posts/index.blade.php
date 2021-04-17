<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Posts</h1>
        @foreach ($posts as $post)
        <article>
            <h3><a href="{{ route('posts.show', [$post->id]) }}">{{ $post->title }}</a></h3>
            <p>Category: {{ $post->category->name }}</p>
            {{-- $category = Category::where('id', '=', $post->category_id)->first() --}}

            <p>{{ $post->content }}</p>
        </article>
        <hr>
        @endforeach
    </ul>
</body>
</html>