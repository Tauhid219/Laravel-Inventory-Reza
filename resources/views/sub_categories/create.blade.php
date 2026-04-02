@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Sub Category')" subtitle="Attach a new sub-category to an existing category.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form method="POST" action="{{ route('sub-categories.store') }}">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Sub Category Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('sub-categories.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <livewire:select-category />

                    <livewire:name />

                    <livewire:slug />
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">
                        {{ __('Save') }}
                    </x-button.save>

                    <x-button.back route="{{ route('sub-categories.index') }}">
                        {{ __('Cancel') }}
                    </x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
