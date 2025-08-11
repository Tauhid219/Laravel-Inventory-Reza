<div class="card">
    <div class="card-header">
        <div>
            <h3 class="card-title">
                {{ __('Sub Categories') }}
            </h3>
        </div>
        <div class="card-actions">
            <x-action.create route="{{ route('sub-categories.create') }}" />
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
                        aria-label="Search sub-category">
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
                    <th scope="col" class="align-middle text-center">
                        <a wire:click.prevent="sortBy('name')" href="#" role="button">
                            {{ __('Name') }}
                            @include('inclues._sort-icon', ['field' => 'name'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center d-none d-sm-table-cell">
                        <a wire:click.prevent="sortBy('slug')" href="#" role="button">
                            {{ __('Slug') }}
                            @include('inclues._sort-icon', ['field' => 'slug'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center d-none d-sm-table-cell">
                        <a wire:click.prevent="sortBy('category.name')" href="#" role="button">
                            {{ __('Category') }}
                            @include('inclues._sort-icon', ['field' => 'category.name'])
                        </a>
                    </th>
                    <th scope="col" class="align-middle text-center">
                        {{ __('Action') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($subCategories as $subCategory)
                    <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="align-middle">{{ $subCategory->name }}</td>
                        <td>{{ $subCategory->slug }}</td>
                        <td>{{ $subCategory->category->name ?? '-' }}</td>
                        {{-- <td>{{ $subCategory->products_count }}</td> --}}
                        <td class="align-middle text-center" style="width: 10%">
                            <x-button.show class="btn-icon" route="{{ route('sub-categories.show', $subCategory) }}" />
                            <x-button.edit class="btn-icon" route="{{ route('sub-categories.edit', $subCategory) }}" />
                            <x-button.delete class="btn-icon"
                                route="{{ route('sub-categories.destroy', $subCategory) }}" />
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center" colspan="6">
                            {{ __('No results found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between align-items-center">
        <div>
            {{ $subCategories->links() }}
        </div>
        <div>
            <span class="text-secondary">
                Showing {{ $subCategories->firstItem() }} to {{ $subCategories->lastItem() }} of
                {{ $subCategories->total() }} entries
            </span>
        </div>
    </div>
</div>
