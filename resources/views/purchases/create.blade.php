@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Create Purchase')" subtitle="Create a new supplier purchase and capture incoming stock details.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('purchases.store') }}" method="POST">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Purchase Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('purchases.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-4">
                            <label for="date" class="form-label required">
                                {{ __('Purchase Date') }}
                            </label>

                            <input name="date" id="date" type="date"
                                class="form-control example-date-input @error('date') is-invalid @enderror"
                                value="{{ old('date') ?? now()->format('Y-m-d') }}" required>

                            @error('date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <x-tom-select label="Suppliers" id="supplier_id" name="supplier_id"
                            placeholder="Select Supplier" :data="$suppliers" />
                    </div>

                    @livewire('purchase-form')
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <button type="submit" class="btn btn-primary">
                        {{ __('Purchase') }}
                    </button>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
