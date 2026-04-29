@php $isDemoMode = session('demo_mode', false); @endphp
<div class="card">
    <div class="card-header d-flex align-items-center justify-content-between gap-2">
        <div>
            <h3 class="card-title">
                {{ __('Quotations') }}
            </h3>
        </div>

        <div class="card-tools d-flex align-items-center gap-2">
            @can('create quotation')
                @unless ($isDemoMode)
                <x-action.create route="{{ route('quotations.create') }}" />
                @endunless
            @endcan
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
                    <input type="text" wire:model.live="search" class="form-control form-control-sm" aria-label="Search invoice">
                </div>
            </div>
        </div>
    </div>

    <x-spinner.loading-spinner/>

    <div class="table-responsive">
        <table wire:loading.remove class="table table-bordered card-table table-vcenter text-nowrap datatable">
            <thead class="thead-light">
            <tr>
                <th class="align-middle text-center w-1">
                    {{ __('No.') }}
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('reference')" href="#" role="button">
                        {{ __('Quotation No.') }}
                        @include('inclues._sort-icon', ['field' => 'reference'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('date')" href="#" role="button">
                        {{ __('Date') }}
                        @include('inclues._sort-icon', ['field' => 'date'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('customer_name')" href="#" role="button">
                        {{ __('Customer name') }}
                        @include('inclues._sort-icon', ['field' => 'customer_name'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('total')" href="#" role="button">
                        {{ __('Total amount') }}
                        @include('inclues._sort-icon', ['field' => 'total_amount'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    <a wire:click.prevent="sortBy('order_status')" href="#" role="button">
                        {{ __('Status') }}
                        @include('inclues._sort-icon', ['field' => 'order_status'])
                    </a>
                </th>
                <th scope="col" class="align-middle text-center">
                    {{ __('Action') }}
                </th>
            </tr>
            </thead>
            <tbody>
            @forelse ($quotations as $quotation)
                <tr>
                    <td class="align-middle text-center">
                        {{ $loop->iteration }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $quotation->reference }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $quotation->date->format('d-m-Y') }}
                    </td>
                    <td class="align-middle text-center">
                        {{ $quotation->customer->name }}
                    </td>
                    <td class="align-middle text-center">
                        {{ Number::currency($quotation->total_amount, 'BDT') }}
                    </td>
                    <td class="align-middle text-center">
                        <span class="badge {{ $quotation->status === \App\Enums\QuotationStatus::PENDING ? 'bg-orange' : 'bg-green' }} text-white text-uppercase">
                            {{ $quotation->status->label() }}
                        </span>
                    </td>
                    <td class="align-middle text-center">
                        @can('view quotation')
                            <x-button.show class="btn-icon" route="{{ route('quotations.show', $quotation) }}"/>
                        @endcan
                        @can('update quotation')
                            @unless ($isDemoMode)
                            <x-button.edit class="btn-icon" route="{{ route('quotations.edit', $quotation) }}"/>
                            @endunless
                        @endcan
                        @can('delete quotation')
                            @unless ($isDemoMode)
                            <x-button.delete class="btn-icon" route="{{ route('quotations.destroy', $quotation) }}"/>
                            @endunless
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td class="align-middle text-center" colspan="8">
                        No results found
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer d-flex align-items-center">
        <p class="m-0 text-secondary">
            Showing <span>{{ $quotations->firstItem() }}</span> to <span>{{ $quotations->lastItem() }}</span> of <span>{{ $quotations->total() }}</span> entries
        </p>

        <ul class="pagination m-0 ms-auto">
            {{ $quotations->links() }}
        </ul>
    </div>
</div>
