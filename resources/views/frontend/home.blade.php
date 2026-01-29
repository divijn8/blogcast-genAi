@extends('frontend.layouts.app')

@section('main-content')
<style>
.blog-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    margin-bottom: 50px;
}

.blog-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
}

.blog-thumb {
    height: 200px;
    overflow: hidden;
}

.blog-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.blog-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.blog-title {
    font-size: 16px;
    font-weight: 700;
    line-height: 1.4;

    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 44px;
}

.blog-title a {
    color: #212529;
    text-decoration: none;
}

.blog-title a:hover {
    color: #0d6efd;
}

.blog-meta {
    font-size: 12px;
    color: #6c757d;
    margin-top: 6px;
}

.blog-excerpt {
    margin-top: 10px;
    font-size: 13px;
    color: #555;
    line-height: 1.5;

    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;

    min-height: 63px;
    max-height: 63px;
}

.blog-body .btn {
    margin-top: auto;
}

.btn-read {
    border: 1.5px solid #0d6efd;
    color: #0d6efd;
    background: transparent;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 14px;
    border-radius: 999px;
    transition: all 0.25s ease;
}

.btn-read:hover {
    background: #0d6efd;
    color: #fff;
    transform: translateY(-1px);
}
</style>

<div class="container py-5">

    @if(request()->query('search'))
        <div class="row mb-4">
            <div class="col-md-12">
                <h3 class="text-primary">
                    Results for: <strong>{{ request()->query('search') }}</strong>
                </h3>
            </div>
        </div>
    @endif

    <div class="row">

        <div class="col-lg-9 col-md-8 mt25">
            <div class="row">

                @foreach ($posts as $post)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <div class="blog-card">

                            <div class="blog-thumb">
                                <img src="{{ asset($post->thumbnail_path) }}" alt="{{ $post->title }}">
                            </div>

                            <div class="blog-body">

                                <h5 class="blog-title">
                                    <a href="{{ route('frontend.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h5>

                                <p class="blog-meta">
                                    <i class="fa fa-calendar me-1"></i>
                                    {{ $post->created_at->format('F j, Y') }}
                                    &nbsp;•&nbsp;
                                    <i class="fa fa-user me-1"></i>
                                    {{ $post->author->name }}
                                </p>

                                <p class="blog-excerpt">
                                    {{ $post->excerpt }}
                                </p>

                                <a href="{{ route('frontend.show', $post->slug) }}"
                                   class="btn btn-read w-100 mt-3">
                                    Read Article →
                                </a>

                            </div>

                        </div>

                    </div>
                @endforeach

            </div>
        </div>

        {{-- SIDEBAR --}}
        <div class="col-lg-3 col-md-4">
            {{-- sidebar handled globally --}}
        </div>

    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->appends(request()->query())->links('frontend.partials._pagination') }}
    </div>

</div>
@endsection
