@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="$category->name" subtitle="Review products currently assigned to this category.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $category])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        @livewire('tables.product-by-category-table', ['category' => $category])
    </x-adminlte.page-body>
@endsection
