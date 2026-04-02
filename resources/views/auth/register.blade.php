@extends('layouts.auth')

@section('title', __('Register'))
@section('auth_heading', __('Create your inventory account.'))
@section('auth_subtitle', __('Register a new account to access purchasing, stock, quotation, and sales workflows.'))

@section('content')
<div class="card-body p-4 p-lg-5">
    <h2 class="h3 text-center mb-2">{{ __('Create Account') }}</h2>
    <p class="text-center text-muted mb-4">{{ __('Set up your credentials to start using the inventory system.') }}</p>

    <form action="{{ route('register') }}" method="POST" autocomplete="off">
        @csrf

        <x-input name="name" :value="old('name')" placeholder="Your name" required="true"/>
        <x-input name="email" :value="old('email')" placeholder="your@email.com" required="true"/>
        <x-input name="username" :value="old('username')" placeholder="Your username" required="true"/>
        <x-input type="password" name="password" placeholder="Password" required="true"/>
        <x-input type="password" name="password_confirmation" placeholder="Password confirmation" required="true" label="Password Confirmation"/>

        <div class="mb-3">
            <label class="form-check">
                <input type="checkbox" name="terms-of-service" id="terms-of-service"
                       class="form-check-input @error('terms-of-service') is-invalid @enderror">
                <span class="form-check-label">
                    {{ __('I agree to the platform terms and usage policy.') }}
                </span>
            </label>
            @error('terms-of-service')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-footer">
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Create new account') }}
            </button>
        </div>
    </form>

    <div class="text-center text-muted mt-4 inventory-auth-links">
        {{ __('Already have an account?') }}
        <a href="{{ route('login') }}" tabindex="-1">{{ __('Sign in') }}</a>
    </div>
</div>
@endsection
