@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Users')" subtitle="Manage application accounts, profile access, and assigned roles.">
        <x-slot:actions>
            <a href="{{ route('users.create') }}" class="btn btn-primary">
                {{ __('Add User') }}
            </a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />
        @livewire('tables.user-table')
    </x-adminlte.page-body>
@endsection
