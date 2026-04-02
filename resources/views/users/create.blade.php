@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create User')" subtitle="Set up a new application user, profile image, and role access.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row row-cards">
                <div class="col-lg-4">
                    <x-card>
                        <x-slot:header>
                            <x-slot:title>
                                {{ __('User Image') }}
                            </x-slot:title>
                        </x-slot:header>

                        <div class="text-center">
                            <img class="img-account-profile mb-3"
                                src="{{ asset('assets/img/demo/user-placeholder.svg') }}" alt=""
                                id="image-preview">

                            <div class="small text-muted mb-2">{{ __('JPG or PNG no larger than 1 MB') }}</div>

                            <input type="file" id="image" name="photo" accept="image/*" onchange="previewImage();"
                                class="form-control @error('photo') is-invalid @enderror">

                            @error('photo')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </x-card>
                </div>

                <div class="col-lg-8">
                    <x-card>
                        <x-slot:header>
                            <x-slot:title>
                                {{ __('User Details') }}
                            </x-slot:title>

                            <x-slot:actions>
                                <x-action.close route="{{ route('users.index') }}" />
                            </x-slot:actions>
                        </x-slot:header>

                        <x-slot:content>
                            <div class="row row-cards">
                                <div class="col-md-12">
                                    <x-input name="name" :value="old('name')" required="true" />
                                    <x-input name="email" :value="old('email')" required="true" />
                                    <x-input name="username" :value="old('username')" required="true" />
                                </div>

                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="roles" class="form-label">{{ __('Roles') }}</label>
                                        <select name="roles[]" id="roles"
                                            class="form-control @error('roles') is-invalid @enderror" multiple required>
                                            @foreach ($role as $r)
                                                <option value="{{ $r->name }}"
                                                    {{ is_array(old('roles')) && in_array($r->name, old('roles')) ? 'selected' : '' }}>
                                                    {{ $r->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-hint text-info">
                                            {{ __('Hold CTRL to select multiple roles.') }}
                                        </small>
                                        @error('roles')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <x-input type="password" name="password" />
                                </div>

                                <div class="col-sm-6">
                                    <x-input type="password" name="password_confirmation"
                                        label="Password Confirmation" />
                                </div>
                            </div>
                        </x-slot:content>

                        <x-slot:footer class="text-end">
                            <x-button.save type="submit">
                                {{ __('Save') }}
                            </x-button.save>

                            <x-button.back route="{{ route('users.index') }}">
                                {{ __('Cancel') }}
                            </x-button.back>
                        </x-slot:footer>
                    </x-card>
                </div>
            </div>
        </form>
    </x-adminlte.page-body>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
