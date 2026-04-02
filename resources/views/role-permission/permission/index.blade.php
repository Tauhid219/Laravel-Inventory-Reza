@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Permissions')" subtitle="Maintain the permission catalog used to gate application features.">
        @can('create permission')
            <x-slot:actions>
                <a href="{{ route('pr.create') }}" class="btn btn-primary">
                    {{ __('Add Permission') }}
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
                    {{ __('Permission List') }}
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
                        @forelse ($permission as $permissions)
                            <tr>
                                <td>{{ $permissions->id }}</td>
                                <td>{{ $permissions->name }}</td>
                                <td class="text-center">
                                    <form action="{{ route('pr.destroy', $permissions->id) }}" method="POST" class="d-inline">
                                        @can('update permission')
                                            <a href="{{ route('pr.edit', $permissions->id) }}" class="btn btn-success btn-sm">
                                                {{ __('Edit') }}
                                            </a>
                                        @endcan
                                        @csrf
                                        @method('DELETE')
                                        @can('delete permission')
                                            <button type="submit" class="btn btn-danger btn-sm"
                                                onclick="return confirm('Are you sure you want to delete this permission?')">
                                                {{ __('Delete') }}
                                            </button>
                                        @endcan
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">{{ __('No permissions found.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </x-card>
    </x-adminlte.page-body>
@endsection
