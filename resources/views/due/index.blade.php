@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Due Orders')" subtitle="Track outstanding balances and jump into customer payment follow-up.">
        <x-slot:actions>
            <a href="{{ route('orders.create') }}" class="btn btn-primary">
                {{ __('Create Order') }}
            </a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        @if ($orders->isEmpty())
            <x-empty
                title="No due orders found"
                message="All invoices are currently settled, or no due orders have been created yet."
                button_label="{{ __('Create your first order') }}"
                button_route="{{ route('orders.create') }}"
            />
        @else
            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Due Order List') }}
                    </x-slot:title>
                </x-slot:header>

                <div class="table-responsive">
                    <table class="table table-bordered card-table table-vcenter align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th class="text-center">{{ __('No.') }}</th>
                                <th class="text-center">{{ __('Invoice No.') }}</th>
                                <th class="text-center">{{ __('Customer') }}</th>
                                <th class="text-center">{{ __('Date') }}</th>
                                <th class="text-center">{{ __('Payment') }}</th>
                                <th class="text-center">{{ __('Paid') }}</th>
                                <th class="text-center">{{ __('Due') }}</th>
                                <th class="text-center">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    <td class="text-center">{{ $order->invoice_no }}</td>
                                    <td class="text-center">{{ $order->customer->name }}</td>
                                    <td class="text-center">{{ $order->order_date->format('d-m-Y') }}</td>
                                    <td class="text-center">{{ $order->payment_type }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">
                                            {{ Number::currency($order->pay, 'BDT') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">
                                            {{ Number::currency($order->due, 'BDT') }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <x-button.show class="btn-icon" route="{{ route('due.show', $order) }}" />
                                        <x-button.edit class="btn-icon" route="{{ route('due.edit', $order) }}" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <x-slot:footer>
                    {{ $orders->links() }}
                </x-slot:footer>
            </x-card>
        @endif
    </x-adminlte.page-body>
@endsection
