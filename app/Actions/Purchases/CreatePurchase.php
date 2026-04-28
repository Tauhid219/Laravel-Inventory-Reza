<?php

namespace App\Actions\Purchases;

use App\Data\Purchases\CreatePurchaseData;
use App\Enums\PurchaseStatus;
use App\Models\Purchase;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\DB;

final class CreatePurchase
{
    public function handle(CreatePurchaseData $data): Purchase
    {
        return DB::transaction(function () use ($data): Purchase {
            $purchase = Purchase::create([
                'supplier_id' => $data->supplierId,
                'date' => $data->purchaseDate,
                'purchase_no' => $this->generatePurchaseNumber(),
                'status' => PurchaseStatus::PENDING,
                'total_amount' => $data->totalAmount,
                'created_by' => $data->createdBy,
            ]);

            $purchase->details()->createMany(array_map(
                static fn ($line): array => [
                    'product_id' => $line->productId,
                    'quantity' => $line->quantity,
                    'unitcost' => $line->unitCost,
                    'total' => $line->total(),
                ],
                $data->lines,
            ));

            return $purchase->fresh(['details']);
        });
    }

    private function generatePurchaseNumber(): string
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            $lastPurchaseNo = DB::table('purchases')->orderByDesc('id')->value('purchase_no');
            $nextNumber = ((int) preg_replace('/\D/', '', (string) $lastPurchaseNo)) + 1;

            return 'PRS-' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
        }

        return IdGenerator::generate([
            'table' => 'purchases',
            'field' => 'purchase_no',
            'length' => 10,
            'prefix' => 'PRS-',
        ]);
    }
}
