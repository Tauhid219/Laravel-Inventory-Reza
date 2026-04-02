@extends('layouts.auth')

@section('title', __('Forgot Password'))
@section('auth_heading', __('Reset access without losing your data.'))
@section('auth_subtitle', __('We will send a secure password reset link to the email address associated with your account.'))

@section('content')
<div class="card-body p-4 p-lg-5">
    <h2 class="h3 text-center mb-2">{{ __('Forgot Password') }}</h2>
    <p class="text-center text-muted mb-4">
        {{ __('Enter your email address and we will send you a reset link.') }}
    </p>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form action="{{ route('password.email') }}" method="post" autocomplete="off" novalidate>
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">{{ __('Email address') }}</label>
            <input type="email" name="email" id="email"
                   class="form-control @error('email') is-invalid @enderror"
                   value="{{ old('email') }}"
                   placeholder="your@email.com">

            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary w-100">
                {{ __('Send Reset Link') }}
            </button>
        </div>
    </form>

    <div class="text-center text-muted mt-4 inventory-auth-links">
        {{ __('Remembered your password?') }}
        <a href="{{ route('login') }}">{{ __('Back to sign in') }}</a>
    </div>
</div>
@endsection
