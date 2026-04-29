@extends('layouts.tabler')

@section('content')
    @php $isDemoMode = session('demo_mode', false); @endphp
    <x-adminlte.page-header :title="__('Purchase Details')" subtitle="Review supplier information, approval state, and purchased product lines.">
        <x-slot:breadcrumbs>
            @include('partials._breadcrumbs', ['model' => $purchase])
        </x-slot:breadcrumbs>
    </x-adminlte.page-header>

    <x-adminlte.page-body>
        <x-card class="mb-4">
            <x-slot:header>
                <x-slot:title>
                    {{ __('Supplier Information') }}
                </x-slot:title>
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
                        <label class="small mb-1">{{ __('Purchase Date') }}</label>
                        <div class="form-control form-control-solid">
                            {{ $purchase->date ? $purchase->date->format('d-m-Y') : 'N/A' }}
                        </div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('No Purchase') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->purchase_no }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Total') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->total_amount }}</div>
                    </div>
                </div>
                <div class="row gx-3 mb-3">
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Created By') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->createdBy ? $purchase->createdBy->name : '-' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="small mb-1">{{ __('Updated By') }}</label>
                        <div class="form-control form-control-solid">{{ $purchase->updatedBy ? $purchase->updatedBy->name : '-' }}</div>
                    </div>
                </div>
                <div class="mb-0">
                    <label class="small mb-1">{{ __('Address') }}</label>
                    <div class="form-control form-control-solid">{{ $purchase->supplier->address }}</div>
                </div>
            </x-slot:content>

            <x-slot:footer class="text-end">
                @can('update purchase')
                    @unless ($isDemoMode)
                    @if ($purchase->status == \App\Enums\PurchaseStatus::PENDING)
                        <form action="{{ route('purchases.update', $purchase) }}" method="POST">
                            @csrf
                            @method('put')
                            <input type="hidden" name="id" value="{{ $purchase->id }}">
                            <button type="submit" class="btn btn-success"
                                onclick="return confirm('Are you sure you want to approve this purchase?')">
                                {{ __('Approve Purchase') }}
                            </button>
                            <a class="btn btn-default" href="{{ URL::previous() }}">{{ __('Back') }}</a>
                        </form>
                    @else
                        <a class="btn btn-default" href="{{ URL::previous() }}">{{ __('Back') }}</a>
                    @endif
                    @else
                        <a class="btn btn-default" href="{{ URL::previous() }}">{{ __('Back') }}</a>
                    @endunless
                @endcan
            </x-slot:footer>
        </x-card>

        <x-card>
            <x-slot:header>
                <x-slot:title>
                    {{ __('Purchased Products') }}
                </x-slot:title>
            </x-slot:header>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Product Code</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Price</th>
                            <th scope="col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td scope="row">{{ $loop->iteration }}</td>
                                <td scope="row">{{ $product->product->name }}</td>
                                <td scope="row">{{ $product->product->code }}</td>
                                <td scope="row"><span class="btn btn-success">{{ $product->quantity }}</span></td>
                                <td scope="row">{{ $product->unitcost }}</td>
                                <td scope="row">
                                    <span class="btn btn-primary">{{ $product->total }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>
    </x-adminlte.page-body>
@endsection
