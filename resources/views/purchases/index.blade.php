@extends('layouts.tabler')

@section('content')
    @php $isDemoMode = session('demo_mode', false); @endphp
    <x-adminlte.page-header :title="__('Purchases')" subtitle="Track purchase records, approval status, suppliers, and stock intake.">
        <x-slot:actions>
            <div class="btn-group">
                <a href="{{ route('purchases.pendingPurchases') }}" class="btn btn-default">{{ __('Pending') }}</a>
                <a href="{{ route('purchases.approvedPurchases') }}" class="btn btn-default">{{ __('Approved') }}</a>
                @unless ($isDemoMode)
                    <a href="{{ route('purchases.create') }}" class="btn btn-primary">{{ __('Add Purchase') }}</a>
                @endunless
            </div>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($purchases->isEmpty())
            <x-empty title="No purchases found"
                message="Create a purchase to start recording supplier orders and inventory intake."
                button_label="{{ __('Add your first Purchase') }}" button_route="{{ route('purchases.create') }}" />
        @else
            @livewire('tables.purchase-table')
        @endif
    </x-adminlte.page-body>
@endsection
