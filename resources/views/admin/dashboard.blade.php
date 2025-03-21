@extends('admin.layouts.app')

@section('main-content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Posts</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $postCount }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-blog fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Views</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{$totalViewCount}}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-eye fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Plan</div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    @if ($subscriptionStatus == 'active')
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">{{$subscriptionPlan->name}}</div>
                                    @else
                                        <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">No Subscriptions</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            @if ($subscriptionStatus == 'active')
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    ACTIVE
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card border-left-danger shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    INACTIVE
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-user fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Content Row (Tags, Categories, Pie Chart) -->
    <div class="row">
        <!-- Tags and Categories in one column -->
        <div class="col-xl-8 col-lg-8 col-md-12 mb-4">
            <div class="row">
                <div class="col-md-6">
                    <!-- Most Used Tags Card Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Most Used Tags</h6>
                        </div>
                        <div class="card-body">
                            @foreach($mostUsedTags as $tag)
                                <h4 class="small font-weight-bold">{{ $tag->name }}<span class="float-right">{{ $tag->count }}</span></h4>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Most Used Categories Card Example -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Most Used Categories</h6>
                        </div>
                        <div class="card-body">
                            @foreach($mostUsedCategories as $category)
                                <h4 class="small font-weight-bold">{{ $category->name }}<span class="float-right">{{ $category->count }}</span></h4>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pie Chart in a separate column -->
        @if($subscriptionStatus == 'active')
        <div class="col-xl-4 col-lg-4 col-md-12 mb-4">
            <!-- Pie Chart -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Article Generated</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="myPieChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="mr-2">
                            <i class="fas fa-circle text-primary"></i> Used
                        </span>
                        <span class="mr-2">
                            <i class="fas fa-circle text-success"></i> Remaining
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

@section('page-level-scripts')
    <!-- Page level plugins -->
    <script src="{{ asset('admin/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script>
        var articlesRemaining = {{ $articlesRemaining }};
        var totalArticles = {{$totalArticles->articles_per_month}};
        var articlesUsed = totalArticles - articlesRemaining;
    </script>
    <script src="{{ asset('admin/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('admin/js/demo/chart-pie-demo.js') }}"></script>
@endsection
