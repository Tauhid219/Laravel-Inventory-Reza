@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="$subCategory->name" subtitle="Review products assigned to this sub-category.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $subCategory])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        @livewire('tables.product-by-sub-category-table', ['subCategory' => $subCategory])
    </x-adminlte.page-body>
@endsection
