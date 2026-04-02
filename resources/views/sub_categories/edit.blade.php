@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Sub Category')" subtitle="Update the sub-category name, slug, or parent category.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $subCategory])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form method="POST" action="{{ route('sub-categories.update', $subCategory->slug) }}">
            @csrf
            @method('put')

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
                    <x-input label="{{ __('Name') }}" id="name" name="name" :value="old('name', $subCategory->name)" required />

                    <x-input label="{{ __('Slug') }}" id="slug" name="slug" :value="old('slug', $subCategory->slug)" required />

                    <div class="mb-0">
                        <label for="category_id" class="form-label required">{{ __('Select Category') }}</label>
                        <select id="category_id" name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">{{ __('Select a category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    @selected(old('category_id', $subCategory->category_id) == $category->id)>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">
                        {{ __('Update') }}
                    </x-button.save>

                    <x-button.back route="{{ route('sub-categories.index') }}">
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
