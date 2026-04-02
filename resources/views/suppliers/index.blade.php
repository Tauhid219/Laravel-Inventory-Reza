@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Suppliers')" subtitle="Maintain supplier records, vendor contacts, and procurement parties.">
        <x-slot:actions>
            <a href="{{ route('suppliers.create') }}" class="btn btn-primary">{{ __('Add Supplier') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($suppliers->isEmpty())
            <x-empty title="No suppliers found"
                message="Create a supplier to start managing purchase sources, shop details, and vendor contacts."
                button_label="{{ __('Add your first Supplier') }}" button_route="{{ route('suppliers.create') }}" />
        @else
            @livewire('tables.supplier-table')
        @endif
    </x-adminlte.page-body>
@endsection
