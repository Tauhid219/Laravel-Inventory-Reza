@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Role Users')" subtitle="Manage users from the access-control area and review assigned roles.">
        @can('create user')
            <x-slot:actions>
                <a href="{{ route('user.create') }}" class="btn btn-primary">
                    {{ __('Add User') }}
                </a>
            </x-slot:actions>
        @endcan
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        @include('role-permission.nav-links')

        @if (session('status'))
            <div class="alert alert-success bg-white">{{ session('status') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger bg-white">{{ session('error') }}</div>
        @endif

        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('User List') }}
                </x-slot:title>
            </x-slot:header>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Roles') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($user as $singleUser)
                            <tr>
                                <td>{{ $singleUser->id }}</td>
                                <td>{{ $singleUser->name }}</td>
                                <td>{{ $singleUser->email }}</td>
                                <td>
                                    @foreach ($singleUser->getRoleNames() as $rolename)
                                        <span class="badge bg-primary text-white">{{ $rolename }}</span>
                                    @endforeach
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('user.edit', $singleUser->id) }}" class="btn btn-sm btn-success">
                                        {{ __('Edit') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">{{ __('No users found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </x-adminlte.page-body>
@endsection
