@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Edit Quotation')" subtitle="Update quotation details, customer selection, and quoted line items.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $quotation])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    @section('use_default_flash', '1')

    <x-adminlte.page-body>
        <x-alert />

        <div class="row row-cards">
            <div class="row">
                <div class="col">
                    <x-card>
                        <x-slot:header>
                            <x-slot:title>
                                {{ __('Products') }}
                            </x-slot:title>
                        </x-slot:header>

                        <div class="card-body">
                            <livewire:search-product />
                        </div>
                    </x-card>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <x-card>
                        <x-slot:header>
                            <x-slot:title>
                                {{ __('Quotation Details') }}
                            </x-slot:title>

                            <x-slot:actions>
                                <x-action.close route="{{ route('quotations.index') }}" />
                            </x-slot:actions>
                        </x-slot:header>

                        <div class="card-body">
                            <form action="{{ route('quotations.update', $quotation) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="row gx-3 mb-3">
                                    <div class="col">
                                        <label class="small mb-1" for="date">
                                            {{ __('Date') }}
                                            <span class="text-danger">*</span>
                                        </label>

                                        <input class="form-control @error('date') is-invalid @enderror" name="date"
                                            id="date" type="date"
                                            value="{{ old('date', $quotation->date->format('Y-m-d')) }}" required>

                                        @error('date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col">
                                        <label class="small mb-1" for="customer_id">
                                            {{ __('Customer') }}
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select class="form-select @error('customer_id') is-invalid @enderror" id="customer_id"
                                            name="customer_id">
                                            <option selected disabled>{{ __('Select a customer:') }}</option>

                                            @foreach ($customers as $customer)
                                                <option value="{{ $customer->id }}"
                                                    @selected(old('customer_id', $quotation->customer_id) == $customer->id)>
                                                    {{ $customer->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        @error('customer_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col">
                                        <label for="status" class="small mb-1">
                                            {{ __('Status') }}
                                            <span class="text-danger">*</span>
                                        </label>

                                        <select class="form-select" name="status" id="status" required>
                                            @foreach (\App\Enums\QuotationStatus::cases() as $status)
                                                <option value="{{ $status->value }}"
                                                    @selected(old('status', $quotation->status->value) == $status->value)>
                                                    {{ $status->label() }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col">
                                        <label for="reference" class="small mb-1">{{ __('Reference') }}</label>

                                        <input type="text" id="reference" name="reference" class="form-control"
                                            value="{{ old('reference', $quotation->reference) }}" readonly>

                                        @error('reference')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <livewire:product-cart :cartInstance="'quotation'" :data="$quotation" :key="'quotation-cart-'.$quotation->id" />

                                <div class="col-md-12 mt-4">
                                    <div class="form-group">
                                        <label for="note">{{ __('Notes') }}</label>
                                        <textarea name="note" id="note" rows="5" class="form-control">{{ old('note', $quotation->note) }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-4">
                                    <div class="d-flex flex-wrap">
                                        <button type="submit" class="btn btn-success add-list mx-1">
                                            {{ __('Update Quotation') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </x-card>
                </div>
            </div>
        </div>
    </x-adminlte.page-body>
@endsection
