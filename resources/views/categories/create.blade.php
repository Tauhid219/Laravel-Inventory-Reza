@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Category')" subtitle="Add a new product category and optionally assign role visibility.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form method="POST" action="{{ route('categories.store') }}">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Category Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('categories.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <livewire:name />

                    <livewire:slug />

                    <div class="mb-0">
                        <label class="form-label">{{ __('Assign to Role (Permission)') }}</label>
                        <select name="role_name" class="form-control @error('role_name') is-invalid @enderror">
                            <option value="">{{ __('-- No Role (Visible to All) --') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}" @selected(old('role_name') == $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">
                        {{ __('Save') }}
                    </x-button.save>

                    <x-button.back route="{{ route('categories.index') }}">
                        {{ __('Cancel') }}
                    </x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
