@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Roles & Permissions')" subtitle="Manage access control, roles, and administrative user assignments.">
        <x-slot:actions>
            <a href="{{ route('rl.index') }}" class="btn btn-primary">
                {{ __('Open Roles') }}
            </a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        @include('role-permission.nav-links')

        <x-card>
            <x-slot:content>
                <div class="py-4 text-center">
                    <h3 class="mb-2">{{ __('Welcome to Role and Permission Management') }}</h3>
                    <p class="text-muted mb-0">
                        {{ __('Use the sections above to maintain roles, permission rules, and user access assignments.') }}
                    </p>
                </div>
            </x-slot:content>
        </x-card>
    </x-adminlte.page-body>
@endsection
