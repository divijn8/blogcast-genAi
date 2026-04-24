@extends('admin.layouts.app')

@section('page-level-styles')
<style>
    /* Sleek UI Adjustments */
    .kpi-card {
        border-radius: 12px;
        transition: transform 0.2s;
        border: none;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
    }
    .insight-list-item {
        padding: 12px 0;
        border-bottom: 1px solid #f1f3f9;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .insight-list-item:last-child {
        border-bottom: none;
    }
    .content-title {
        font-weight: 600;
        color: #4a5568;
        font-size: 14px;
        margin-bottom: 2px;
    }
    .content-meta {
        font-size: 12px;
        color: #a0aec0;
    }
    .stat-badge {
        font-size: 12px;
        padding: 5px 10px;
        border-radius: 20px;
        font-weight: bold;
    }
    .tag-pill {
        font-size: 12px;
        padding: 6px 12px;
        border-radius: 6px;
        background: #edf2f7;
        color: #4a5568;
        display: inline-block;
        margin: 0 4px 8px 0;
        border: 1px solid #e2e8f0;
    }
    .category-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        background: #f8f9fc;
        border-radius: 8px;
        margin-bottom: 8px;
    }
</style>
@endsection

@section('main-content')
<div class="container-fluid pb-5">

    {{-- 🔥 TOP: SUBSCRIPTION (KEPT EXACTLY AS YOU HAD IT) --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow border-0" style="border-radius:14px;">
                <div class="card-body">
                    <div class="row {{ $subscriptionStatus != 'active' ? 'justify-content-center align-items-center' : 'align-items-center' }}"
                        style="{{ $subscriptionStatus != 'active' ? 'min-height:250px;' : '' }}">

                        {{-- LEFT: SUBSCRIPTION DETAILS --}}
                        <div class="{{ $subscriptionStatus == 'active' ? 'col-md-7' : 'col-md-12' }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 font-weight-bold">Subscription</h5>
                                <span class="badge {{ $subscriptionStatus == 'active' ? 'badge-success' : 'badge-danger' }}">
                                    {{ strtoupper($subscriptionStatus) }}
                                </span>
                            </div>
                            <div class="mb-3">
                                <h4 class="mb-1">
                                    {{ $subscriptionStatus == 'active' ? ($subscriptionPlan->name ?? 'Plan') : 'No Active Plan' }}
                                </h4>
                                @if($subscriptionStatus == 'active')
                                    <small class="text-muted">AI Content Generation Plan</small>
                                @endif
                            </div>

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
                                        <div class="progress-bar {{ $percentage > 80 ? 'bg-danger' : ($percentage > 50 ? 'bg-warning' : 'bg-success') }}"
                                            role="progressbar" style="width: {{ $percentage }}%">
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Remaining: {{ $articlesRemaining }}</span>
                                    <span>{{ round($percentage) }}% used</span>
                                </div>
                            @else
                                <div class="d-flex justify-content-center align-items-center h-100">
                                    <div class="p-3 text-center" style="background:#f8f9fc; border-radius:10px; max-width:320px; width:100%;">
                                        <i class="fas fa-lock fa-2x text-danger mb-2"></i>
                                        <h6 class="mb-1">No Active Subscription</h6>
                                        <p class="text-muted small mb-2">Unlock AI content generation by upgrading your plan</p>
                                        <a href="{{ route('subscriptions.index') }}" class="btn btn-danger btn-sm px-4">Upgrade Now</a>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- RIGHT: PIE CHART --}}
                        <div class="col-md-5 text-center">
                            @if($subscriptionStatus == 'active')
                                <h6 class="mb-3 font-weight-bold text-gray-800">AI Usage Breakdown</h6>
                                <div style="max-width: 180px; margin:auto;">
                                    <canvas id="aiUsageChart"></canvas>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 NEW ROW 1: QUICK KPI STATS --}}
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card kpi-card shadow h-100 py-2 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Blogs</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($postCount) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-file-alt fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card kpi-card shadow h-100 py-2 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Blog Views</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($totalViewCount) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-eye fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card kpi-card shadow h-100 py-2 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total Podcasts</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($podcastCount) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-podcast fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4 mb-xl-0">
            <div class="card kpi-card shadow h-100 py-2 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Podcast Plays</div>
                            <div class="h4 mb-0 font-weight-bold text-gray-800">{{ number_format($podcastViews) }}</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-headphones fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 NEW ROW 2: LEADERBOARD & INSIGHTS --}}
    <div class="row mb-4">
        {{-- TOP PERFORMING BLOGS --}}
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="card shadow border-0 h-100" style="border-radius:12px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fire text-danger mr-2"></i>Top Performing Blogs</h6>
                </div>
                <div class="card-body px-4">
                    @forelse($topBlogs as $blog)
                        <div class="insight-list-item">
                            <div>
                                <div class="content-title">{{ \Illuminate\Support\Str::limit($blog->title, 45) }}</div>
                                <div class="content-meta">Published {{ $blog->created_at->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-eye mr-1"></i>{{ number_format($blog->view_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center mt-3">No blogs generated yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- TOP PERFORMING PODCASTS --}}
        <div class="col-lg-6">
            <div class="card shadow border-0 h-100" style="border-radius:12px;">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-info"><i class="fas fa-headphones-alt text-info mr-2"></i>Top Performing Podcasts</h6>
                </div>
                <div class="card-body px-4">
                    @forelse($topPodcasts as $podcast)
                        <div class="insight-list-item">
                            <div>
                                <div class="content-title">{{ \Illuminate\Support\Str::limit($podcast->title, 45) }}</div>
                                <div class="content-meta">Published {{ $podcast->created_at->format('M d, Y') }}</div>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark border px-2 py-1"><i class="fas fa-play mr-1"></i>{{ number_format($podcast->view_count) }}</span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small text-center mt-3">No podcasts generated yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 NEW ROW 3: TAXONOMY & SYSTEM HEALTH --}}
    <div class="row">
        {{-- INTELLIGENT TAXONOMY (TAGS & CATEGORIES) --}}
        <div class="col-lg-8">
            <div class="card shadow border-0 h-100" style="border-radius:12px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-gray-800"><i class="fas fa-chart-pie mr-2 text-primary"></i>Audience Interest</h6>
                </div>
                <div class="card-body row">
                    <div class="col-md-6 border-right">
                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3">Popular Categories</h6>
                        @forelse($mostUsedCategories as $category)
                            <div class="category-row">
                                <span class="font-weight-bold text-gray-700 text-sm"><i class="fas fa-folder text-warning mr-2"></i>{{ $category->name }}</span>
                                <span class="badge badge-primary badge-pill">{{ $category->count }} Posts</span>
                            </div>
                        @empty
                            <p class="text-muted small">No category data.</p>
                        @endforelse
                    </div>
                    <div class="col-md-6 pl-md-4 mt-4 mt-md-0">
                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3">Trending Tags</h6>
                        <div>
                            @forelse($mostUsedTags as $tag)
                                <div class="tag-pill">
                                    <i class="fas fa-hashtag text-primary mr-1"></i>{{ $tag->name }}
                                    <span class="text-muted small ml-1">({{ $tag->count }})</span>
                                </div>
                            @empty
                                <p class="text-muted small">No tags used.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- CONTENT HEALTH / RECENT ALERTS --}}
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="card shadow border-0 h-100" style="border-radius:12px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-gray-800"><i class="fas fa-heartbeat mr-2 text-danger"></i>Content Health</h6>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-2">Reported Blogs</h6>
                        <div class="d-flex align-items-center">
                            <div class="h3 mb-0 mr-3 font-weight-bold {{ $blogReports > 0 ? 'text-danger' : 'text-success' }}">{{ $blogReports }}</div>
                            <div class="text-sm text-muted">User reports requiring your attention.</div>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-4">
                        <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-2">Reported Podcasts</h6>
                        <div class="d-flex align-items-center">
                            <div class="h3 mb-0 mr-3 font-weight-bold {{ $podcastReports > 0 ? 'text-danger' : 'text-success' }}">{{ $podcastReports }}</div>
                            <div class="text-sm text-muted">Audio flags requiring your attention.</div>
                        </div>
                    </div>

                    @if($blogReports > 0 || $podcastReports > 0)
                        <div class="alert alert-warning mt-4 small border-left-warning">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Review flagged content to maintain platform quality.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- 🔥 NEW ROW 4: GROWTH CHART & INFRASTRUCTURE --}}
    <div class="row mt-4">
        {{-- CONTENT GROWTH CHART (AREA CHART) --}}
        <div class="col-lg-8 mb-4">
            <div class="card shadow border-0 h-100" style="border-radius:12px;">
                <div class="card-header bg-white py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-chart-area mr-2"></i>Content Generation Trend (Last 6 Months)</h6>
                </div>
                <div class="card-body">
                    <div class="chart-area" style="height: 250px;">
                        <canvas id="growthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- INFRASTRUCTURE & ENGAGEMENT --}}
        <div class="col-lg-4 mb-4">
            <div class="card shadow border-0 h-100" style="border-radius:12px;">
                <div class="card-header bg-white py-3">
                    <h6 class="m-0 font-weight-bold text-gray-800"><i class="fas fa-server mr-2 text-info"></i>System & Engagement</h6>
                </div>
                <div class="card-body">

                    {{-- Storage Monitor --}}
                    <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-2">Audio Storage Usage</h6>
                    <div class="d-flex justify-content-between small mb-1">
                        <span class="font-weight-bold text-gray-800">{{ $estimatedStorageMB }} MB Used</span>
                        <span class="text-muted">{{ $storageLimitMB }} MB Limit</span>
                    </div>
                    <div class="progress mb-4" style="height: 8px; border-radius:10px;">
                        <div class="progress-bar {{ $storagePercentage > 80 ? 'bg-danger' : 'bg-info' }}"
                             role="progressbar"
                             style="width: {{ $storagePercentage }}%"></div>
                    </div>

                    <hr>

                    {{-- Engagement Stats --}}
                    <h6 class="text-xs font-weight-bold text-uppercase text-muted mb-3 mt-4">Average Engagement</h6>

                    <div class="d-flex align-items-center mb-3 p-3" style="background:#f8f9fc; border-radius:8px;">
                        <i class="fas fa-file-alt fa-2x text-primary mr-3"></i>
                        <div>
                            <div class="text-sm text-muted">Avg. Views per Blog</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avgBlogViews }} <span class="text-xs font-weight-normal text-muted">views/post</span></div>
                        </div>
                    </div>

                    <div class="d-flex align-items-center p-3" style="background:#f8f9fc; border-radius:8px;">
                        <i class="fas fa-podcast fa-2x text-warning mr-3"></i>
                        <div>
                            <div class="text-sm text-muted">Avg. Plays per Podcast</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $avgPodcastViews }} <span class="text-xs font-weight-normal text-muted">plays/ep</span></div>
                        </div>
                    </div>

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

    if(ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Used', 'Remaining'],
                datasets: [{
                    data: [used, remaining],
                    backgroundColor: ['#4e73df', '#1cc88a'],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                cutout: '75%',
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 11 } } }
                }
            }
        });
    }

    // Growth Chart Data from Laravel
    const months = {!! json_encode($months) !!};
    const blogData = {!! json_encode($blogGrowthData) !!};
    const podcastData = {!! json_encode($podcastGrowthData) !!};

    const growthCtx = document.getElementById('growthChart');
    if(growthCtx) {
        new Chart(growthCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [
                    {
                        label: 'Blogs Generated',
                        data: blogData,
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#4e73df',
                        fill: true,
                        tension: 0.4 // Smooth curves
                    },
                    {
                        label: 'Podcasts Generated',
                        data: podcastData,
                        borderColor: '#f6c23e',
                        backgroundColor: 'rgba(246, 194, 62, 0.1)',
                        borderWidth: 3,
                        pointRadius: 4,
                        pointBackgroundColor: '#f6c23e',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'top' }
                },
                scales: {
                    x: { grid: { display: false } },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    }
</script>
@endsection
