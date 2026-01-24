@extends('admin.layouts.app')

@section('page-level-styles')
<style>
    .pricing-card {
        border: 1px solid #e3e6f0;
        border-radius: 10px;
        background: #fff;
        height: 100%;
        display: flex;
        flex-direction: column;
        transition: all 0.2s ease-in-out;
    }

    .pricing-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.08);
    }

    .pricing-header {
        padding: 20px;
        border-bottom: 1px solid #f1f1f1;
        text-align: center;
    }

    .pricing-price {
        font-size: 34px;
        font-weight: 700;
        color: #4e73df;
    }

    .pricing-interval {
        font-size: 14px;
        color: #858796;
    }

    .pricing-body {
        padding: 20px;
        flex-grow: 1;
    }

    .pricing-body ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .pricing-body ul li {
        padding: 10px 0;
        border-bottom: 1px solid #f1f1f1;
        font-size: 14px;
    }

    .pricing-footer {
        padding: 20px;
        margin-top: auto;
        text-align: center;
    }

    .pricing-featured {
        border: 2px solid #4e73df;
    }
</style>
@endsection

@section('main-content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Choose Your Plan</h1>
    </div>

    <div class="row">
        @foreach ($plans as $plan)
            <div class="col-lg-3 col-md-6 mb-4 d-flex">
                <div class="pricing-card w-100">

                    <!-- Header -->
                    <div class="pricing-header">
                        <h5 class="text-uppercase text-muted mb-2">
                            {{ $plan->name }}
                        </h5>

                        <div class="pricing-price">
                            ${{ number_format($plan->price) }}
                        </div>

                        <div class="pricing-interval">
                            per {{ ucfirst($plan->interval) }}
                        </div>
                    </div>

                    <!-- Body -->
                    <div class="pricing-body">
                        <ul>
                            <li>
                                <strong>{{ number_format($plan->articles_per_month) }}</strong>
                                articles / month
                            </li>

                            <li>
                                AI Article Generation
                            </li>

                            <li>
                                Admin Dashboard Access
                            </li>

                            <li>
                                Email Support
                            </li>
                        </ul>
                    </div>

                    <!-- Footer -->
                    <div class="pricing-footer">
                        <a href="{{ route('subscription.checkout', $plan->id) }}"
                           class="btn btn-primary btn-sm w-100">
                            Subscribe Now
                        </a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endsection
