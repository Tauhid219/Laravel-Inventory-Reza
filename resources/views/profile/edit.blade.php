@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Profile')" subtitle="Update your account details, email address, username, and profile photo.">
        <x-slot:actions>
            <a href="{{ route('profile.settings') }}" class="btn btn-default">{{ __('Security Settings') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <div class="mb-4">
            <div class="btn-group">
                <a href="{{ route('profile.edit') }}" class="btn btn-primary">{{ __('Profile') }}</a>
                <a href="{{ route('profile.settings') }}" class="btn btn-default">{{ __('Settings') }}</a>
            </div>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('patch')

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
                                id="image-preview" />

                            <div class="small text-muted mb-2">{{ __('JPG or PNG no larger than 1 MB') }}</div>

                            <input class="form-control @error('photo') is-invalid @enderror" type="file" id="image"
                                name="photo" accept="image/*" onchange="previewImage();">

                            @error('photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
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
                        </x-slot:header>

                        <x-slot:content>
                            <x-input name="name" value="{{ old('name', $user->name) }}" :required="true" />
                            <x-input name="email" label="Email address" value="{{ old('email', $user->email) }}" :required="true" />
                            <x-input name="username" value="{{ old('username', $user->username) }}" :required="true" />
                        </x-slot:content>

                        <x-slot:footer class="text-end">
                            <x-button.save type="submit">
                                {{ __('Update') }}
                            </x-button.save>

                            <x-button.back route="{{ route('dashboard') }}">
                                {{ __('Cancel') }}
                            </x-button.back>
                        </x-slot:footer>
                    </x-card>
                </div>
            </div>
        </form>
    </x-adminlte.page-body>
@endsection

@push('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpush
