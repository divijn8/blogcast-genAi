<!DOCTYPE html>
<html lang="en">
<head>
    <title>Pen It</title>
</head>
<body>
    <p>Dear Pen It User,</p>
    <p>A new Blog has been added.Click below to check</p>
    <p>{{$post->title}}</p>
    <p>{{$post->excerpt}}</p>
    <a href="{{'$post->url'}}">Blog</a>

</body>
</html>
