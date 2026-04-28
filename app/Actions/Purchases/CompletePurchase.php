<?php

declare(strict_types=1);

namespace App\Actions\Purchases;

use App\Enums\PurchaseStatus;
use App\Exceptions\Purchases\InvalidPurchaseApproval;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

final class CompletePurchase
{
    public function handle(Purchase $purchase): Purchase
    {
        return DB::transaction(function () use ($purchase): Purchase {
            $lockedPurchase = Purchase::query()
                ->with('details')
                ->lockForUpdate()
                ->findOrFail($purchase->id);

            if ($lockedPurchase->status === PurchaseStatus::APPROVED) {
                throw new InvalidPurchaseApproval('This purchase has already been approved.');
            }

            foreach ($lockedPurchase->details as $item) {
                $product = Product::query()
                    ->lockForUpdate()
                    ->find($item->product_id);

                if (!$product) {
                    throw new InvalidPurchaseApproval('A product in this purchase no longer exists.');
                }

                $product->increment('quantity', $item->quantity);
            }

            $lockedPurchase->update([
                'status' => PurchaseStatus::APPROVED,
                'updated_by' => auth()->id(),
            ]);

            return $lockedPurchase->fresh(['details']);
        });
    }
}
