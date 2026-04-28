@extends('layouts.tabler')

@section('content')
    <x-adminlte.page-header :title="__('Purchase Approval')" subtitle="Review supplier information and approve pending purchases when ready.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $purchase])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-alert />

        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Purchase Summary') }}
                </x-slot:title>

                <x-slot:actions>
                    <x-action.close route="{{ route('purchases.index') }}" />
                </x-slot:actions>
            </x-slot:header>

            <x-slot:content>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Name') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->supplier->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Email') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->supplier->email }}</div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Phone') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->supplier->phone }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Order Date') }}</label>
                        <div class="form-control form-control-solid">
                            {{ $purchase->purchase_date ? $purchase->purchase_date->format('d-m-Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('No Purchase') }}</label>
                        <div class="form-control">{{ $purchase->purchase_no }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Total') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->total_amount }}</div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Created By') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->createdBy->name ?? '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Updated By') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->updatedBy->name ?? '-' }}</div>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="small mb-1">{{ __('Address') }}</label>
                    <div class="form-control form-control-solid">{{ $purchase->supplier->address }}</div>
                </div>
            </x-slot:content>

            <x-slot:footer class="text-end">
                @can('update purchase')
                    @if ($purchase->status === \App\Enums\PurchaseStatus::PENDING)
                        <form action="{{ route('purchases.update', $purchase) }}" method="POST">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id" value="{{ $purchase->id }}">

                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Are you sure you want to approve this purchase?')">
                                {{ __('Approve Purchase') }}
                            </button>
                        </form>
                    @endif
                @endcan
            </x-slot:footer>
        </x-card>
    </x-adminlte.page-body>
@endsection
