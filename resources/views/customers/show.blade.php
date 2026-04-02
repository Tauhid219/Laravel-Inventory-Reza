@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="$customer->name" subtitle="Review customer contact information and saved address details.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $customer])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Customer Details') }}
                </x-slot:title>
            </x-slot:header>

            <div class="table-responsive">
                <table class="table table-bordered card-table table-vcenter text-nowrap datatable">
                    <tbody>
                        <tr>
                            <td>{{ __('Name') }}</td>
                            <td>{{ $customer->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Email address') }}</td>
                            <td>{{ $customer->email }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Phone number') }}</td>
                            <td>{{ $customer->phone }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('Address') }}</td>
                            <td>{{ $customer->address }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <x-slot:footer class="text-end">
                <x-button.edit route="{{ route('customers.edit', $customer) }}">
                    {{ __('Edit') }}
                </x-button.edit>

                <x-button.back route="{{ route('customers.index') }}">
                    {{ __('Cancel') }}
                </x-button.back>
            </x-slot:footer>
        </x-card>
    </x-adminlte.page-body>
@endsection
