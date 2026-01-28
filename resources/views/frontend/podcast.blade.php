@extends('frontend.layouts.app')

@section('main-content')
    <div class="container py-5">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="text-primary"><i class="fa fa-podcast"></i> AI Podcasts</h2>
                <p class="text-muted">Listen to AI-generated conversations between experts.</p>
            </div>
            @if(request()->query('search'))
                <div class="col-md-12 mt-2">
                    <h3 class="text-primary">Results for: <strong>{{ request()->query('search') }}</strong></h3>
                </div>
            @endif
        </div>

        <div class="row">
            <div class="col-lg-9 col-md-8">
                <div class="row">
                    @forelse ($podcasts as $podcast)
                        <div class="col-md-6 col-sm-6 mb-4"> <div class="card shadow-sm h-100 d-flex flex-column" style="border-radius: 15px; overflow: hidden;">

                                <div class="position-relative">
                                    <img src="{{ asset($podcast->thumbnail) }}" class="card-img-top" alt="Podcast Image"
                                         style="object-fit: cover; height: 220px; width: 100%;">
                                    <span class="badge bg-dark position-absolute bottom-0 end-0 m-2">
                                        <i class="fa fa-clock"></i> {{ gmdate("i:s", $podcast->duration) }}
                                    </span>
                                    <span class="badge bg-primary position-absolute top-0 start-0 m-2">
                                        {{ $podcast->category->name }}
                                    </span>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">
                                        <a href="{{ route('frontend.podcasts.show', $podcast->slug) }}" class="text-decoration-none text-dark fw-bold">
                                            {{ $podcast->title }}
                                        </a>
                                    </h5>

                                    <p class="text-muted small mb-2">
                                        <i class="fa fa-calendar"></i> {{ $podcast->created_at->format('M d, Y') }} |
                                        <i class="fa fa-users"></i> AI Generated
                                    </p>

                                    <div class="audio-preview mb-3">
                                        <audio controls controlsList="nodownload" class="w-100" style="height: 30px;">
                                            <source src="{{ asset($podcast->audio_path) }}" type="audio/mpeg">
                                        </audio>
                                    </div>

                                    <p class="card-text small text-secondary flex-grow-1">
                                        {{ Str::limit($podcast->description, 120) }}
                                    </p>

                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <a href="{{ route('frontend.podcasts.show', $podcast->slug) }}" class="btn btn-outline-primary btn-sm rounded-pill px-3">
                                            View Transcript
                                        </a>
                                        <div class="stats small text-muted">
                                            <i class="fa fa-headphones"></i> {{ number_format($podcast->view_count) }} listens
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5">
                            <i class="fa fa-microphone-slash fa-3x text-muted mb-3"></i>
                            <p>No podcasts found. AI is recording some new episodes!</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-3 col-md-4 mt25">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="fw-bold mb-3">Trending Categories</h5>
                        <ul class="list-group list-group-flush">
                            @foreach($podcastCategories as $cat)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 border-0">
                                    <a href="?category={{ $cat->slug }}" class="text-decoration-none text-dark small">
                                        <i class="fa fa-tag text-primary me-2"></i> {{ $cat->name }}
                                    </a>
                                    <span class="badge bg-light text-dark rounded-pill">{{ $cat->podcasts_count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="card bg-primary text-white border-0 shadow-sm" style="border-radius: 15px;">
                    <div class="card-body text-center py-4">
                        <i class="fa fa-chart-line fa-2x mb-2"></i>
                        <h5>Total Listens</h5>
                        <h2 class="fw-bold">{{ number_format($totalListens) }}</h2>
                        <p class="small opacity-75 mb-0">Across all AI episodes</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $podcasts->appends(request()->query())->links('frontend.partials._pagination') }}
        </div>
    </div>
@endsection
