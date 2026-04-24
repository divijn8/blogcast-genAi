@extends('admin.layouts.app')

@section('page-level-styles')
<style>
    /* Medium Sized SaaS Pricing Card Styles */
    .pricing-wrapper {
        padding: 1rem 0;
    }

    .pricing-card {
        background: #ffffff;
        border: 1px solid #e3e6f0;
        border-radius: 18px; /* Balanced radius */
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    }

    .pricing-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 28px rgba(78, 115, 223, 0.12);
        border-color: #4e73df;
    }

    .pricing-card.is-pro {
        border: 2px solid #4e73df;
        box-shadow: 0 8px 25px rgba(78, 115, 223, 0.15);
    }

    .pricing-badge {
        position: absolute;
        top: 14px;
        right: -35px;
        background: #4e73df;
        color: white;
        padding: 4px 40px;
        font-size: 11px;
        font-weight: bold;
        text-transform: uppercase;
        transform: rotate(45deg);
        letter-spacing: 1px;
    }

    .pricing-header {
        padding: 25px 20px 15px; /* Medium padding */
        text-align: center;
        border-bottom: 1px solid #f8f9fc;
    }

    .plan-name {
        font-size: 14px; /* Medium font */
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.2px;
        color: #858796;
        margin-bottom: 10px;
    }

    .is-pro .plan-name {
        color: #4e73df;
    }

    .pricing-price {
        font-size: 42px; /* Perfect balance between 48 and 36 */
        font-weight: 900;
        color: #3a3b45;
        line-height: 1;
        margin-bottom: 4px;
    }

    .pricing-price span {
        font-size: 18px;
        vertical-align: super;
        color: #858796;
    }

    .pricing-interval {
        font-size: 13px;
        font-weight: 600;
        color: #b7b9cc;
        text-transform: uppercase;
    }

    .pricing-body {
        padding: 20px 15px;
        flex-grow: 1;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .feature-list li {
        padding: 10px 0; /* Medium padding */
        font-size: 14px; /* Readable text */
        color: #5a5c69;
        display: flex;
        align-items: center;
        border-bottom: 1px dashed #eaecf4;
    }

    .feature-list li:last-child {
        border-bottom: none;
    }

    .feature-icon {
        color: #1cc88a;
        margin-right: 12px;
        font-size: 15px;
    }

    .feature-icon.pro-icon {
        color: #4e73df;
    }

    .pricing-footer {
        padding: 15px 20px 25px; /* Enough space for the button */
        text-align: center;
        margin-top: auto;
    }

    .btn-subscribe {
        border-radius: 50px;
        padding: 10px 22px; /* Balanced button size */
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .is-pro .btn-subscribe {
        background: #4e73df;
        color: white;
        box-shadow: 0 4px 12px rgba(78, 115, 223, 0.35);
    }

    .is-pro .btn-subscribe:hover {
        background: #2e59d9;
        transform: scale(1.03);
    }
</style>
@endsection

@section('main-content')
    <div class="text-center mb-4">
        <h1 class="h3 mb-2 text-gray-800 font-weight-bold">Choose Your Perfect Plan</h1>
        <p class="text-muted small mb-0">Unlock the full power of AI Blogs and Podcasts</p>
    </div>

    <div class="row pricing-wrapper justify-content-center">
        @foreach ($plans as $plan)
            @php
                $isPro = \Illuminate\Support\Str::contains(strtolower($plan->name), 'pro');
            @endphp

            <div class="col-lg-3 col-md-6 mb-4 d-flex">
                <div class="pricing-card w-100 {{ $isPro ? 'is-pro' : '' }}">

                    @if($isPro)
                        <div class="pricing-badge">Popular</div>
                    @endif

                    <div class="pricing-header">
                        <div class="plan-name">{{ $plan->name }}</div>
                        <div class="pricing-price">
                            <span>$</span>{{ number_format($plan->price) }}
                        </div>
                        <div class="pricing-interval">
                            / {{ ucfirst($plan->interval) }}
                        </div>
                    </div>

                    <div class="pricing-body">
                        <ul class="feature-list">
                            <li>
                                <i class="fas fa-check-circle feature-icon"></i>
                                <strong>{{ number_format($plan->articles_per_month) }}</strong>&nbsp;AI Articles
                            </li>

                            <li>
                                <i class="fas fa-check-circle feature-icon"></i>
                                SEO & Structure Gen
                            </li>

                            @if($isPro)
                                <li>
                                    <i class="fas fa-podcast feature-icon pro-icon"></i>
                                    <strong>Advanced Studio</strong>
                                </li>
                                <li>
                                    <i class="fas fa-volume-up feature-icon pro-icon"></i>
                                    Premium Voices
                                </li>
                                <li>
                                    <i class="fas fa-users feature-icon pro-icon"></i>
                                    Multi-Speaker Scripts
                                </li>
                            @else
                                <li>
                                    <i class="fas fa-podcast feature-icon"></i>
                                    Basic Scripts
                                </li>
                                <li>
                                    <i class="fas fa-volume-down feature-icon"></i>
                                    Standard Voices
                                </li>
                            @endif

                            <li>
                                <i class="fas fa-tachometer-alt feature-icon"></i>
                                Full Dashboard
                            </li>
                            <li>
                                <i class="fas fa-headset feature-icon"></i>
                                {{ $isPro ? 'Priority Support' : 'Email Support' }}
                            </li>
                        </ul>
                    </div>

                    <div class="pricing-footer">
                        <a href="{{ route('subscription.checkout', $plan->id) }}"
                           class="btn w-100 btn-subscribe {{ $isPro ? 'btn-primary' : 'btn-outline-primary' }}">
                            Get Started
                        </a>
                    </div>

                </div>
            </div>
        @endforeach
    </div>
@endsection
