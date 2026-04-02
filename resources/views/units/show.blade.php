@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="$unit->name" subtitle="Review products currently assigned to this unit.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $unit])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        @livewire('tables.product-by-unit-table', ['unit' => $unit])
    </x-adminlte.page-body>
@endsection
