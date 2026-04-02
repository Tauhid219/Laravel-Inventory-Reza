@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Role Permissions')" :subtitle="__('Manage permission assignments for :role', ['role' => $role->name])">
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

        <x-alert />

        <form action="{{ route('givePermissionToRole', $role->id) }}" method="POST">
            @csrf
            @method('PUT')

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Permission Assignment') }}
                    </x-slot:title>
                </x-slot:header>

                <x-slot:content>
                    @error('permission')
                        <div class="text-danger mb-3">{{ $message }}</div>
                    @enderror

                    <div class="row">
                        @foreach ($permission as $permissions)
                            @php
                                $restricted = !auth()->user()->hasRole('super-admin') && in_array($permissions->name, $restrictedPermissions);
                            @endphp

                            @if (! $restricted)
                                <div class="col-md-4 col-lg-3 mb-3">
                                    <label class="d-flex align-items-center gap-2 border rounded px-3 py-2 mb-0">
                                        <input type="checkbox" name="permission[]" value="{{ $permissions->name }}"
                                            {{ in_array($permissions->id, $rolepermission) ? 'checked' : '' }}>
                                        <span>{{ $permissions->name }}</span>
                                    </label>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">{{ __('Update Permissions') }}</x-button.save>
                    <x-button.back route="{{ route('rl.index') }}">{{ __('Cancel') }}</x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
