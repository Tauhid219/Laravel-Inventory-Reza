<?php

namespace App\Http\Requests\Purchase;

use App\Enums\PurchaseStatus;
use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'date' => 'required|date',
            'total_amount' => 'required|numeric|gt:0',
            'invoiceProducts' => 'required|array|min:1',
            'invoiceProducts.*.product_id' => 'required|exists:products,id',
            'invoiceProducts.*.quantity' => 'required|integer|min:1',
            'invoiceProducts.*.unitcost' => 'required|numeric|gt:0',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'status' => PurchaseStatus::PENDING->value,
            'created_by' => auth()->user()->id,
        ]);
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier is required',
            'invoiceProducts.required' => 'At least one product row is required',
        ];
    }

    public function validatedForCreation(): array
    {
        return array_merge($this->validated(), [
            'created_by' => auth()->id(),
        ]);
    }
}
