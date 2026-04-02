@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Roles')" subtitle="Create and maintain the roles that bundle permissions for administrators and staff.">
        @can('create role')
            <x-slot:actions>
                <a href="{{ route('rl.create') }}" class="btn btn-primary">
                    {{ __('Add Role') }}
                </a>
            </x-slot:actions>
        @endcan
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        @include('role-permission.nav-links')

        @if (session('status'))
            <div class="alert alert-success bg-white">{{ session('status') }}</div>
        @endif

        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Role List') }}
                </x-slot:title>
            </x-slot:header>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th class="text-center">{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($roles as $role)
                            <tr>
                                <td>{{ $role->id }}</td>
                                <td>{{ $role->name }}</td>
                                <td class="text-center">
                                    <form action="{{ route('rl.destroy', $role->id) }}" method="POST" class="d-inline">
                                        @can('create role')
                                            <a href="{{ route('rl.edit', $role->id) }}" class="btn btn-success btn-sm">
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @can('update role')
                                            <a href="{{ route('addPermissionToRole', $role->id) }}" class="btn btn-warning btn-sm">
                                                {{ __('Permissions') }}
                                            </a>
                                        @endcan
                                        @can('view role')
                                            <a href="{{ route('rl.show', $role->id) }}" class="btn btn-primary btn-sm">
                                                {{ __('Show') }}
                                            </a>
                                        @endcan
                                        @csrf
                                        @method('DELETE')
                                        @can('delete role')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this role?')">
                                                {{ __('Delete') }}
                                            </button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">{{ __('No roles found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </x-adminlte.page-body>
@endsection
