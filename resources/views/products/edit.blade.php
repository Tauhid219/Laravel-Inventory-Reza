@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Product')">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $product])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')

            <x-card>
                <x-slot:content>
                    <h3 class="card-title mb-4">
                        {{ __('Product Details') }}
                    </h3>

                    <div class="row row-cards">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label for="name" class="form-label">
                                                    {{ __('Name') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" id="name" name="name"
                                                    class="form-control @error('name') is-invalid @enderror"
                                                    placeholder="Product name" value="{{ old('name', $product->name) }}">

                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="category_id" class="form-label">
                                                    Product category
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <select name="category_id" id="category_id"
                                                    class="form-select @error('category_id') is-invalid @enderror">
                                                    <option selected="" disabled="">Select a category:</option>
                                                    @foreach ($categories as $category)
                                                        <option value="{{ $category->id }}"
                                                            @if (old('category_id', $product->category_id) == $category->id) selected="selected" @endif>
                                                            {{ $category->name }}</option>
                                                    @endforeach
                                                </select>

                                                @error('category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="sub_category_id" class="form-label">
                                                    {{ __('Subcategory') }}
                                                </label>
                                                <select name="sub_category_id" id="sub_category_id"
                                                    class="form-select @error('sub_category_id') is-invalid @enderror">
                                                    <option selected="" disabled="Select a subcategory:"></option>
                                                    @foreach ($subCategories as $subCategory)
                                                        <option value="{{ $subCategory->id }}"
                                                            @if (old('sub_category_id', $product->sub_category_id) == $subCategory->id) selected="selected" @endif>
                                                            {{ $subCategory->name }}</option>
                                                    @endforeach
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

                                                <select name="unit_id" id="unit_id"
                                                    class="form-select @error('unit_id') is-invalid @enderror">
                                                    <option selected="" disabled="">
                                                        Select a unit:
                                                    </option>

                                                    @foreach ($units as $unit)
                                                        <option value="{{ $unit->id }}"
                                                            @if (old('unit_id', $product->unit_id) == $unit->id) selected="selected" @endif>
                                                            {{ $unit->name }}</option>
                                                    @endforeach
                                                </select>

                                                @error('unit_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="buying_price">
                                                    Price
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" id="buying_price" name="buying_price"
                                                    class="form-control @error('buying_price') is-invalid @enderror"
                                                    placeholder="0"
                                                    value="{{ old('buying_price', $product->buying_price) }}">

                                                @error('buying_price')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="selling_price" class="form-label">
                                                    Selling price
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="text" id="selling_price" name="selling_price"
                                                    class="form-control @error('selling_price') is-invalid @enderror"
                                                    placeholder="0"
                                                    value="{{ old('selling_price', $product->selling_price) }}">

                                                @error('selling_price')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div> --}}

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity" class="form-label">
                                                    {{ __('Quantity') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input disabled type="number" id="quantity" name="quantity"
                                                    class="form-control @error('quantity') is-invalid @enderror"
                                                    min="0" value="{{ old('quantity', $product->quantity) }}"
                                                    placeholder="0">

                                                @error('quantity')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="quantity_alert" class="form-label">
                                                    {{ __('Quantity Alert') }}
                                                    <span class="text-danger">*</span>
                                                </label>

                                                <input type="number" id="quantity_alert" name="quantity_alert"
                                                    class="form-control @error('quantity_alert') is-invalid @enderror"
                                                    min="0" placeholder="0"
                                                    value="{{ old('quantity_alert', $product->quantity_alert) }}">

                                                @error('quantity_alert')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        {{-- <div class="col-sm-6 col-md-6">
                                            <div class="mb-3">
                                                <label for="tax" class="form-label">
                                                    {{ __('Tax') }}
                                                </label>

                                                <input type="number" id="tax" name="tax"
                                                    class="form-control @error('tax') is-invalid @enderror"
                                                    min="0" placeholder="0"
                                                    value="{{ old('tax', $product->tax) }}">

                                                @error('tax')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
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
                                                            @selected(old('tax_type', $product->tax_type) == $taxType->value)>
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
                                                    placeholder="Product notes">{{ old('notes', $product->notes) }}</textarea>

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
                        {{ __('Update') }}
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
    @include('products.partials.subcategory-script', ['selectedSubCategoryId' => old('sub_category_id', $product->sub_category_id)])
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
