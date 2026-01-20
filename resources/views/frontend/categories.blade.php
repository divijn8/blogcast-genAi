@extends('frontend.layouts.app')

@section('main-content')
    <div class="row">
        <div class="col-md-12">
            <h2>All Posts of Category: <strong>{{ $category->name }}</strong></h2>
        </div>
    </div>
    @if($posts->count() === 0)
        <h4>No posts found!</h4>
    @endif
    <div class="row">
        @foreach($posts as $post)
            <div class="col-md-4 col-sm-6 col-xs-12 mb50">
                <h4 class="blog-title">
                    <a href="{{ route('frontend.show', $post->slug) }}">{{ $post->title }}</a>
                </h4>
                <div class="blog-three-attrib">
                    <span class="icon-calendar"></span> {{ \Carbon\Carbon::parse($post->published_at)->format('d M Y') }} |
                    <span class=" icon-pencil"></span><a href="#"> {{ $post->author->name }}</a>
                </div>
                <img src="{{ asset($post->thumbnail_path) }}" class="img-responsive" alt="image blog">
                <p class="mt25">
                    {{ $post->excerpt }}
                </p>
                <a href="{{ route('frontend.show', $post->slug) }}" class="button button-gray button-xs">Read More <i class="fa fa-long-arrow-right"></i></a>
            </div>
        @endforeach
    </div>

    {{ $posts->links('frontend.partials._pagination') }}

@endsection
