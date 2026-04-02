@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Permission')" subtitle="Update the permission key used across roles and feature checks.">
        <x-slot:actions>
            <a href="{{ route('pr.index') }}" class="btn btn-default">
                {{ __('Back to Permissions') }}
            </a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        @include('role-permission.nav-links')

        <x-alert />

        <form action="{{ route('pr.update', $permission->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Permission Details') }}
                    </x-slot:title>
                </x-slot:header>

                <x-slot:content>
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Permission Name') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $permission->name) }}"
                            class="form-control @error('name') is-invalid @enderror" />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">{{ __('Update') }}</x-button.save>
                    <x-button.back route="{{ route('pr.index') }}">{{ __('Cancel') }}</x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
