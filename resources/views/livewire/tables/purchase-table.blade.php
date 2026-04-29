@php $isDemoMode = session('demo_mode', false); @endphp
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2">
        <div>
            <h3 class="card-title">
                {{ __('Purchases') }}
            </h3>
        </div>

        <div class="card-tools d-flex align-items-center gap-2">
            @unless ($isDemoMode)
                <x-action.create route="{{ route('purchases.create') }}" />
            @endunless
        </div>
    </div>

    <div class="card-body border-bottom py-3">
        <div class="d-flex">
            <div class="text-secondary">
                Show
                <div class="mx-2 d-inline-block">
                    <select wire:model.live="perPage" class="form-select form-select-sm" aria-label="result per page">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="15">15</option>
                        <option value="25">25</option>
                    </select>
                </div>
                entries
            </div>
            <div class="ms-auto text-secondary">
                Search:
                <div class="ms-2 d-inline-block">
                    <input type="text" wire:model.live="search" class="form-control form-control-sm"
                        aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner />

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
                <tr>
                    <th class="align-middle text-center w-1">
                        {{ __('No.') }}
                    </th>
                    {{-- <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('purchase_no')" href="#" role="button">
                            {{ __('Purchase No.') }}
                            @include('inclues._sort-icon', ['field' => 'purchase_no'])
                        </a>
                    </th> --}}
                    <th scope="col" class="align-middle text-center">
                        <a href="#" role="button">
                            {{ __('Product') }}
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a href="#" role="button">
                            {{ __('Category') }}
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a href="#" role="button">
                            {{ __('Sub Category') }}
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('supplier_id')" href="#" role="button">
                            {{ __('Supplier') }}
                            @include('inclues._sort-icon', ['field' => 'supplier_id'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('date')" href="#" role="button">
                            {{ __('Date') }}
                            @include('inclues._sort-icon', ['field' => 'date'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('total_amount')" href="#" role="button">
                            {{ __('Total') }}
                            @include('inclues._sort-icon', ['field' => 'total_amount'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('status')" href="#" role="button">
                            {{ __('Status') }}
                            @include('inclues._sort-icon', ['field' => 'status'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Action') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @if ($purchases->isEmpty())
                    <tr>
                        <td class="align-middle text-center" colspan="7">
                            No results found
                        </td>
                    </tr>
                @else
                    @foreach ($purchases as $purchase)
                        <tr>
                            <td class="align-middle text-center">
                                {{ $loop->iteration }}
                            </td>
                            {{-- <td class="align-middle text-center">
                                {{ $purchase->purchase_no }}
                            </td> --}}

                            {{-- Product Names --}}
                            <td class="align-middle text-center">
                                @foreach ($purchase->details as $detail)
                                    <span class="badge bg-blue-lt">{{ $detail->product->name }}</span><br>
                                @endforeach
                            </td>

                            {{-- Category Names --}}
                            <td class="align-middle text-center">
                                @php
                                    $categories = $purchase->details->map(fn($d) => $d->product->category->name)->unique();
                                @endphp
                                @foreach ($categories as $catName)
                                    <span class="badge bg-purple-lt">{{ $catName }}</span><br>
                                @endforeach
                            </td>

                            {{-- Sub-Category Names --}}
                            <td class="align-middle text-center">
                                @php
                                    $subCategories = $purchase->details
                                        ->map(fn($d) => $d->product->subCategory->name ?? 'N/A')
                                        ->unique();
                                @endphp
                                @foreach ($subCategories as $subCatName)
                                    <span class="badge bg-gray-lt">{{ $subCatName }}</span><br>
                                @endforeach
                            </td>
                            <td class="align-middle">
                                {{ $purchase->supplier->name }}
                            </td>
                            <td class="align-middle text-center">
                                {{ $purchase->date->format('d-m-Y') }}
                            </td>
                            <td class="align-middle text-center">
                                {{ safe_currency($purchase->total_amount, 'BDT') }}
                            </td>

                            <td class="align-middle text-center">
                                @if ($purchase->status === \App\Enums\PurchaseStatus::APPROVED)
                                    <span class="badge bg-green text-white text-uppercase">
                                        {{ $purchase->status->label() }}
                                    </span>
                                @else
                                    <span class="badge bg-orange text-white text-uppercase">
                                        {{ $purchase->status->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="align-middle text-center" style="width: 5%">
                                <x-button.show class="btn-icon" route="{{ route('purchases.show', $purchase) }}" />
                                {{-- <x-button.edit class="btn-icon" route="{{ route('purchases.edit', $purchase) }}" /> --}}
                                @if (! $isDemoMode)
                                    <x-button.delete class="btn-icon" route="{{ route('purchases.destroy', $purchase) }}"
                                        onclick="return confirm('Are you sure you want to delete this purchase?')" />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $purchases->firstItem() }}</span>
            to <span>{{ $purchases->lastItem() }}</span> of <span>{{ $purchases->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $purchases->links() }}
        </ul>
    </div>
</div>
