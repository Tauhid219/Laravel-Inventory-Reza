@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Role')" subtitle="Rename an existing role while keeping the current access model intact.">
        <x-slot:actions>
            <a href="{{ route('rl.index') }}" class="btn btn-default">
                {{ __('Back to Roles') }}
            </a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        @include('role-permission.nav-links')

        <x-alert />

        <form action="{{ route('rl.update', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Role Details') }}
                    </x-slot:title>
                </x-slot:header>

                <x-slot:content>
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Role Name') }}</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $role->name) }}"
                            class="form-control @error('name') is-invalid @enderror" />
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">{{ __('Update') }}</x-button.save>
                    <x-button.back route="{{ route('rl.index') }}">{{ __('Cancel') }}</x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
