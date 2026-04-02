<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatus;
use Gloudemans\Shoppingcart\Facades\Cart;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class OrderStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_id' => 'required',
            'payment_type' => 'required',
            'pay' => 'required|numeric',
        ];
    }

    public function prepareForValidation(): void
    {
        $cart = Cart::instance('order');

        $this->merge([
            'order_date' => $this->order_date ?: Carbon::now()->format('Y-m-d'),
            'order_status' => $this->order_status ?: OrderStatus::PENDING->value,
            'total_products' => $this->total_products ?: $cart->count(),
            'sub_total' => $this->sub_total ?: $cart->subtotal(),
            'vat' => $this->vat ?: $cart->tax(),
            'total' => $this->total ?: $cart->total(),
            'invoice_no' => $this->invoice_no ?: $this->generateInvoiceNumber(),
            'due' => $this->has('due') ? $this->due : ($cart->total() - $this->pay),
        ]);
    }

    private function generateInvoiceNumber(): string
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            return IdGenerator::generate([
                'table' => 'orders',
                'field' => 'invoice_no',
                'length' => 10,
                'prefix' => 'INV-',
            ]);
        }

        $lastInvoiceNo = DB::table('orders')->orderByDesc('id')->value('invoice_no');
        $nextNumber = ((int) preg_replace('/\D/', '', (string) $lastInvoiceNo)) + 1;

        return 'INV-' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }
}
