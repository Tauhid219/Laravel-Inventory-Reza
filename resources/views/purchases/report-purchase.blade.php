@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Purchase Report')" subtitle="Choose a date range to export purchase activity and totals.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs')
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <form action="{{ route('purchases.exportPurchaseReport') }}" method="POST">
            @csrf

            <x-card>
                <x-slot:header>
                    <x-slot:title>
                        {{ __('Purchase Report Details') }}
                    </x-slot:title>

                    <x-slot:actions>
                        <x-action.close route="{{ route('purchases.index') }}" />
                    </x-slot:actions>
                </x-slot:header>

                <x-slot:content>
                    <div class="row gx-3 mb-3">
                        <div class="col-md-6">
                            <label class="small my-1" for="start_date">{{ __('Start Date') }} <span class="text-danger">*</span></label>
                            <input class="form-control example-date-input @error('start_date') is-invalid @enderror"
                                name="start_date" id="start_date" type="date" value="{{ old('start_date') }}">
                            @error('start_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="small my-1" for="end_date">{{ __('End Date') }} <span class="text-danger">*</span></label>
                            <input class="form-control example-date-input @error('end_date') is-invalid @enderror"
                                name="end_date" id="end_date" type="date" value="{{ old('end_date') }}">
                            @error('end_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                </x-slot:content>

                <x-slot:footer class="text-end">
                    <button class="btn btn-primary" type="submit">{{ __('Export Report') }}</button>
                    <a class="btn btn-default" href="{{ URL::previous() }}">{{ __('Cancel') }}</a>
                </x-slot:footer>
            </x-card>
        </form>
    </x-adminlte.page-body>
@endsection
