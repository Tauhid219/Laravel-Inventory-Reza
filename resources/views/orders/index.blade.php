@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Orders')" subtitle="Manage the order workflow with product, category, and customer summaries.">
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

        @if (empty($orders) || (isset($orders) && $orders->isEmpty()))
            <x-empty title="{{ __('No orders found') }}"
                message="{{ __('You haven\'t created any orders yet. Start by adding your first order to manage your sales.') }}"
                button_label="{{ __('Add your first Order') }}" button_route="{{ route('orders.create') }}" />
        @else
            <livewire:tables.order-table />
        @endif
    </x-adminlte.page-body>
@endsection
