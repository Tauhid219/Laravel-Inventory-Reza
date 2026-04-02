@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="$user->name" subtitle="Review account details and assigned role access for this user.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $user])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <div class="row row-cards">
            <div class="col-lg-4">
                <x-card>
                    <x-slot:header>
                        <x-slot:title>
                            {{ __('Profile Image') }}
                        </x-slot:title>
                    </x-slot:header>

                    <div class="text-center">
                        <img id="image-preview" class="img-account-profile mb-2"
                            src="{{ $user->photo ? asset('storage/profile/' . $user->photo) : asset('assets/img/demo/user-placeholder.svg') }}"
                            alt="">
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

                    <div class="table-responsive">
                        <table class="table table-bordered card-table table-vcenter text-nowrap">
                            <tbody>
                                <tr>
                                    <td>{{ __('Name') }}</td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Email address') }}</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('Username') }}</td>
                                    <td>{{ $user->username }}</td>
                                </tr>
                                <tr>
                                    <td>{{ __('User Role') }}</td>
                                    <td>
                                        @foreach ($user->getRoleNames() as $rolename)
                                            <span class="badge bg-primary text-white mx-1">{{ $rolename }}</span>
                                        @endforeach
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <x-slot:footer class="text-end">
                        <x-button.edit route="{{ route('users.edit', $user) }}">
                            {{ __('Edit') }}
                        </x-button.edit>

                        <x-button.back route="{{ route('users.index') }}">
                            {{ __('Cancel') }}
                        </x-button.back>
                    </x-slot:footer>
                </x-card>
            </div>
        </div>
    </x-adminlte.page-body>
@endsection
