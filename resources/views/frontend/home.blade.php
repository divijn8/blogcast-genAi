@extends('frontend.layouts.app')

@section('main-content')
    <div class="container py-5">
        @if(request()->query('search'))
            <div class="row mb-4">
                <div class="col-md-12">
                    <h3 class="text-primary">Results for: <strong>{{ request()->query('search') }}</strong></h3>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-9 col-md-8 mt25">
                <div class="row">
                    @foreach ($posts as $post)
                        <div class="col-md-4 col-sm-6 mb-4 mt20">
                            <div class="card shadow-md h-100 d-flex flex-column" style="min-height: 420px;">
                                <!-- Image -->
                                <img src="{{ asset($post->thumbnail_path) }}" class="card-img-top" alt="Blog Image"
                                    style="object-fit: cover; height: 200px; width: 100%;">

                                <div class="card-body d-flex flex-column">
                                    <!-- Blog Title -->
                                    <h5 class="card-title" style="min-height: 50px;">
                                        <a href="{{ route('frontend.show', $post->slug) }}" class="text-decoration-none text-dark">
                                            {{$post->title}}
                                        </a>
                                    </h5>

                                    <!-- Date & Author -->
                                    <p class="text-muted small">
                                        <i class="fa fa-calendar"></i> {{ $post->created_at->format('F j, Y') }} |
                                        <i class="fa fa-user"></i> <a href="#" class="text-decoration-none">{{ $post->author->name }}</a>
                                    </p>

                                    <!-- Excerpt -->
                                    <p class="card-text" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-height: 40px;">
                                        {{ Str::limit($post->excerpt, 200) }}
                                    </p>

                                    <!-- Read More Button (aligned at bottom) -->
                                    <a href="{{ route('frontend.show', $post->slug) }}" class="btn btn-primary btn-sm mb-4">
                                        Read More <i class="fa fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-3 col-md-4">
            </div>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $posts->appends(request()->query())->links('frontend.partials._pagination') }}
        </div>
    </div>
@endsection
