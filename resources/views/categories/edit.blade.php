@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Category')" subtitle="Update category naming and role-based visibility settings.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $category])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('categories.update', $category->slug) }}" method="POST">
            @csrf
            @method('put')

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Category Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('categories.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <x-input label="{{ __('Name') }}" id="name" name="name" :value="old('name', $category->name)" required />

                    <x-input label="{{ __('Slug') }}" id="slug" name="slug" :value="old('slug', $category->slug)" required />

                    <div class="mb-0">
                        <label class="form-label">{{ __('Assign to Role (Permission)') }}</label>
                        <select name="role_name" class="form-control @error('role_name') is-invalid @enderror">
                            <option value="">{{ __('-- No Role (Visible to All) --') }}</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    @selected(old('role_name', $category->role_name) == $role->name)>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">
                        {{ __('Update') }}
                    </x-button.save>

                    <x-button.back route="{{ route('categories.index') }}">
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
