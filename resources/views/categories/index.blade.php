@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Categories')" subtitle="Organize product groups and control category-level visibility.">
        <x-slot:actions>
            <a href="{{ route('categories.create') }}" class="btn btn-primary">{{ __('Add Category') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($categories->isEmpty())
            <x-empty title="No categories found"
                message="Create a category to start organizing products and permissions."
                button_label="{{ __('Add your first Category') }}" button_route="{{ route('categories.create') }}" />
        @else
            @livewire('tables.category-table')
        @endif
    </x-adminlte.page-body>
@endsection
