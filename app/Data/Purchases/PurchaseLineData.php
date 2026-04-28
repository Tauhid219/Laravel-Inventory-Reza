<?php

namespace App\Data\Purchases;

final class PurchaseLineData
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly int $unitCost,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            productId: (int) $attributes['product_id'],
            quantity: (int) $attributes['quantity'],
            unitCost: (int) round((float) $attributes['unitcost'] * 100),
        );
    }

    public function total(): int
    {
        return $this->quantity * $this->unitCost;
    }
}
