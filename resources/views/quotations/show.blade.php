@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Quotation Details')" subtitle="Review quoted products, customer information, and saved totals.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $quotation])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Quotation Details') }}
                </x-slot:title>

                <x-slot:actions>
                    <x-button.edit route="{{ route('quotations.edit', $quotation) }}">
                        {{ __('Edit') }}
                    </x-button.edit>
                    <x-action.close route="{{ route('quotations.index') }}" />
                </x-slot:actions>
            </x-slot:header>

            <x-slot:content>
                <div class="row row-cards mb-3">
                    <div class="col-md-3">
                        <label for="date" class="small mb-1">{{ __('Date') }}</label>
                        <input type="text" id="date" class="form-control" value="{{ $quotation->date->format('d-m-Y') }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label for="reference" class="small mb-1">{{ __('Reference ID') }}</label>
                        <input type="text" id="reference" class="form-control" value="{{ $quotation->reference }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label for="customer_name" class="small mb-1">{{ __('Customer Name') }}</label>
                        <input type="text" id="customer_name" class="form-control" value="{{ $quotation->customer_name }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="small mb-1">{{ __('Status') }}</label>
                        <input type="text" id="status" class="form-control" value="{{ $quotation->status->label() }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="tax_percentage" class="small mb-1">{{ __('Tax %') }}</label>
                        <input type="text" id="tax_percentage" class="form-control" value="{{ $quotation->tax_percentage }}" disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="discount_percentage" class="small mb-1">{{ __('Discount %') }}</label>
                        <input type="text" id="discount_percentage" class="form-control" value="{{ $quotation->discount_percentage }}" disabled>
                    </div>

                    <div class="col-12">
                        <label for="note" class="small mb-1">{{ __('Note') }}</label>
                        <textarea name="note" id="note" rows="3" class="form-control" disabled>{{ $quotation->note }}</textarea>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">{{ __('No.') }}</th>
                                <th class="text-center">{{ __('Photo') }}</th>
                                <th class="text-center">{{ __('Product Name') }}</th>
                                <th class="text-center">{{ __('Product Code') }}</th>
                                <th class="text-center">{{ __('Current Stock') }}</th>
                                <th class="text-center">{{ __('Quantity') }}</th>
                                <th class="text-center">{{ __('Net Unit Price') }}</th>
                                <th class="text-center">{{ __('Sub Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($quotation->quotationDetails as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <div style="max-height: 80px; max-width: 80px;">
                                            <img class="img-fluid"
                                                src="{{ $item->product && $item->product->product_image ? asset('storage/products/' . $item->product->product_image) : asset('assets/img/products/default.webp') }}">
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->product_name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-indigo-lt">{{ $item->product_code }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-lt">{{ $item->product->quantity ?? 0 }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-lt">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="text-center">{{ number_format($item->unit_price, 2) }}</td>
                                    <td class="text-center">{{ number_format($item->sub_total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="text-end" colspan="7">{{ __('Tax') }}</td>
                                <td class="text-center">{{ number_format($quotation->tax_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end" colspan="7">{{ __('Shipping') }}</td>
                                <td class="text-center">{{ number_format($quotation->shipping_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end" colspan="7">{{ __('Discount') }}</td>
                                <td class="text-center">{{ number_format($quotation->discount_amount, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="text-end" colspan="7">{{ __('Total') }}</td>
                                <td class="text-center">{{ number_format($quotation->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-slot:content>
        </x-card>
    </x-adminlte.page-body>
@endsection
