@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Order Details')" subtitle="Review the updated order summary, item grouping, and completion status.">
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
                    <x-action.close route="{{ route('ordersV2.index') }}" />
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
                                <th scope="col" class="align-middle text-center">Product Name</th>
                                <th scope="col" class="align-middle text-center">Category</th>
                                <th scope="col" class="align-middle text-center">Sub Category</th>
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
                                        <span class="badge bg-blue-lt">{{ $item->product->name }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-purple-lt">{{ $item->product->category->name ?? 'N/A' }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-gray-lt">{{ $item->product->subCategory->name ?? 'N/A' }}</span>
                                    </td>
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
                                <td colspan="6" class="text-end">{{ __('Total') }}</td>
                                <td class="text-center">{{ number_format($order->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if ($order->note)
                    <div class="row mt-5">
                        <div class="col-md-12">
                            <div class="hr-text text-start text-secondary mb-3">
                                <span>{{ __('Order Note') }}</span>
                            </div>
                            <div class="card bg-light-lt border-0 shadow-none">
                                <div class="card-body p-3 text-secondary"
                                    style="white-space: pre-wrap; background-color: #f8f9fa; border-left: 4px solid #206bc4;">
                                    {{ $order->note }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </x-slot:content>

            @role('super-admin|admin')
                <x-slot:footer class="text-end">
                    @if ($order->order_status === \App\Enums\OrderStatus::PENDING)
                        <form action="{{ route('ordersV2.update', $order) }}" method="POST">
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
