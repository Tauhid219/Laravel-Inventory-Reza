@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Due Order Details')" subtitle="Review invoice items, payment progress, and remaining balance.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $order])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Due Order Details') }}
                </x-slot:title>

                <x-slot:actions>
                    <a href="{{ route('due.edit', $order) }}" class="btn btn-warning">
                        {{ __('Edit Order') }}
                    </a>
                    <x-action.close route="{{ route('due.index') }}" />
                </x-slot:actions>
            </x-slot:header>

            <x-slot:content>
                <div class="row row-cards mb-3">
                    <div class="col-md-3">
                        <label for="order_date" class="small mb-1">{{ __('Order Date') }}</label>
                        <input type="text" id="order_date" class="form-control" value="{{ $order->order_date->format('d-m-Y') }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label for="invoice_no" class="small mb-1">{{ __('Invoice No.') }}</label>
                        <input type="text" id="invoice_no" class="form-control" value="{{ $order->invoice_no }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label for="customer" class="small mb-1">{{ __('Customer') }}</label>
                        <input type="text" id="customer" class="form-control" value="{{ $order->customer->name }}" disabled>
                    </div>

                    <div class="col-md-3">
                        <label for="payment_type" class="small mb-1">{{ __('Payment Type') }}</label>
                        <input type="text" id="payment_type" class="form-control" value="{{ $order->payment_type }}" disabled>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">{{ __('No.') }}</th>
                                <th class="text-center">{{ __('Photo') }}</th>
                                <th class="text-center">{{ __('Product Name') }}</th>
                                <th class="text-center">{{ __('Product Code') }}</th>
                                <th class="text-center">{{ __('Quantity') }}</th>
                                <th class="text-center">{{ __('Price') }}</th>
                                <th class="text-center">{{ __('Total') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">
                                        <div style="max-height: 80px; max-width: 80px;">
                                            <img class="img-fluid" src="{{ $item->product->product_image ? asset('storage/products/' . $item->product->product_image) : asset('assets/img/products/default.webp') }}">
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->product->name }}</td>
                                    <td class="text-center">{{ $item->product->code }}</td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-center">{{ number_format($item->unitcost, 2) }}</td>
                                    <td class="text-center">{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-end">{{ __('Paid Amount') }}</td>
                                <td class="text-center">{{ number_format($order->pay, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end">{{ __('Due') }}</td>
                                <td class="text-center">{{ number_format($order->due, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end">{{ __('VAT') }}</td>
                                <td class="text-center">{{ number_format($order->vat, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end">{{ __('Total') }}</td>
                                <td class="text-center">{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-slot:content>

            <x-slot:footer class="text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-due">
                    {{ __('Pay Due') }}
                </button>
            </x-slot:footer>
        </x-card>
    </x-adminlte.page-body>

    @include('partials._modal_due', $order)
@endsection
