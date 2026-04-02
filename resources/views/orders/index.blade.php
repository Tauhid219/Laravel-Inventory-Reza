@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Orders')" subtitle="Manage invoices, order activity, and completion flow for customer sales.">
        <x-slot:actions>
            <div class="btn-group">
                <a href="{{ route('orders.pending') }}" class="btn btn-default">{{ __('Pending') }}</a>
                <a href="{{ route('orders.complete') }}" class="btn btn-default">{{ __('Completed') }}</a>
                <a href="{{ route('orders.create') }}" class="btn btn-primary">{{ __('Add Order') }}</a>
            </div>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        <livewire:tables.order-table />
    </x-adminlte.page-body>
@endsection
