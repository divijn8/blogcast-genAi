@extends('admin.layouts.app')

@section('main-content')

<div class="container-fluid">

    {{-- 🔥 TOP: SUBSCRIPTION --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0" style="border-radius:14px;">

                <div class="card-body">

                    <div class="row align-items-center">

                        {{-- 🔥 LEFT: SUBSCRIPTION DETAILS --}}
                        <div class="col-md-7">

                            {{-- HEADER --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 font-weight-bold">Subscription</h5>

                                <span class="badge {{ $subscriptionStatus == 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ strtoupper($subscriptionStatus) }}
                                </span>
                            </div>

                            {{-- PLAN --}}
                            <div class="mb-3">
                                <h4 class="mb-1">
                                    {{ $subscriptionStatus == 'active' ? ($subscriptionPlan->name ?? 'Plan') : 'No Active Plan' }}
                                </h4>

                                @if($subscriptionStatus == 'active')
                                    <small class="text-muted">AI Content Generation Plan</small>
                                @endif
                            </div>

                            {{-- PROGRESS --}}
                            @if($subscriptionStatus == 'active')

                                @php
                                    $total = $totalArticles->articles_per_month ?? 3;
                                    $used = $total - $articlesRemaining;
                                    $percentage = ($total > 0) ? ($used / $total) * 100 : 0;
                                @endphp

                                <div class="mb-3">

                                    <div class="d-flex justify-content-between small mb-1">
                                        <span>Usage</span>
                                        <span>{{ $used }} / {{ $total }}</span>
                                    </div>

                                    <div class="progress" style="height:8px; border-radius:10px;">
                                        <div class="progress-bar
                                            {{ $percentage > 80 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success') }}"
                                            role="progressbar"
                                            style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Remaining: {{ $articlesRemaining }}</span>
                                    <span>{{ round($percentage) }}% used</span>
                                </div>

                            @else

                                <div class="mt-3">
                                    <p class="text-muted mb-2">Upgrade to start generating AI content</p>
                                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary btn-sm px-4">
                                        Upgrade Plan
                                    </a>
                                </div>

                            @endif

                        </div>

                        {{-- 🔥 RIGHT: PIE CHART --}}
                        <div class="col-md-5 text-center">

                            @if($subscriptionStatus == 'active')

                                <h6 class="mb-3">AI Usage</h6>

                                <div style="max-width: 200px; margin:auto;">
                                    <canvas id="aiUsageChart"></canvas>
                                </div>

                            @else

                                <div class="text-muted">
                                    <i class="fas fa-chart-pie fa-2x mb-2"></i>
                                    <p>No usage data</p>
                                </div>

                            @endif

                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 MAIN SPLIT --}}
    <div class="row">

        {{-- ================= BLOGS ================= --}}
        <div class="col-md-6">

            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    Blogs Section
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Blogs</span>
                        <strong>{{ $postCount }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Views</span>
                        <strong>{{ $totalViewCount }}</strong>
                    </div>

                    <hr>

                    {{-- TOP BLOGS --}}
                    <h6>Top Blogs</h6>
                    @foreach($topBlogs as $blog)
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ \Illuminate\Support\Str::limit($blog->title, 30) }}</span>
                            <span>{{ $blog->view_count }}</span>
                        </div>
                    @endforeach

                    <hr>

                    {{-- RECENT BLOGS --}}
                    <h6>Recent Blogs</h6>
                    @foreach($recentBlogs as $blog)
                        <div class="small">
                            {{ \Illuminate\Support\Str::limit($blog->title, 30) }}
                            <small class="text-muted">
                                • {{ $blog->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach

                    <hr>

                    {{-- REPORTS --}}
                    <div class="d-flex justify-content-between">
                        <span>Reports</span>
                        <strong class="text-danger">{{ $blogReports }}</strong>
                    </div>

                </div>
            </div>

        </div>

        {{-- ================= PODCASTS ================= --}}
        <div class="col-md-6">

            <div class="card shadow mb-4">
                <div class="card-header bg-info text-white">
                    Podcasts Section
                </div>

                <div class="card-body">

                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Podcasts</span>
                        <strong>{{ $podcastCount }}</strong>
                    </div>

                    <div class="d-flex justify-content-between mb-3">
                        <span>Total Views</span>
                        <strong>{{ $podcastViews }}</strong>
                    </div>

                    <hr>

                    {{-- TOP PODCASTS --}}
                    <h6>Top Podcasts</h6>
                    @foreach($topPodcasts as $podcast)
                        <div class="d-flex justify-content-between small mb-1">
                            <span>{{ \Illuminate\Support\Str::limit($podcast->title, 30) }}</span>
                            <span>{{ $podcast->view_count }}</span>
                        </div>
                    @endforeach

                    <hr>

                    {{-- RECENT PODCASTS --}}
                    <h6>Recent Podcasts</h6>
                    @foreach($recentPodcasts as $podcast)
                        <div class="small">
                            {{ \Illuminate\Support\Str::limit($podcast->title, 30) }}
                            <small class="text-muted">
                                • {{ $podcast->created_at->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach

                    <hr>

                    {{-- REPORTS --}}
                    <div class="d-flex justify-content-between">
                        <span>Reports</span>
                        <strong class="text-danger">{{ $podcastReports }}</strong>
                    </div>

                </div>
            </div>

        </div>

    </div>
    {{-- 🔥 TAGS + CATEGORIES (BOTTOM SECTION) --}}
    <div class="row mt-3">

        {{-- MOST USED TAGS --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-dark text-white">
                    Most Used Tags
                </div>

                <div class="card-body">

                    @forelse($mostUsedTags as $tag)
                        <div class="d-flex justify-content-between mb-2">
                            <span>#{{ $tag->name }}</span>
                            <strong>{{ $tag->count }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">No tags yet</p>
                    @endforelse

                </div>
            </div>
        </div>

        {{-- MOST USED CATEGORIES --}}
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-secondary text-white">
                    Most Used Categories
                </div>

                <div class="card-body">

                    @forelse($mostUsedCategories as $category)
                        <div class="d-flex justify-content-between mb-2">
                            <span>{{ $category->name }}</span>
                            <strong>{{ $category->count }}</strong>
                        </div>
                    @empty
                        <p class="text-muted">No categories yet</p>
                    @endforelse

                </div>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const used = {{ ($totalArticles->articles_per_month ?? 3) - $articlesRemaining }};
    const remaining = {{ $articlesRemaining }};

    const ctx = document.getElementById('aiUsageChart');

    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Used', 'Remaining'],
            datasets: [{
                data: [used, remaining],
                backgroundColor: ['#4e73df', '#1cc88a'],
                borderWidth: 0
            }]
        },
        options: {
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
</script>
@endsection
