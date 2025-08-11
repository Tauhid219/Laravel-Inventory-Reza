@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        @if ($subCategories->isEmpty())
            <x-empty title="No sub-categories found"
                message="Try adjusting your search or filter to find what you're looking for."
                button_label="{{ __('Add your first Sub Category') }}" button_route="{{ route('sub-categories.create') }}" />
        @else
            <div class="container-xl">
                <x-alert />

                @livewire('tables.sub-category-table')
            </div>
        @endif
    </div>
@endsection
