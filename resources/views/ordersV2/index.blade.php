@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Orders V2')" subtitle="Manage the updated order workflow with product, category, and customer summaries.">
        <x-slot:actions>
            <div class="btn-group">
                <a href="{{ route('ordersV2.pendingOrders') }}" class="btn btn-default">{{ __('Pending') }}</a>
                <a href="{{ route('ordersV2.completedOrders') }}" class="btn btn-default">{{ __('Completed') }}</a>
                <a href="{{ route('ordersV2.create') }}" class="btn btn-primary">{{ __('Add Order') }}</a>
            </div>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        <livewire:tables.order-v2-table />
    </x-adminlte.page-body>
@endsection
