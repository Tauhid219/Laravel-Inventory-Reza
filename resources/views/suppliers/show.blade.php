@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="$supplier->name" subtitle="Review supplier contact, shop, and vendor type information.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $supplier])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Supplier Details') }}
                </x-slot:title>
            </x-slot:header>

            <div class="table-responsive">
                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                    <tbody>
                        <tr>
                            <td>{{ __('Name') }}</td>
                            <td>{{ $supplier->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Email address') }}</td>
                            <td>{{ $supplier->email }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Phone number') }}</td>
                            <td>{{ $supplier->phone }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Address') }}</td>
                            <td>{{ $supplier->address }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Shop name') }}</td>
                            <td>{{ $supplier->shopname }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Type') }}</td>
                            <td>{{ $supplier->type?->label() ?? __('Not set') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <x-slot:footer class="text-end">
                <x-button.edit route="{{ route('suppliers.edit', $supplier) }}">
                    {{ __('Edit') }}
                </x-button.edit>
                <x-button.back route="{{ route('suppliers.index') }}">
                    {{ __('Back') }}
                </x-button.back>
            </x-slot:footer>
        </x-card>
    </x-adminlte.page-body>
@endsection
