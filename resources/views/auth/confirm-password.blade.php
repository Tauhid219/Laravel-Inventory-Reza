@extends('layouts.auth')

@section('title', __('Confirm Password'))
@section('auth_heading', __('Confirm your password to continue.'))
@section('auth_subtitle', __('Sensitive actions require a fresh password confirmation before access is granted.'))

@section('content')
    <div class="card-body p-4 p-lg-5">
        <form action="{{ route('password.confirm') }}" method="POST" autocomplete="off" novalidate>
        @csrf
            <div class="text-center mb-4">
                <h2 class="h3 mb-2">{{ __('Confirm Password') }}</h2>
                <p class="text-muted mb-0">
                    {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
                </p>
            </div>
            <div class="mb-4">
                <span class="avatar avatar-xl mb-3 shadow-none" style="background-image: url({{ Avatar::create(Auth::user()->name)->toBase64() }})"></span>
                <h3>{{ Auth::user()->name }}</h3>
            </div>
            <div class="mb-4">
                <label for="password" class="visually-hidden">{{ __('Password') }}</label>
                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ __('Password') }}">
                @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-100">
                    {{ __('Confirm') }}
                </button>
            </div>
        </form>
    </div>
@endsection
