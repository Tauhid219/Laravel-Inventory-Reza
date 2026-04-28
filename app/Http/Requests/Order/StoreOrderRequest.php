<?php

namespace App\Http\Requests\Order;

use App\Enums\PaymentType;
use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
            'customer_id' => 'required|exists:customers,id',
            'date' => 'required|date',
            'payment_type' => 'required|in:' . implode(',', array_column(PaymentType::cases(), 'value')),
            'pay' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|gt:0',
            'note' => 'nullable|string',
            'invoiceProducts' => 'required|array|min:1',
            'invoiceProducts.*.product_id' => 'required|exists:products,id',
            'invoiceProducts.*.quantity' => 'required|integer|min:1',
            'invoiceProducts.*.unitcost' => 'required|numeric|gt:0',
        ];
    }

    public function prepareForValidation(): void
    {
        if (!$this->has('pay') || $this->input('pay') === '') {
            $this->merge([
                'pay' => $this->input('total_amount'),
            ]);
        }
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => __('Please select a customer for this order.'),
            'customer_id.exists' => __('The selected customer is invalid.'),
            'date.required' => __('Order date is required.'),
            'payment_type.required' => __('Please select a payment type.'),
            'pay.required' => __('Payment amount is required.'),
            'pay.numeric' => __('Payment amount must be a number.'),
            'total_amount.required' => __('Total amount is required.'),
            'total_amount.numeric' => __('Total amount must be a number.'),
            'invoiceProducts.required' => __('Please add at least one product to the order.'),
            'invoiceProducts.min' => __('The order must contain at least one product.'),
            'invoiceProducts.*.product_id.required' => __('Please select a product for this row.'),
            'invoiceProducts.*.quantity.required' => __('Quantity is required for this product.'),
            'invoiceProducts.*.quantity.min' => __('Quantity must be at least 1.'),
            'invoiceProducts.*.unitcost.required' => __('Unit cost is required.'),
            'invoiceProducts.*.unitcost.gt' => __('Unit cost must be greater than 0.'),
        ];
    }
}
