@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="$role->name" subtitle="Review role details and the permissions currently assigned to it.">
        <x-slot:actions>
            <a href="{{ route('rl.index') }}" class="btn btn-default">
                {{ __('Back to Roles') }}
            </a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        @include('role-permission.nav-links')

        @if (session('status'))
            <div class="alert alert-success bg-white">{{ session('status') }}</div>
        @endif

        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Role Details') }}
                </x-slot:title>
            </x-slot:header>

            <x-slot:content>
                <div class="mb-4">
                    <div class="text-muted small mb-1">{{ __('Role Name') }}</div>
                    <div class="h5 mb-0">{{ $role->name }}</div>
                </div>

                <div>
                    <div class="text-muted small mb-2">{{ __('Permissions') }}</div>
                    @if (!empty($rolePermissions) && $rolePermissions->count())
                        <div class="d-flex flex-wrap gap-2">
                            @foreach ($rolePermissions as $permission)
                                <span class="badge bg-primary text-white">{{ $permission->name }}</span>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">{{ __('No permissions assigned yet.') }}</p>
                    @endif
                </div>
            </x-slot:content>
        </x-card>
    </x-adminlte.page-body>
@endsection
