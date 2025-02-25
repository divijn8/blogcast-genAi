@extends('frontend.layouts.app')

@section('main-content')
    <div class="row">
        @foreach ($posts as $post)
        <div class="col-md-4 col-sm-6 col-xs-12 mb50">
            <h4 class="blog-title"><a href="#">{{$post->title}}</a></h4>
            <div class="blog-three-attrib">
                <span class="icon-calendar"></span>{{$post->created_at->format('F j, Y')}}|
                <span class=" icon-pencil"></span><a href="#">John Doe</a>
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
    <div class="row mt25 animated" data-animation="fadeInUp" data-animation-delay="100">
        <div class="col-md-6">
            <a href="#" class="button button-sm button-pasific pull-left hover-skew-backward">
                Old Entries
            </a>
        </div>
        <div class="col-md-6">
            <a href="#" class="button button-sm button-pasific pull-right hover-skew-forward">New Entries</a>
        </div>
    </div>

@endsection
