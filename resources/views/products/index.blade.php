@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Products')" subtitle="Manage catalog items, stock details, and product setup.">
        <x-slot:actions>
            <div class="btn-group">
                <a href="{{ route('products.import.view') }}" class="btn btn-default">Import</a>
                <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
            </div>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @livewire('tables.product-table')
    </x-adminlte.page-body>
@endsection
