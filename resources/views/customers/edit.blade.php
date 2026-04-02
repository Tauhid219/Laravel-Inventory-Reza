@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Customer')" subtitle="Update customer information without changing the existing sales flow.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $customer])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('customers.update', $customer) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')

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
                            <x-input name="name" :value="old('name', $customer->name)" :required="true" />

                            <x-input label="Email address" name="email" :value="old('email', $customer->email)"
                                :required="true" />
                        </div>

                        <div class="col-sm-6 col-md-6">
                            <x-input label="Phone number" name="phone" :value="old('phone', $customer->phone)"
                                :required="true" />
                        </div>

                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="address" class="form-label required">
                                    {{ __('Address') }}
                                </label>

                                <textarea id="address" name="address" rows="3"
                                    class="form-control @error('address') is-invalid @enderror">{{ old('address', $customer->address) }}</textarea>

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
                        {{ __('Update') }}
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
