@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            {{ __('Category Details') }}
                        </h3>
                    </div>
                    <div class="card-actions">
                        <x-action.close route="{{ route('categories.index') }}" />
                    </div>
                </div>
                <form method="POST" action="{{ route('categories.store') }}">
                    @csrf
                    <div class="card-body">
                        <livewire:name />

                        <livewire:slug />

                        <div class="mb-3">
                            <label class="form-label">Assign to Role (Permission)</label>
                            <select name="role_name" class="form-control @error('role_name') is-invalid @enderror">
                                <option value="">-- No Role (Visible to All) --</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ old('role_name') == $role->name ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card-footer text-end">
                        <x-button.save type="submit">
                            {{ __('Save') }}
                        </x-button.save>

                        <x-button.back route="{{ route('categories.index') }}">
                            {{ __('Cancel') }}
                        </x-button.back>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
