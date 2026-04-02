@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Unit')" subtitle="Define a new measurement unit and its short code.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('units.store') }}" method="POST">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Unit Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('units.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <livewire:name />

                    <livewire:slug />

                    <x-input label="{{ __('Short Code') }}" id="short_code" name="short_code" :value="old('short_code')" required />
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">
                        {{ __('Save') }}
                    </x-button.save>

                    <x-button.back route="{{ route('units.index') }}">
                        {{ __('Cancel') }}
                    </x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection

@pushonce('page-scripts')
    <script>
        const title = document.querySelector('#name');
        const slug = document.querySelector('#slug');

        title?.addEventListener('keyup', function() {
            let preslug = title.value;
            preslug = preslug.replace(/ /g, '-');
            slug.value = preslug.toLowerCase();
        });
    </script>
@endpushonce
