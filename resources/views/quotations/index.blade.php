@extends('layouts.tabler')

@section('content')
    @php $isDemoMode = session('demo_mode', false); @endphp
    <x-adminlte.page-header :title="__('Quotations')" subtitle="Manage draft quotations and review customer proposals.">
        @unless ($isDemoMode)
            <x-slot:actions>
                <a href="{{ route('quotations.create') }}" class="btn btn-primary">
                    {{ __('Add Quotation') }}
                </a>
            </x-slot:actions>
        @endunless
    </x-adminlte.page-header>

    <x-adminlte.page-body container-class="container container-xl">
        <x-alert />

        @if ($quotations->isEmpty())
            <x-empty
                title="No quotations found"
                message="Create a quotation to start preparing customer-ready pricing proposals."
                button_label="{{ __('Add your first quotation') }}"
                button_route="{{ route('quotations.create') }}"
            />
        @else
            @livewire('tables.quotation-table')
        @endif
    </x-adminlte.page-body>
@endsection
