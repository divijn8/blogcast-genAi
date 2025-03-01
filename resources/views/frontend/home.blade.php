@extends('frontend.layouts.app')

@section('main-content')
    <div class="row">
        @foreach ($posts as $post)
        <div class="col-md-4 col-sm-6 col-xs-12 mb50">
            <h4 class="blog-title"><a href="{{ route('frontend.show', $post->slug) }}">{{$post->title}}</a></h4>
            <div class="blog-three-attrib">
                <span class="icon-calendar"></span>{{$post->created_at->format('F j, Y')}}|
                <span class=" icon-pencil"></span><a href="#">{{ $post->author->name }}</a>
            </div>
            <img src="{{ asset($post->thumbnail_path) }}" class="img-responsive" alt="image blog">
            <p class="mt25">
               {{ $post->excerpt}}
            </p>
            <a href="#" class="button button-gray button-xs">Read More <i class="fa fa-long-arrow-right"></i></a>

        </div>
        @endforeach
    </div>

    <!-- Blog Paging
    ===================================== -->
    {{ $posts->links('frontend.partials._pagination') }}
@endsection
