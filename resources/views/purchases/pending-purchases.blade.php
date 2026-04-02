@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Pending Purchases')" subtitle="Review purchase records waiting for approval before stock is posted.">
        <x-slot:actions>
            <div class="btn-group">
                <a href="{{ route('purchases.index') }}" class="btn btn-default">{{ __('All Purchases') }}</a>
                <a href="{{ route('purchases.create') }}" class="btn btn-primary">{{ __('Add Purchase') }}</a>
            </div>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        <x-card>
            <div class="table-responsive">
                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                        <thead class="thead-light">
                            <tr>
                                <th scope="col" class="align-middle text-center w-1">No.</th>
                                {{-- <th scope="col" class="align-middle text-center">Purchase</th> --}}
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
                                <th scope="col" class="align-middle text-center">Supplier</th>
                                <th scope="col" class="align-middle text-center">Date</th>
                                <th scope="col" class="align-middle text-center">Total</th>
                                <th scope="col" class="align-middle text-center">Status</th>
                                <th scope="col" class="align-middle text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $purchase)
                                <tr>
                                    <td class="align-middle text-center">
                                        {{ $loop->iteration }}
                                    </td>
                                    {{-- <td class="align-middle text-center">
                                        {{ $purchase->purchase_no }}
                                    </td> --}}

                                    {{-- Product Names --}}
                                    <td class="align-middle text-center">
                                        @foreach ($purchase->details as $detail)
                                            <span class="badge bg-blue-lt">{{ $detail->product->name }}</span><br>
                                        @endforeach
                                    </td>

                                    {{-- Category Names --}}
                                    <td class="align-middle text-center">
                                        @php
                                            $categories = $purchase->details
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
                                            $subCategories = $purchase->details
                                                ->map(fn($d) => $d->product->subCategory->name ?? 'N/A')
                                                ->unique();
                                        @endphp
                                        @foreach ($subCategories as $subCatName)
                                            <span class="badge bg-gray-lt">{{ $subCatName }}</span><br>
                                        @endforeach
                                    </td>
                                    <td class="align-middle">
                                        {{ $purchase->supplier->name }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ $purchase->date ? $purchase->date->format('d-m-Y') : 'N/A' }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{ Number::currency($purchase->total_amount, 'BDT') }}
                                    </td>
                                    <td class="align-middle text-center">
                                        {{-- <span
                                            class="btn btn-{{ $purchase->purchase_status == 0 ? 'warning' : 'success' }} btn-sm text-uppercase">{{ $purchase->purchase_status == 0 ? 'pending' : 'approved' }}</span> --}}
                                        <span
                                            class="btn btn-{{ $purchase->status == \App\Enums\PurchaseStatus::PENDING ? 'warning' : 'success' }} btn-sm text-uppercase">
                                            {{ $purchase->status == \App\Enums\PurchaseStatus::PENDING ? 'pending' : 'approved' }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <a href="{{ route('purchases.show', $purchase) }}"
                                            class="btn btn-icon btn-outline-info">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye"
                                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
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
    </x-adminlte.page-body>
@endsection
