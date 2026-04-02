@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Units')" subtitle="Maintain measurement units used across the product catalog.">
        <x-slot:actions>
            <a href="{{ route('units.create') }}" class="btn btn-primary">{{ __('Add Unit') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($units->isEmpty())
            <x-empty title="No units found" message="Create a unit before assigning it to products."
                button_label="{{ __('Add your first Unit') }}" button_route="{{ route('units.create') }}" />
        @else
            @livewire('tables.unit-table')
        @endif
    </x-adminlte.page-body>
@endsection
