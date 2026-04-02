@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Customer')" subtitle="Add a customer profile with the contact details used in sales and billing.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('customers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Customer Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('customers.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <div class="row row-cards">
                        <div class="col-md-12">
                            <x-input name="name" :required="true" />

                            <x-input name="email" label="Email address" :required="true" />
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <x-input label="Phone Number" name="phone" :required="true" />
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label required">
                                    {{ __('Address') }}
                                </label>

                                <textarea name="address" id="address" rows="3"
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

                    <x-button.back route="{{ route('customers.index') }}">
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
