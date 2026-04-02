@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Customers')" subtitle="Manage customer contacts, communication details, and party records.">
        <x-slot:actions>
            <a href="{{ route('customers.create') }}" class="btn btn-primary">{{ __('Add Customer') }}</a>
        </x-slot:actions>
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($customers->isEmpty())
            <x-empty title="No customers found"
                message="Create a customer to start tracking sales, contact information, and addresses."
                button_label="{{ __('Add your first Customer') }}" button_route="{{ route('customers.create') }}" />
        @else
            @livewire('tables.customer-table')
        @endif
    </x-adminlte.page-body>
@endsection
