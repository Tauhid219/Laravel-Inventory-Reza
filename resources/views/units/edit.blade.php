@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Unit')" subtitle="Update unit naming, slug, and short code details.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $unit])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('units.update', $unit) }}" method="POST">
            @csrf
            @method('put')

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
                    <x-input label="{{ __('Unit Name') }}" id="name" name="name" :value="old('name', $unit->name)" required />

                    <x-input label="{{ __('Slug') }}" id="slug" name="slug" :value="old('slug', $unit->slug)" required />

                    <x-input label="{{ __('Short Code') }}" id="short_code" name="short_code" :value="old('short_code', $unit->short_code)" required />
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">
                        {{ __('Update') }}
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
