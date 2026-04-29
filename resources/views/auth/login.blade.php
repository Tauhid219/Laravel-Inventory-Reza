@extends('layouts.auth')

@section('title', __('Sign In'))
@section('auth_heading', __('Welcome back to your inventory workspace.'))
@section('auth_subtitle', __('Use your account to continue with sales, purchasing, stock management, and reporting.'))

@section('content')
<div class="card-body p-4 p-lg-5">
        <h2 class="h3 text-center mb-2">{{ __('Sign In') }}</h2>
        <p class="text-center text-muted mb-4">{{ __('Enter your credentials to continue.') }}</p>

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @error('demo_login')
            <div class="alert alert-warning">{{ $message }}</div>
        @enderror

        <form action="{{ route('login') }}" method="POST" autocomplete="off">
            @csrf

            <x-input name="email" :value="old('email')" placeholder="your@email.com" required="true"/>

            <x-input type="password" name="password" placeholder="Your password" required="true"/>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <label for="remember" class="form-check">
                    <input type="checkbox" id="remember" name="remember" class="form-check-input"/>
                    <span class="form-check-label">{{ __('Remember me on this device') }}</span>
                </label>

                <a href="{{ route('password.request') }}" class="small">{{ __('Forgot password?') }}</a>
            </div>

            <div class="form-footer">
                <button type="submit" class="btn btn-primary btn-block">
                    {{ __('Sign in') }}
                </button>
            </div>
        </form>

        <div class="position-relative my-4">
            <hr>
            <span class="badge badge-light position-absolute top-50 start-50 translate-middle px-3">{{ __('or') }}</span>
        </div>

        <div class="border rounded-lg p-3 p-lg-4 bg-light">
            <div class="d-flex align-items-start justify-content-between gap-3 mb-3">
                <div>
                    <h3 class="h5 mb-1">{{ __('Explore Demo Admin') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('Use read-only demo access to review inventory, orders, purchases, quotations, and reports.') }}
                    </p>
                </div>
                <span class="badge badge-info text-uppercase">{{ __('Read only') }}</span>
            </div>

            <p class="small text-muted mb-3">
                {{ __('Data changes, approvals, exports, and administrative actions are disabled in demo mode.') }}
            </p>

            <form action="{{ route('demo.login') }}" method="POST">
                @csrf

                <button type="submit" class="btn btn-outline-primary btn-block">
                    <i class="fas fa-user-shield mr-2"></i>
                    {{ __('Login as Demo Admin') }}
                </button>
            </form>
        </div>

        <div class="text-center text-muted mt-4 inventory-auth-links">
            {{ __("Don't have an account yet?") }}
            <a href="{{ route('register') }}">{{ __('Create one') }}</a>
        </div>
</div>
@endsection
