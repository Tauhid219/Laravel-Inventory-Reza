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

        <div class="text-center text-muted mt-4 inventory-auth-links">
            {{ __("Don't have an account yet?") }}
            <a href="{{ route('register') }}">{{ __('Create one') }}</a>
        </div>
</div>
@endsection
