<?php

namespace App\Http\Requests\Purchase;

use App\Enums\PurchaseStatus;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id'   => 'required',
            'date'          => 'required|string',
            'total_amount'  => 'required|numeric',
            'status'        => 'required',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'purchase_no' => $this->purchase_no ?: $this->generatePurchaseNumber(),
            'status'     => PurchaseStatus::PENDING->value,
            'created_by' => auth()->user()->id,
        ]);
    }

    private function generatePurchaseNumber(): string
    {
        if (DB::connection()->getDriverName() === 'mysql') {
            return IdGenerator::generate([
                'table' => 'purchases',
                'field' => 'purchase_no',
                'length' => 10,
                'prefix' => 'PRS-'
            ]);
        }

        $lastPurchaseNo = DB::table('purchases')->orderByDesc('id')->value('purchase_no');
        $nextNumber = ((int) preg_replace('/\D/', '', (string) $lastPurchaseNo)) + 1;

        return 'PRS-' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function messages(): array
    {
        return [
            'supplier_id.required' => 'Supplier is required',
        ];
    }
}
