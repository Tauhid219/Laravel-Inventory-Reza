@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Order Details')" subtitle="Review invoice information, ordered products, and completion status.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $order])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
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
                <div class="row row-cards mb-3">
                    <div class="col">
                        <label for="order_date" class="form-label required">{{ __('Order Date') }}</label>
                        <input type="text" id="order_date" class="form-control"
                            value="{{ $order->order_date->format('d-m-Y') }}" disabled>
                    </div>

                    <div class="col">
                        <label for="invoice_no" class="form-label required">{{ __('Invoice No.') }}</label>
                        <input type="text" id="invoice_no" class="form-control" value="{{ $order->invoice_no }}" disabled>
                    </div>

                    <div class="col">
                        <label for="customer" class="form-label required">{{ __('Customer') }}</label>
                        <input type="text" id="customer" class="form-control" value="{{ $order->customer->name }}" disabled>
                    </div>

                    <div class="col">
                        <label for="payment_type" class="form-label required">{{ __('Payment Type') }}</label>
                        <input type="text" id="payment_type" class="form-control" value="{{ $order->payment_type }}"
                            disabled>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="align-middle text-center">No.</th>
                                <th scope="col" class="align-middle text-center">Photo</th>
                                <th scope="col" class="align-middle text-center">Product Name</th>
                                <th scope="col" class="align-middle text-center">Product Code</th>
                                <th scope="col" class="align-middle text-center">Quantity</th>
                                <th scope="col" class="align-middle text-center">Price</th>
                                <th scope="col" class="align-middle text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->details as $item)
                                <tr>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle text-center">
                                        <div style="max-height: 80px; max-width: 80px;">
                                            <img class="img-fluid"
                                                src="{{ $item->product->product_image ? asset('storage/products/' . $item->product->product_image) : asset('assets/img/products/default.webp') }}">
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">{{ $item->product->name }}</td>
                                    <td class="align-middle text-center">{{ $item->product->code }}</td>
                                    <td class="align-middle text-center">{{ $item->quantity }}</td>
                                    <td class="align-middle text-center">{{ number_format($item->unitcost, 2) }}</td>
                                    <td class="align-middle text-center">{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="6" class="text-end">{{ __('Payed amount') }}</td>
                                <td class="text-center">{{ number_format($order->pay, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end">{{ __('Due') }}</td>
                                <td class="text-center">{{ number_format($order->due, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="text-end">{{ __('Total') }}</td>
                                <td class="text-center">{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row gx-3 mb-0">
                    <div class="col-md-12">
                        <label for="note" class="form-label">{{ __('Note') }}</label>
                        <textarea class="form-control" rows="4" disabled>{{ $note }}</textarea>
                    </div>
                </div>
            </x-slot:content>

            @role('super-admin|admin')
                <x-slot:footer class="text-end">
                    @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                        <form action="{{ route('orders.update', $order) }}" method="POST">
                            @method('put')
                            @csrf

                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Are you sure you want to complete this order?')">
                                {{ __('Complete Order') }}
                            </button>
                        </form>
                    @endif
                </x-slot:footer>
            @endrole
        </x-card>
    </x-adminlte.page-body>
@endsection
