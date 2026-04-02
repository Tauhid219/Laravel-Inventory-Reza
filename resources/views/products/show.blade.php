@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Product Details')">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $product])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Product Details') }}
                </x-slot:title>
            </x-slot:header>

            <div class="table-responsive">
                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                    <tbody>
                                        <tr>
                                            <td>Name</td>
                                            <td>{{ $product->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Slug</td>
                                            <td>{{ $product->slug }}</td>
                                        </tr>
                                        <tr>
                                            <td><span class="text-secondary">Code</span></td>
                                            <td>{{ $product->code }}</td>
                                        </tr>
                                        <tr>
                                            <td>Barcode</td>
                                            <td>{!! $barcode !!}</td>
                                        </tr>
                                        <tr>
                                            <td>Category</td>
                                            <td>
                                                <a href="{{ route('categories.show', $product->category) }}"
                                                    class="badge bg-blue-lt">
                                                    {{ $product->category->name ?? 'N/A' }}
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Sub Category</td>
                                            <td>
                                                @if ($product->subCategory)
                                                    <a href="{{ route('sub-categories.show', $product->subCategory) }}"
                                                        class="badge bg-blue-lt">
                                                        {{ $product->subCategory->name ?? 'N/A' }}
                                                    </a>
                                                @else
                                                    <span class="badge bg-red-lt">No Sub Category</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Unit</td>
                                            <td>
                                                @if ($product->unit)
                                                    <a href="{{ route('units.show', $product->unit) }}"
                                                        class="badge bg-blue-lt">
                                                        {{ $product->unit->name }}
                                                    </a>
                                                @else
                                                    <span class="badge bg-secondary-lt">N/A</span>
                                                @endif
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Quantity</td>
                                            <td>{{ $product->quantity }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantity Alert</td>
                                            <td>
                                                <span class="badge bg-red-lt">
                                                    {{ $product->quantity_alert }}
                                                </span>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Price</td>
                                            <td>{{ $product->buying_price }}</td>
                                        </tr>
                                        {{-- <tr>
                                            <td>Selling Price</td>
                                            <td>{{ $product->selling_price }}</td>
                                        </tr>
                                        <tr>
                                            <td>Tax</td>
                                            <td>
                                                <span class="badge bg-red-lt">
                                                    {{ $product->tax }} %
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Tax Type</td>
                                            <td>{{ $product->tax_type->label() }}</td>
                                        </tr> --}}
                                        <tr>
                                            <td>{{ __('Notes') }}</td>
                                            <td>{{ $product->notes }}</td>
                                        </tr>
                    </tbody>
                </table>
            </div>

            <x-slot:footer class="text-end">
                <x-button.edit route="{{ route('products.edit', $product) }}">
                    {{ __('Edit') }}
                </x-button.edit>

                <x-button.back route="{{ route('products.index') }}">
                    {{ __('Cancel') }}
                </x-button.back>
            </x-slot:footer>
        </x-card>
    </x-adminlte.page-body>
@endsection
