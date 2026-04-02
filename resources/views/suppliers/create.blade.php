@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Supplier')" subtitle="Add a supplier profile with shop, contact, and type information.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('suppliers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Supplier Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('suppliers.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <div class="row row-cards">
                        <div class="col-md-12">
                            <x-input name="name" :required="true" />

                            <x-input name="email" label="Email address" :required="true" />

                            <x-input name="shopname" label="Shop name" :required="true" />

                            <x-input name="phone" label="Phone number" :required="true" />
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label required">
                                    {{ __('Type of supplier') }}
                                </label>

                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                    <option selected disabled>{{ __('Select a type:') }}</option>

                                    @foreach (\App\Enums\SupplierType::cases() as $supplierType)
                                        <option value="{{ $supplierType->value }}" @selected(old('type') == $supplierType->value)>
                                            {{ $supplierType->label() }}
                                        </option>
                                    @endforeach
                                </select>

                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    {{ __('Address') }}
                                </label>

                                <textarea id="address" name="address" rows="3"
                                    class="form-control @error('address') is-invalid @enderror">{{ old('address') }}</textarea>

                                @error('address')
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

                    <x-button.back route="{{ route('suppliers.index') }}">
                        {{ __('Cancel') }}
                    </x-button.back>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection

@pushonce('page-scripts')
    <script src="{{ asset('assets/js/img-preview.js') }}"></script>
@endpushonce
