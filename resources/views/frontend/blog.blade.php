@extends('frontend.layouts.app')

@php
    $postUrl = urlencode(route('frontend.show', $post->slug));
    $postTitle = urlencode($post->title);
@endphp


@section('main-content')
    <div class="blog-three-mini">
        <h2 class="color-dark">
            <a href="#">{{ $post->title }}</a>
        </h2>
        <div class="blog-three-attrib">
            <div><i class="fa fa-calendar"></i>{{$post->created_at->format('F j, Y')}}</div> |
            <div><i class="fa fa-pencil"></i>{{ $post->author->name }}</div> |
            <div><i class="fa fa-eye"></i>{{ $post->view_count }}</div> |
            <div><a href="#comment"><i class="fa fa-comment-o"></i>{{ $post->comments->count() }}</a></div> |
            <div>
                <div>
                    Share:
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ $postUrl }}"
                    target="_blank">
                        <i class="fa fa-facebook-official"></i>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url={{ $postUrl }}&text={{ $postTitle }}"
                    target="_blank">
                        <i class="fa fa-twitter"></i>
                    </a>

                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $postUrl }}"
                    target="_blank">
                        <i class="fa fa-linkedin"></i>
                    </a>

                    <a href="https://pinterest.com/pin/create/button/?url={{ $postUrl }}&description={{ $postTitle }}"
                    target="_blank">
                        <i class="fa fa-pinterest"></i>
                    </a>
                </div>

            </div>
        </div>

        <img src="{{ asset($post->thumbnail_path) }}" alt="Blog Image" class="img-responsive">
        {!! $post->body !!}
        <div class="blog-post-read-tag mt50">
            <i class="fa fa-tags"></i> Tags:
            @foreach($post->tags as $tag)
                <a href="{{ route('frontend.showByTag', $tag->name) }}"> {{$tag->name}}</a>,
            @endforeach
        </div>

    </div>
    <div class="mb50">
        <img src="{{ $post->author->user_profile }}" class="img-circle" alt="image">
        <span class="blog-post-author-name">{{ $post->author->name }}</span> <a href="https://twitter.com/booisme"><i class="fa fa-twitter"></i></a>
        <p>
            {{-- A sport enthu and a Cricket fan. --}}
        </p>
    </div>

        {{--  Comments --}}

        <div class="blog-post-comment-container">
            <h5 id="comment"><i class="fa fa-comments-o mb25"></i> {{ $post->comments->count() }} Comments</h5>

            {{-- Show top-level comments --}}
            @include('frontend.partials.comments', ['comments' => $post->comments->whereNull('parent_id')])

            <form action="{{ route('comments.store', $post->id) }}" method="POST">
                @csrf
                @guest
                    <div class="row" style="margin: 10px">
                        <input type="text" name="name" class="col-md-6 blog-leave-comment-input" placeholder="name" style="margin-left: -10px; margin-right: 10px">
                        <input type="email" name="email" class="col-md-6 blog-leave-comment-input" placeholder="email">
                    </div>
                @endguest
                <textarea name="content" class="form-control mb-2" rows="5"  placeholder="Write a comment..." class="blog-leave-comment-textarea"></textarea>

                <button type="submit" class="button button-pasific button-sm center-block mb25">Leave Comment</button>
            </form>
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
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.toggle-replies-link').forEach(link => {
                    link.addEventListener('click', function (e) {
                        e.preventDefault();
                        const target = document.querySelector(this.dataset.target);
                        if (target.style.display === 'none') {
                            target.style.display = 'block';
                            this.textContent = 'Hide replies';
                        } else {
                            target.style.display = 'none';
                            this.textContent = this.getAttribute('data-original');
                        }
                    });
                    link.setAttribute('data-original', link.textContent);
                });
            });

        </script>


@endsection
