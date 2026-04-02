@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Daily Purchase Report')" :subtitle="__('Purchases recorded on ') . today()->format('d-m-Y')">
        <x-slot:actions>
            <a href="{{ route('purchases.getPurchaseReport') }}" class="btn btn-default">{{ __('Export Range Report') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('List of Purchases') }}
                </x-slot:title>
            </x-slot:header>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col">No.</th>
                                    <th scope="col">Supplier Name</th>
                                    <th scope="col">Purchase No.</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Total Amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($purchases as $purchase)
                                <tr>
                                    <td scope="row">{{ $loop->iteration }}</td>
                                    <td>{{ $purchase->supplier->name }}</td>
                                    <td>{{ $purchase->purchase_no }}</td>
                                    <td>{{ $purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : 'N/A' }}</td>
                                    <td>{{ $purchase->total_amount }}</td>
                                    <td>
                                        @if ($purchase->status === \App\Enums\PurchaseStatus::APPROVED)
                                            <span class="btn btn-success">{{ __('Approved') }}</span>
                                        @else
                                            <span class="btn btn-warning">{{ __('Pending') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('purchases.show', $purchase->id) }}" class="btn btn-primary btn-sm">View Details</a>
                                    </td>
                                </tr>
                                @empty
                                    <tr>
                                        <td class="align-middle text-center" colspan="7">
                                            No results found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                </table>
            </div>
        </x-card>
    </x-adminlte.page-body>
@endsection
