@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit User')" subtitle="Update account details, profile image, assigned roles, and password.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $user])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <div class="row row-cards">
            <div class="col-lg-4">
                <x-card>
                    <x-slot:header>
                        <x-slot:title>
                            {{ __('Profile Image') }}
                        </x-slot:title>
                    </x-slot:header>

                    <div class="text-center">
                        <img class="img-account-profile mb-3"
                            src="{{ $user->photo ? asset('storage/profile/' . $user->photo) : asset('assets/img/demo/user-placeholder.svg') }}"
                            alt="" id="image-preview" />

                        <div class="small text-muted mb-2">{{ __('JPG or PNG no larger than 1 MB') }}</div>

                        <input class="form-control @error('photo') is-invalid @enderror"
                            type="file" id="image" name="photo" form="user-profile-form" accept="image/*"
                            onchange="previewImage();">

                        @error('photo')
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </x-card>
            </div>

            <div class="col-lg-8">
                <div class="row row-cards">
                    <div class="col-12">
                        <form id="user-profile-form" action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('put')

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
                                            <x-input name="name" :value="old('name', $user->name)" required="true" />
                                            <x-input name="username" :value="old('username', $user->username)" required="true" />
                                            <x-input name="email" :value="old('email', $user->email)" label="Email address" required="true" />
                                        </div>

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('Assign Roles') }}</label>
                                                <select name="roles[]" class="form-control @error('roles') is-invalid @enderror" multiple>
                                                    @foreach ($roles as $role)
                                                        <option value="{{ $role->name }}"
                                                            {{ $user->hasRole($role->name) ? 'selected' : '' }}>
                                                            {{ $role->name }}
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
                        </form>
                    </div>

                    <div class="col-12">
                        <form action="{{ route('users.updatePassword', $user) }}" method="POST">
                            @csrf
                            @method('put')

                            <x-card>
                                <x-slot:header>
                                    <x-slot:title>
                                        {{ __('Change Password') }}
                                    </x-slot:title>
                                </x-slot:header>

                                <x-slot:content>
                                    <div class="row row-cards">
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
                                        {{ __('Update') }}
                                    </x-button.save>

                                    <x-button.back route="{{ route('users.index') }}">
                                        {{ __('Cancel') }}
                                    </x-button.back>
                                </x-slot:footer>
                            </x-card>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-adminlte.page-body>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
