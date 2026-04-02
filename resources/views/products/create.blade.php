@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Product')">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Product Create') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('products.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <div class="row row-cards">
                                        <div class="col-md-12">

                                            <x-input name="name" id="name" placeholder="Product name"
                                                value="{{ old('name') }}" />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">
                                                    Product Category
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="category_id" id="category_id"
                                                    class="form-select @error('category_id') is-invalid @enderror">
                                                    <option selected="" disabled="">
                                                        Select a category:
                                                    </option>

                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            @if (old('category_id') == $category->id) selected="selected" @endif>
                                                            {{ $category->name }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="sub_category_id" class="form-label">
                                                    Product Subcategory
                                                </label>
                                                <select name="sub_category_id" id="sub_category_id"
                                                    class="form-select @error('subcategory_id') is-invalid @enderror">
                                                    <option selected="" disabled="">
                                                        Select a subcategory:
                                                    </option>

                                                    @foreach ($subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}"
                                                            @if (old('sub_category_id') == $subCategory->id) selected="selected" @endif>
                                                            {{ $subCategory->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('sub_category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="sub_category_id" class="form-label">
                                                    Product Subcategory
                                                </label>
                                                <select name="sub_category_id" id="sub_category_id"
                                                    class="form-select @error('sub_category_id') is-invalid @enderror">
                                                    <option selected="" disabled="">Select a subcategory:</option>
                                                </select>
                                                @error('sub_category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="unit_id">
                                                    {{ __('Unit') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                @if ($units->count() === 1)
                                                    <select name="unit_id" id="unit_id"
                                                        class="form-select @error('unit_id') is-invalid @enderror" readonly>
                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}" selected>
                                                                {{ $unit->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @else
                                                    <select name="unit_id" id="unit_id"
                                                        class="form-select @error('unit_id') is-invalid @enderror">
                                                        <option selected="" disabled="">
                                                            Select a unit:
                                                        </option>

                                                        @foreach ($units as $unit)
                                                            <option value="{{ $unit->id }}"
                                                                @selected(old('unit_id') == $unit->id)>
                                                                {{ $unit->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                @endif

                                                @error('unit_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number" label="Price" name="buying_price" id="buying_price"
                                                placeholder="0" value="{{ old('buying_price') }}" />
                                        </div>

                                        {{-- <div class="col-sm-6 col-md-6">
                                            <x-input type="number" label="Selling Price" name="selling_price"
                                                id="selling_price" placeholder="0" value="{{ old('selling_price') }}" />
                                        </div> --}}

                                        <div class="col-sm-6 col-md-6">
                                            <x-input disabled type="number" label="Quantity" name="quantity" id="quantity"
                                                placeholder="0" value="{{ old('quantity') }}" />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <x-input type="number" label="Quantity Alert" name="quantity_alert"
                                                id="quantity_alert" placeholder="0" value="{{ old('quantity_alert') }}" />
                                        </div>

                                        {{-- <div class="col-sm-6 col-md-6">
                                            <x-input type="number" label="Tax" name="tax" id="tax"
                                                placeholder="0" value="{{ old('tax') }}" />
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="tax_type">
                                                    {{ __('Tax Type') }}
                                                </label>

                                                <select name="tax_type" id="tax_type"
                                                    class="form-select @error('tax_type') is-invalid @enderror">
                                                    @foreach (\App\Enums\TaxType::cases() as $taxType)
                                                        <option value="{{ $taxType->value }}"
                                                            @selected(old('tax_type') == $taxType->value)>
                                                            {{ $taxType->label() }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('tax_type')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="notes" class="form-label">
                                                    {{ __('Notes') }}
                                                </label>

                                                <textarea name="notes" id="notes" rows="5" class="form-control @error('notes') is-invalid @enderror"
                                                    placeholder="Product notes"></textarea>

                                                @error('notes')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <x-button.save type="submit">
                        {{ __('Save') }}
                    </x-button.save>

                    <x-button.back route="{{ route('products.index') }}">
                        {{ __('Cancel') }}
                    </x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection

@pushonce('page-scripts')
    @include('products.partials.subcategory-script', ['selectedSubCategoryId' => old('sub_category_id')])
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
