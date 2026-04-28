<div class="mb-3">
    <div class="alert alert-info d-flex align-items-center" role="alert">
        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-info-circle me-2" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 12m-9 -9a9 9 0 1 0 18 0a9 9 0 1 0 -18 0" /><path d="M12 9v4" /><path d="M12 16h.01" /></svg>
        <div>
            <strong>{{ __('Quick Guide:') }}</strong> To add a product, click the <strong>"+"</strong> button and select the category first.
        </div>
    </div>

    <table class="table table-bordered" id="products_table">
        <thead class="thead-dark">
            <tr>
                <th class="align-middle text-center">Category*</th>
                <th class="align-middle text-center">Sub Category</th>
                <th class="align-middle">Product*</th>
                <th class="align-middle text-center">Quantity*</th>
                <th class="align-middle text-center">Availability</th>
                <th class="align-middle text-center">Price</th>
                <th class="align-middle text-center">Total</th>
                <th class="align-middle text-center">Action</th>
            </tr>
        </thead>

        <tbody class="{{ empty($invoiceProducts) ? 'text-center' : '' }}">
            @forelse ($invoiceProducts as $index => $invoiceProduct)
                <tr>
                    {{-- - Category - --}}
                    <td class="align-middle text-center">
                        @if ($invoiceProduct['is_saved'])
                            <input type="hidden" name="invoiceProducts[{{ $index }}][category_id]"
                                value="{{ $invoiceProduct['category_id'] }}">
                            {{ $invoiceProduct['category_name'] }}
                        @else
                            <select wire:model="invoiceProducts.{{ $index }}.category_id" class="form-control"
                                wire:change="onCategoryUpdated($event.target.value)">
                                <option value="">-- choose category --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>

                    {{-- - Sub Category - --}}
                    <td class="align-middle text-center">
                        @if ($invoiceProduct['is_saved'])
                            {{ $invoiceProduct['subcategory_name'] }}
                        @else
                            <select wire:model="invoiceProducts.{{ $index }}.subcategory_id" class="form-control"
                                wire:change="onSubCategoryUpdated($event.target.value)">
                                <option value="">-- choose subcategory --</option>
                                @foreach ($subCategories as $subCategory)
                                    <option value="{{ $subCategory->id }}">{{ $subCategory->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>

                    {{-- Product --}}
                    <td class="align-middle">
                        @if ($invoiceProduct['is_saved'])
                            <input type="hidden" name="invoiceProducts[{{ $index }}][product_id]"
                                value="{{ $invoiceProduct['product_id'] }}">
                            {{ $invoiceProduct['product_name'] }}
                        @else
                            <select wire:model.live="invoiceProducts.{{ $index }}.product_id"
                                wire:change="onProductSelected({{ $index }})"
                                id="invoiceProducts[{{ $index }}][product_id]" class="form-control text-center"
                                @error('invoiceProducts.' . $index . '.product_id') is-invalid @enderror>
                                <option value="">-- choose product --</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        @endif
                    </td>

                    {{-- - Quantity - --}}
                    <td class="align-middle text-center">
                        @if ($invoiceProduct['is_saved'])
                            {{ $invoiceProduct['quantity'] }}

                            <input type="hidden" name="invoiceProducts[{{ $index }}][quantity]"
                                value="{{ $invoiceProduct['quantity'] }}">
                        @else
                            <input type="number" wire:model="invoiceProducts.{{ $index }}.quantity"
                                id="invoiceProducts[{{ $index }}][quantity]" class="form-control" />
                        @endif
                    </td>

                    {{-- - Available Stock - --}}
                    <td class="align-middle text-center">
                        @if ($invoiceProduct['is_saved'])
                            <!-- Display the selected product's quantity -->
                            {{ $invoiceProduct['product_quantity'] }} <!-- This is the product's available quantity -->
                        @else
                            <!-- Just display the quantity if product is selected -->
                            @if (isset($invoiceProduct['product_id']) && $invoiceProduct['product_id'])
                                <span>{{ $invoiceProduct['product_quantity'] }}</span>
                            @else
                                <span>0</span> <!-- Show 0 if no product is selected yet -->
                            @endif
                        @endif
                    </td>

                    {{-- - Unit Price - --}}
                    <td class="align-middle text-center">
                        {{-- @if ($invoiceProduct['is_saved'])
                            {{ $unit_cost = number_format($invoiceProduct['product_price'], 2) }}

                            <input type="hidden" name="invoiceProducts[{{ $index }}][unitcost]"
                                value="{{ $unit_cost }}">
                        @endif --}}
                        @if ($invoiceProduct['is_saved'])
                            {{-- Display only --}}
                            {{ number_format($invoiceProduct['product_price'], 2) }}

                            {{-- Hidden input for DB (raw number) --}}
                            <input type="hidden" name="invoiceProducts[{{ $index }}][unitcost]"
                                value="{{ $invoiceProduct['product_price'] }}">
                        @endif
                    </td>

                    {{-- - Total - --}}
                    <td class="align-middle text-center">
                        {{ $product_total = $invoiceProduct['product_price'] * $invoiceProduct['quantity'] }}

                        <input type="hidden" name="invoiceProducts[{{ $index }}][total]"
                            value="{{ $product_total }}">
                    </td>

                    <td class="align-middle text-center">
                        @if ($invoiceProduct['is_saved'])
                            <button type="button" wire:click="editProduct({{ $index }})"
                                class="btn btn-icon btn-outline-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-pencil"
                                    width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M4 20h4l10.5 -10.5a2.828 2.828 0 1 0 -4 -4l-10.5 10.5v4" />
                                    <path d="M13.5 6.5l4 4" />
                                </svg>
                            </button>
                        @elseif($invoiceProduct['product_id'])
                            <button type="button" wire:click="saveProduct({{ $index }})"
                                class="btn btn-icon btn-outline-success mr-1">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="icon icon-tabler icon-tabler-device-floppy" width="24" height="24"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                    <path d="M6 4h10l4 4v10a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2" />
                                    <path d="M12 14m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0" />
                                    <path d="M14 4l0 4l-6 0l0 -4" />
                                </svg>
                            </button>
                        @endif

                        <button type="button" wire:click="removeProduct({{ $index }})"
                            class="btn btn-icon btn-outline-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M4 7l16 0" />
                                <path d="M10 11l0 6" />
                                <path d="M14 11l0 6" />
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                            </svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="align-middle text-center py-4 text-secondary">
                        <div class="d-flex flex-column align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-package-export mb-2" width="48" height="48" viewBox="0 0 24 24" stroke-width="1" stroke="#adb5bd" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 10l-2 2m0 0l-2 -2m2 2l2 -2m0 0l2 2m0 0l-2 2m0 0l-2 -2m2 2l2 -2" /><path d="M12 2l0 12" /><path d="M12 22l0 -12" /><path d="M12 10l0 4" /><path d="M16 14l-4 4l-4 -4" /><path d="M12 18l0 4" /><path d="M8 18l4 4l4 -4" /><path d="M12 14l0 4" /></svg>
                            <span>{{ __('No products added yet. Click the + button to start.') }}</span>
                        </div>
                    </td>
                </tr>
            @endforelse
            <tr class="{{ empty($invoiceProducts) ? 'd-none' : '' }}">
                <td colspan="7"></td>
                <td class="text-center">
                    <button type="button" wire:click="addProduct" class="btn btn-icon btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-plus"
                            width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                            <path d="M12 5l0 14" />
                            <path d="M5 12l14 0" />
                        </svg>
                    </button>
                </td>
            </tr>
            <tr>
                <th colspan="7" class="align-middle text-end">
                    Subtotal
                </th>
                <td class="text-center">
                    {{--                    ${{ number_format($subtotal, 2) }} --}}
                    {{ Number::currency($subtotal, 'BDT') }}
                </td>
            </tr>
            {{-- <tr>
                <th colspan="7" class="align-middle text-end">
                    Taxes
                </th>
                <td width="150" class="align-middle text-center">
                    <input wire:model.blur="taxes" type="number" id="taxes" class="form-control w-75 d-inline"
                        min="0" max="100">
                    %

                    @error('taxes')
                        <em class="invalid-feedback">
                            {{ $message }}
                        </em>
                    @enderror
                </td>
            </tr> --}}
            <tr class="{{ $total !== $subtotal && $manualTotal !== null ? 'table-warning' : '' }}">
                <th colspan="7" class="align-middle text-end">
                    Total 
                    @if($total !== $subtotal && $manualTotal !== null)
                        <span class="badge bg-warning text-dark ms-2" title="{{ __('The total has been manually adjusted') }}">
                            <i class="fas fa-edit me-1"></i> {{ __('Manual Override') }}
                        </span>
                    @endif
                </th>
                <td class="text-center">
                    <div class="input-group input-group-sm" style="max-width: 150px; margin: 0 auto;">
                        <span class="input-group-text">BDT</span>
                        <input type="number" wire:model.live="manualTotal" name="total_amount"
                            class="form-control text-center {{ $total !== $subtotal && $manualTotal !== null ? 'border-warning' : '' }}" min="0" step="0.01"
                            placeholder="{{ number_format($total, 2, '.', '') }}">
                    </div>
                </td>
            </tr>

        </tbody>
    </table>
</div>
