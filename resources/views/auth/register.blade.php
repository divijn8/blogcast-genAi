@extends('layouts.app')

@section('main-content')
<div class="container-fluid" style="background-color: #f0f2f5;">
    <div class="row min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="col-lg-10 col-xl-9">

            <div class="card o-hidden border-0 shadow-lg" style="border-radius: 1rem;">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body --><div class="row g-0">
                        <div class="col-lg-6 d-none d-lg-block d-flex align-items-center justify-content-center" style="background-color: #007bff; border-radius: 1rem 0 0 1rem;">
                            <img src="{{ asset('frontend\assets\img\register.png') }}" alt="Register Image" class="img-fluid p-4" style="margin-top: 8rem; max-height: 100%; object-fit: contain;">
                        </div>
                        <div class="col-lg-6 d-flex flex-column justify-content-center">
                            <div class="p-4 p-md-5">
                                <div class="text-center">
                                     <a href="{{ url('/') }}">
                                        <img src="{{ asset('frontend/assets/img/logo.png') }}" height="150px" alt="Logo">
                                    </a>
                                    <h1 class="h4 text-gray-900 mb-4">{{ __('Create an Account!') }}</h1>
                                </div>
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    {{-- Name --}}
                                    <div class="form-floating mb-3">
                                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                                        <label for="name">{{ __('Name') }}</label>
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Email Address --}}
                                    <div class="form-floating mb-3">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="name@example.com">
                                        <label for="email">{{ __('Email Address') }}</label>
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Password --}}
                                    <div class="form-floating mb-3">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                                        <label for="password">{{ __('Password') }}</label>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    {{-- Confirm Password --}}
                                    <div class="form-floating mb-4">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                    </div>

                                    {{-- Register Button --}}
                                    <div class="d-grid mb-3">
                                        <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                            {{ __('Register Account') }}
                                        </button>
                                    </div>

                                </form>
                                <hr>
                                <div class="text-center">
                                     <a class="small" href="{{ route('login') }}">{{ __('Already have an account? Login!') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Hide the main navbar on auth pages --}}
<style>
    nav.navbar {
        display: none !important;
    }
    /* Ensure form-floating labels don't get overwritten by old bootstrap */
    .form-floating > .form-control {
        height: calc(3.8rem + 2px) !important;
        padding: 1.25rem 0.75rem 0.5rem !important;
    }
    .form-floating > label {
        padding: 1rem 0.75rem !important;
    }
    main {
        padding: 0;
    }
</style>
@endsection

