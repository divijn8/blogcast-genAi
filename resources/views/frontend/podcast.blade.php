@extends('frontend.layouts.app')

@section('main-content')
<style>
.podcast-card {
    background: #fff;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    display: flex;
    height: 100%;
    flex-direction: column;
}

.podcast-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.12);
}

.podcast-thumb {
    position: relative;
    height: 220px;
    overflow: hidden;
}

.podcast-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.podcast-duration {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(0,0,0,0.75);
    color: #fff;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 50px;
}

.podcast-body {
    padding: 20px;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.podcast-body > * {
    flex-shrink: 0;
}
.podcast-title {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 44px;
}

.podcast-title a {
    color: #212529;
    text-decoration: none;
}

.podcast-title a:hover {
    color: #0d6efd;
}

.podcast-meta {
    font-size: 12px;
    color: #6c757d;
    margin-top: 4px;
}

.podcast-desc {
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    overflow: hidden;
    min-height: 84px;
    max-height: 84px;
}

.podcast-body .btn {
    margin-top: auto;
}

</style>

<div class="container py-5">

    {{-- Header --}}
    <div class="text-center mb-5">
        @if(request()->query('search'))
            <p class="text-primary fw-semibold mt-3">
                Showing results for: "{{ request()->query('search') }}"
            </p>
        @endif
    </div>

    <div class="row">
        <div class="col-lg-9 col-md-8 mt25">
            <div class="row">

                @forelse ($podcasts as $podcast)
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-4">

                        <div class="podcast-card h-100">

                            <div class="podcast-thumb">
                                <img src="{{ asset($podcast->thumbnail_path) }}" alt="{{ $podcast->title }}">
                                <span class="podcast-duration">
                                    <i class="fa fa-clock me-1"></i>
                                    {{ gmdate("i:s", $podcast->duration) }}
                                </span>
                            </div>

                            <div class="podcast-body">

                                <h5 class="podcast-title">
                                    <a href="{{ route('frontend.podcasts.show', $podcast->slug) }}">
                                        {{ $podcast->title }}
                                    </a>
                                </h5>

                                <p class="podcast-meta">
                                    {{ $podcast->author->name }} |
                                    {{ $podcast->created_at->format('M d, Y') }}
                                </p>

                                <p class="podcast-desc">
                                    {{ Str::limit($podcast->description, 500) }}
                                </p>

                                <a href="{{ route('frontend.podcasts.show', $podcast->slug) }}"
                                class="btn">
                                    <i class="fa fa-play me-2"> Listen Now</i>
                                </a>

                            </div>

                        </div>

                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <i class="fa fa-microphone-slash fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No podcasts available yet.</p>
                    </div>
                @endforelse

            </div>
        </div>

        <div class="col-lg-3 col-md-4">
        </div>

    </div>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-5">
        {{ $podcasts->appends(request()->query())->links('frontend.partials._pagination') }}
    </div>

</div>
@endsection
