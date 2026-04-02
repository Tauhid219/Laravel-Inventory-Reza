@pushonce('page-styles')
    <link rel="stylesheet" href="{{ asset('dist/libs/tom-select/dist/css/tom-select.bootstrap4.css') }}">
@endpushonce

@props([
    'label' => '',
    'name',
    'id' => null,
    'placeholder' => '',
    'data',
    'value' => null,
])

@php
    $selectId = $id ?: $name;
    $selectedValue = old($name, $value);
    $placeholderText = $placeholder ?: __('Select an option...');
    $tomSelectOptions = [
        'create' => true,
        'sortField' => [
            'field' => 'text',
            'direction' => 'asc',
        ],
        'plugins' => [
            'clear_button' => [
                'title' => 'Clear selection',
            ],
        ],
    ];
@endphp

<div class="col-md-4">
    <label for="{{ $selectId }}" class="form-label required" >
        {{ $label }}
    </label>

    <select id="{{ $selectId }}" name="{{ $name }}" placeholder="{{ $placeholder }}" autocomplete="off"
            class="form-control form-select @error($name) is-invalid @enderror"
            data-tom-select
            data-tom-select-options='{{ json_encode($tomSelectOptions, JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_TAG | JSON_HEX_QUOT) }}'
    >
        <option value="">
            {{ $placeholderText }}
        </option>

        @foreach($data as $option)
            <option value="{{ $option->id }}" @selected((string) $selectedValue === (string) $option->id)>
                {{ $option->name }}
            </option>
        @endforeach
    </select>

    @error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>

{{--- ---}}
{{---
<div class="col-md-4">
    <label class="small my-1" for="supplier_id">
        {{ __('Supplier') }}
        <span class="text-danger">*</span>
    </label>

    <select class="form-select @error('supplier_id') is-invalid @enderror" id="supplier_id" name="supplier_id" required>
        <option selected disabled>
            {{ __('Select a supplier:') }}
        </option>

        @foreach ($suppliers as $supplier)
            <option value="{{ $supplier->id }}" @selected(old('supplier_id', ) == $supplier->id)>
                {{ $supplier->name }}
            </option>
        @endforeach
    </select>

    @error('supplier_id')
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>
---}}
