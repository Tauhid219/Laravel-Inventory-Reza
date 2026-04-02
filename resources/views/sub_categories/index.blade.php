@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Sub Categories')" subtitle="Manage the second level of your product catalog structure.">
        <x-slot:actions>
            <a href="{{ route('sub-categories.create') }}" class="btn btn-primary">{{ __('Add Sub Category') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($subCategories->isEmpty())
            <x-empty title="No sub-categories found"
                message="Create a sub-category to organize products under an existing category."
                button_label="{{ __('Add your first Sub Category') }}"
                button_route="{{ route('sub-categories.create') }}" />
        @else
            @livewire('tables.sub-category-table')
        @endif
    </x-adminlte.page-body>
@endsection
