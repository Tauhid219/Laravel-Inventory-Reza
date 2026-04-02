@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Purchase Details')" subtitle="Review the purchase summary, supplier, and item-level breakdown.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $purchase])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Purchase Details') }}
                </x-slot:title>

                <x-slot:actions>
                    <x-action.close route="{{ route('purchases.index') }}" />
                </x-slot:actions>
            </x-slot:header>

            <x-slot:content>
                <div class="row row-cards mb-3">
                    <div class="col">
                        <label for="date" class="small mb-1">{{ __('Order Date') }}</label>
                        <input type="text" id="date" class="form-control"
                            value="{{ $purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : '' }}" disabled>
                    </div>

                    <div class="col">
                        <label for="purchase_no" class="small mb-1">{{ __('Purchase No.') }}</label>
                        <input type="text" id="purchase_no" class="form-control" value="{{ $purchase->purchase_no }}"
                            disabled>
                    </div>

                    <div class="col">
                        <label for="supplier" class="small mb-1">{{ __('Supplier') }}</label>
                        <input type="text" id="supplier" class="form-control" value="{{ $purchase->supplier->name }}"
                            disabled>
                    </div>

                    <div class="col">
                        <label for="create_by" class="small mb-1">{{ __('Created By') }}</label>
                        <input type="text" id="create_by" class="form-control"
                            value="{{ $purchase->createdBy->name ?? null }}" disabled>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="align-middle text-center">No.</th>
                                <th scope="col" class="align-middle text-center">Photo</th>
                                <th scope="col" class="align-middle text-center">Product Name</th>
                                <th scope="col" class="align-middle text-center">Product Code</th>
                                <th scope="col" class="align-middle text-center">Current Stock</th>
                                <th scope="col" class="align-middle text-center">Quantity</th>
                                <th scope="col" class="align-middle text-center">Price</th>
                                <th scope="col" class="align-middle text-center">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->details as $item)
                                <tr>
                                    <td class="align-middle text-center">{{ $loop->iteration }}</td>
                                    <td class="align-middle justify-content-center text-center">
                                        <div style="max-height: 80px; max-width: 80px;">
                                            <img class="img-fluid"
                                                src="{{ $item->product->product_image ? asset('storage/products/' . $item->product->product_image) : asset('assets/img/products/default.webp') }}">
                                        </div>
                                    </td>
                                    <td class="align-middle text-center">{{ $item->product->name }}</td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-indigo-lt">{{ $item->product->code }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-primary-lt">{{ $item->product->quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <span class="badge bg-primary-lt">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="align-middle text-center">{{ number_format($item->unitcost, 2) }}</td>
                                    <td class="align-middle text-center">{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td class="align-middle text-end" colspan="7">{{ __('Total') }}</td>
                                <td class="align-middle text-center">{{ number_format($purchase->total_amount, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </x-slot:content>
        </x-card>
    </x-adminlte.page-body>
@endsection
