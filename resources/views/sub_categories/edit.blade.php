@extends('layouts.tabler')

@section('content')
    <div class="page-body">
        <div class="container-xl">
            <div class="card">
                <div class="card-header">
                    <div>
                        <h3 class="card-title">
                            {{ __('Sub Category Details') }}
                        </h3>
                    </div>
                    <div class="card-actions">
                        <x-action.close route="{{ route('sub-categories.index') }}" />
                    </div>
                </div>
                <form method="POST" action="{{ route('sub-categories.update', $subCategory->slug) }}">
                    @csrf
                    @method('put')
                    <div class="card-body">
                        {{-- <livewire:name :value="$subCategory->name" /> --}}
                        <x-input label="{{ __('Name') }}" id="name" name="name" :value="old('name', $subCategory->name)" required />
                        {{-- <livewire:slug :value="$subCategory->slug" /> --}}
                        <x-input label="{{ __('Slug') }}" id="slug" name="slug" :value="old('slug', $subCategory->slug)" required />
                        {{-- <livewire:select-category :selected="$subCategory->category_id" /> --}}
                        <div class="mb-3">
                            <label for="category_id" class="form-label required">Select Category</label>
                            <select id="category_id" name="category_id"
                                class="form-select @error('category_id') is-invalid @enderror">
                                <option value="">Select a category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id', $subCategory->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <x-button.save type="submit">
                            {{ __('Update') }}
                        </x-button.save>

                        <x-button.back route="{{ route('sub-categories.index') }}">
                            {{ __('Cancel') }}
                        </x-button.back>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@pushonce('page-scripts')
    <script>
        // Slug Generator
        const title = document.querySelector("#name");
        const slug = document.querySelector("#slug");
        title.addEventListener("keyup", function() {
            let preslug = title.value;
            preslug = preslug.replace(/ /g, "-");
            slug.value = preslug.toLowerCase();
        });
    </script>
@endpushonce
