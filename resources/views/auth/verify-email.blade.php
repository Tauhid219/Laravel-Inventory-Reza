@extends('layouts.auth')

@section('title', __('Verify Email'))
@section('auth_heading', __('Verify your email before getting started.'))
@section('auth_subtitle', __('Email verification helps protect your account and unlocks the authenticated experience.'))

@section('content')
<div class="card-body p-4 p-lg-5">
    <h2 class="h3 text-center mb-2">{{ __('Verify Email') }}</h2>
    <p class="text-center text-muted mb-4">
        {{ __('Thanks for signing up! Please verify your email address using the link we just sent you.') }}
    </p>

    <div class="mt-4 mb-4">
        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" role="alert">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif
    </div>

    <form action="{{ route('verification.send') }}" method="POST" autocomplete="off">
        @csrf
        <button type="submit" class="btn btn-primary w-100">
            {{ __('Resend Verification Email') }}
        </button>
    </form>

    <form action="{{ route('logout') }}" method="POST" autocomplete="off" class="mt-3">
        @csrf
        <button type="submit" class="btn btn-default w-100">
            {{ __('Log Out') }}
        </button>
    </form>
</div>
@endsection
