@extends('frontend.layouts.app')

@section('main-content')
    <h1>Select a Subscription Plan</h1>
    <ul>
        @foreach ($plans as $plan)
            <li>
                <strong>{{ $plan->name }}</strong> - ${{ number_format($plan->price) }} per {{ $plan->interval }} <br>
                <a href="#" class="button button-sm button-pasific hover-ripple-out">Subscribe Now</a>
            </li>
        @endforeach
    </ul>
@endsection
