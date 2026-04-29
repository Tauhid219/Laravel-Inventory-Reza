@extends('layouts.tabler')

@section('content')
    @php $isDemoMode = session('demo_mode', false); @endphp
    <x-adminlte.page-header :title="__('Pending Orders V2')" subtitle="Review updated-order records that still need completion.">
        <x-slot:actions>
            <div class="btn-group">
                <a href="{{ route('orders.index') }}" class="btn btn-default">{{ __('All Orders') }}</a>
                @unless ($isDemoMode)
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">{{ __('Add Order') }}</a>
                @endunless
            </div>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($orders->isEmpty())
            <x-empty title="No orders found"
                message="Create an order to start tracking pending entries in the updated workflow."
                button_label="{{ __('Add your first Order') }}" button_route="{{ route('orders.create') }}" />
        @else
            <x-card>
                <div class="table-responsive">
                    <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="text-center">{{ __('No.') }}</th>
                                    {{-- <th scope="col" class="text-center">{{ __('Invoice No.') }}</th> --}}
                                    <th scope="col" class="align-middle text-center">
                                        <a href="#" role="button">
                                            {{ __('Product') }}
                                        </a>
                                    </th>
                                    <th scope="col" class="align-middle text-center">
                                        <a href="#" role="button">
                                            {{ __('Category') }}
                                        </a>
                                    </th>
                                    <th scope="col" class="align-middle text-center">
                                        <a href="#" role="button">
                                            {{ __('Sub Category') }}
                                        </a>
                                    </th>
                                    <th scope="col" class="text-center">{{ __('Customer') }}</th>
                                    <th scope="col" class="text-center">{{ __('Date') }}</th>
                                    {{-- <th scope="col" class="text-center">{{ __('Payment') }}</th> --}}
                                    <th scope="col" class="text-center">{{ __('Total') }}</th>
                                    <th scope="col" class="text-center">{{ __('Status') }}</th>
                                    <th scope="col" class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr>
                                        <td class="text-center">
                                            {{ $loop->iteration }}
                                        </td>
                                        {{-- <td class="text-center">{{ $order->invoice_no }}</td> --}}

                                        {{-- Product Names --}}
                                        <td class="align-middle text-center">
                                            @foreach ($order->details as $detail)
                                                <span class="badge bg-blue-lt">{{ $detail->product->name }}</span><br>
                                            @endforeach
                                        </td>

                                        {{-- Category Names --}}
                                        <td class="align-middle text-center">
                                            @php
                                                $categories = $order->details
                                                    ->map(fn($d) => $d->product->category->name)
                                                    ->unique();
                                            @endphp
                                            @foreach ($categories as $catName)
                                                <span class="badge bg-purple-lt">{{ $catName }}</span><br>
                                            @endforeach
                                        </td>

                                        {{-- Sub-Category Names --}}
                                        <td class="align-middle text-center">
                                            @php
                                                $subCategories = $order->details
                                                    ->map(fn($d) => $d->product->subCategory->name ?? 'N/A')
                                                    ->unique();
                                            @endphp
                                            @foreach ($subCategories as $subCatName)
                                                <span class="badge bg-gray-lt">{{ $subCatName }}</span><br>
                                            @endforeach
                                        </td>
                                        <td class="text-center">{{ $order->customer->name }}</td>
                                        <td class="text-center">{{ $order->order_date->format('d-m-Y') }}</td>
                                        {{-- <td class="text-center">{{ $order->payment_type }}</td> --}}
                                        <td class="text-center">{{ Number::currency($order->total, 'BDT') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-orange text-white text-uppercase">
                                                {{ \App\Enums\OrderStatus::PENDING->label() }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('orders.show', $order) }}"
                                                class="btn btn-icon btn-outline-success">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="icon icon-tabler icon-tabler-eye" width="24"
                                                    height="24" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor" fill="none" stroke-linecap="round"
                                                    stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                                    <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                                    <path
                                                        d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                    </table>
                </div>
            </x-card>
        @endif
    </x-adminlte.page-body>
@endsection
