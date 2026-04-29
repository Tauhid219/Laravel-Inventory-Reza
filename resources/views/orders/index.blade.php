@extends('layouts.tabler')

@section('content')
    @php $isDemoMode = session('demo_mode', false); @endphp
    <x-adminlte.page-header :title="__('Orders')" subtitle="Manage the order workflow with product, category, and customer summaries.">
        <x-slot:actions>
            <div class="btn-group">
                <a href="{{ route('orders.pending') }}" class="btn btn-default">{{ __('Pending') }}</a>
                <a href="{{ route('orders.complete') }}" class="btn btn-default">{{ __('Completed') }}</a>
                @unless ($isDemoMode)
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">{{ __('Add Order') }}</a>
                @endunless
            </div>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        <livewire:tables.order-table />
    </x-adminlte.page-body>
@endsection
