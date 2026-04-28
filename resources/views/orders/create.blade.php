@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Order')" subtitle="Create an order with the canonical itemized order builder.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('orders.store') }}" method="POST">
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

                    @livewire('order-form')

                    <div class="row mt-4 gx-3">
                        <div class="col-md-4">
                            <label for="payment_type" class="form-label required">
                                {{ __('Payment Type') }}
                            </label>

                            <select name="payment_type" id="payment_type"
                                class="form-select @error('payment_type') is-invalid @enderror" required
                                onchange="document.getElementById('pay').disabled = this.value === 'due'; if(this.value === 'due') document.getElementById('pay').value = '';">
                                <option value="cash" @selected(old('payment_type', 'cash') === 'cash')>{{ __('Cash') }}</option>
                                <option value="cheque" @selected(old('payment_type') === 'cheque')>{{ __('Cheque') }}</option>
                                <option value="due" @selected(old('payment_type') === 'due')>{{ __('Due') }}</option>
                            </select>

                            @error('payment_type')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="pay" class="form-label required">
                                {{ __('Paid Amount') }}
                            </label>

                            <input name="pay" id="pay" type="number"
                                class="form-control @error('pay') is-invalid @enderror"
                                value="{{ old('pay') }}" min="0" step="0.01"
                                placeholder="{{ __('Enter the amount received') }}" required>

                            @error('pay')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <label for="note" class="form-label">
                                {{ __('Note (Optional)') }}
                            </label>
                            <textarea name="note" id="note" class="form-control @error('note') is-invalid @enderror" rows="3"
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
                        {{ __('Create Order') }}
                    </button>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
