@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Order')" subtitle="Create a customer invoice and add products from the order cart.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('invoice.create') }}" method="POST">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Order Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('orders.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label required">
                                {{ __('Order Date') }}
                            </label>

                            <input name="date" id="date" type="date"
                                class="form-control example-date-input @error('date') is-invalid @enderror"
                                value="{{ old('date') ?? now()->format('Y-m-d') }}" required>

                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <x-tom-select label="Customers" id="customer_id" name="customer_id"
                            placeholder="Select Customer" :data="$customers" />
                    </div>

                    <livewire:order-form :cart-instance="'order'" />

                    <div class="row gx-3 mb-3">
                        <div class="col-md-12">
                            <label for="note" class="form-label">
                                {{ __('Note (Optional)') }}
                            </label>
                            <textarea name="note" id="note" class="form-control" rows="4"
                                placeholder="Enter any notes or comments here...">{{ old('note') }}</textarea>

                            @error('note')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Create Invoice') }}
                    </button>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
