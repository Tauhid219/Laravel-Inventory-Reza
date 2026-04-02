@extends('layouts.auth')

@section('title', __('Reset Password'))
@section('auth_heading', __('Create a fresh password for your account.'))
@section('auth_subtitle', __('Choose a strong password to restore access and continue working securely.'))

@section('content')
    <div class="card-body p-4 p-lg-5">
        <h2 class="h3 text-center mb-2">{{ __('Reset Password') }}</h2>
        <p class="text-center text-muted mb-4">{{ __('Set a new password for your account below.') }}</p>

        <form action="{{ route('password.store') }}" method="POST" autocomplete="off">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email address') }}</label>
                <input type="email" name="email" id="email"
                       class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email', $request->email) }}"
                       placeholder="your@email.com">

                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('New Password') }}</label>
                <input type="password" name="password" id="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="{{ __('Password') }}"
                       autocomplete="off">

                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="{{ __('Password confirmation') }}"
                       autocomplete="off">
            </div>
            <div class="form-footer">
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </form>
    </div>
@endsection
