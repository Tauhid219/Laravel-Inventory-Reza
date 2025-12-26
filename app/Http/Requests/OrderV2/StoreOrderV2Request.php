<?php

namespace App\Http\Requests\OrderV2;

use App\Enums\OrderStatus;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderV2Request extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id'   => 'required',
            'date'          => 'required|string',
            'total_amount'  => 'required|numeric',
            'status'        => 'required',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'invoice_no' => IdGenerator::generate([
                'table' => 'orders',
                'field' => 'invoice_no',
                'length' => 10,
                'prefix' => 'INV-',
            ]),
            'status'     => OrderStatus::PENDING->value,
            'created_by' => auth()->user()->id,
        ]);
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Customer is required',
        ];
    }
}
