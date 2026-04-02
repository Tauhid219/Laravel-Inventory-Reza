@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Settings')" subtitle="Manage password security and permanent account actions.">
        <x-slot:actions>
            <a href="{{ route('profile.edit') }}" class="btn btn-default">{{ __('Back to Profile') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <div class="mb-4">
            <div class="btn-group">
                <a href="{{ route('profile.edit') }}" class="btn btn-default">{{ __('Profile') }}</a>
                <a href="{{ route('profile.settings') }}" class="btn btn-primary">{{ __('Settings') }}</a>
            </div>
        </div>

        <div class="row row-cards">
            <div class="col-lg-8">
                <x-card class="mb-4">
                    <x-slot:header>
                        <x-slot:title>
                            {{ __('Change Password') }}
                        </x-slot:title>
                    </x-slot:header>

                    <x-form action="{{ route('password.update') }}" method="PUT">
                        <div class="card-body">
                            <x-input type="password" name="current_password" label="Current Password" required />
                            <x-input type="password" name="password" label="New Password" required />
                            <x-input type="password" name="password_confirmation" label="Confirm Password" required />
                        </div>

                        <div class="card-footer text-end">
                            <x-button.save type="submit">{{ __('Save') }}</x-button.save>
                        </div>
                    </x-form>
                </x-card>
            </div>

            <div class="col-lg-4">
                <x-card class="border-danger">
                    <x-slot:header>
                        <x-slot:title>
                            {{ __('Delete Account') }}
                        </x-slot:title>
                    </x-slot:header>

                    <x-slot:content>
                        <p class="text-muted">
                            {{ __('Deleting your account is permanent and cannot be undone. Please confirm your password before continuing.') }}
                        </p>

                        <form action="{{ route('profile.destroy') }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <div class="mb-3">
                                <label for="delete_password" class="form-label">{{ __('Current Password') }}</label>
                                <input type="password" id="delete_password" name="password"
                                    class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                    required>
                                @error('password', 'userDeletion')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete your account?')">
                                {{ __('Delete Account') }}
                            </button>
                        </form>
                    </x-slot:content>
                </x-card>
            </div>
        </div>
    </x-adminlte.page-body>
@endsection
