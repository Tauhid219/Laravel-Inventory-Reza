@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit User')" subtitle="Update the access-control user profile and role assignment.">
        <x-slot:actions>
            <a href="{{ route('user.index') }}" class="btn btn-default">
                {{ __('Back to Users') }}
            </a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        @include('role-permission.nav-links')

        <x-alert />

        <form action="{{ route('user.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('User Details') }}
                    </x-slot:title>
                </x-slot:header>

                <x-slot:content>
                    <div class="row row-cards">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                    class="form-control @error('name') is-invalid @enderror" />
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="text" id="email" name="email" value="{{ old('email', $user->email) }}"
                                    class="form-control @error('email') is-invalid @enderror" />
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="text" id="password" name="password"
                                    class="form-control @error('password') is-invalid @enderror" />
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">{{ __('Role') }}</label>
                                <select name="role[]" class="form-control @error('role') is-invalid @enderror" multiple>
                                    @foreach ($role as $roles)
                                        @if ($roles->name != 'super-admin' || auth()->user()->hasRole('super-admin'))
                                            <option value="{{ $roles->name }}"
                                                {{ $user->hasRole($roles->name) ? 'selected' : '' }}>
                                                {{ $roles->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('role')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">{{ __('Update') }}</x-button.save>
                    <x-button.back route="{{ route('user.index') }}">{{ __('Cancel') }}</x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
