@extends('frontend.layouts.app')

@section('main-content')
    <div class="blog-three-mini">
        <h2 class="color-dark">
            <a href="#">{{ $post->title }}</a>
        </h2>
        <div class="blog-three-attrib">
            <div><i class="fa fa-calendar"></i>{{$post->created_at->format('F j, Y')}}</div> |
            <div><i class="fa fa-pencil"></i><a href="#">Harry Boo</a></div> |
            <div><i class="fa fa-comment-o"></i><a href="#">90 Comments</a></div> |
            <div><a href="#"><i class="fa fa-thumbs-o-up"></i></a>150 Likes</div> |
            <div>
                Share:  <a href="#"><i class="fa fa-facebook-official"></i></a>
                <a href="#"><i class="fa fa-twitter"></i></a>
                <a href="#"><i class="fa fa-linkedin"></i></a>
                <a href="#"><i class="fa fa-google-plus"></i></a>
                <a href="#"><i class="fa fa-pinterest"></i></a>
            </div>
        </div>

        <img src="{{ asset($post->thumbnail_path) }}" alt="Blog Image" class="img-responsive">
        {!! $post->body !!}
        <div class="blog-post-read-tag mt50">
            <i class="fa fa-tags"></i> Tags:
            @foreach($post->tags as $tag)
                <a href="#"> {{$tag->name}}</a>,
            @endforeach
        </div>

    </div>
    <div class="blog-post-author mb50 pt30 bt-solid-1">
        <img src="{{ $post->author->user_profile }}" class="img-circle" alt="image">
        <span class="blog-post-author-name">{{ $post->author->name }}</span> <a href="https://twitter.com/booisme"><i class="fa fa-twitter"></i></a>
        <p>
            Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur.
        </p>
    </div>

        {{--  Comments --}}

        <div class="blog-post-comment-container">
            <h5><i class="fa fa-comments-o mb25"></i> {{ $post->comments->count() }} Comments</h5>

            {{-- Show top-level comments --}}
            @include('frontend.partials.comments', ['comments' => $post->comments->whereNull('parent_id')])



            {{-- Form to add a new comment --}}
            <form action="{{ route('comments.store') }}" method="POST" class="mt-4">
                @csrf
                <input type="hidden" name="post_id" value="{{ $post->id }}">
                <textarea name="content" class="form-control mb-2" rows="3" placeholder="Write a comment..." required></textarea>
                <button type="submit" class="btn btn-success">Post Comment</button>
            </form>

            <button class="button button-default button-sm center-block button-block mt25 mb25">Load More Comments</button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.reply-button').forEach(button => {
                    button.addEventListener('click', function (e) {
                        e.preventDefault();
                        const commentId = this.dataset.commentId;
                        const form = document.getElementById(`reply-form-${commentId}`);
                        form.style.display = form.style.display === 'none' ? 'block' : 'none';
                    });
                });
            });
        </script>

@endsection
