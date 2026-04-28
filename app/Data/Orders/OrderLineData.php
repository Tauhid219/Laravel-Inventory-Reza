<?php

namespace App\Data\Orders;

final class OrderLineData
{
    public function __construct(
        public readonly int $productId,
        public readonly int $quantity,
        public readonly int|float $unitCost,
        public readonly int|float|null $lineTotal = null,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        return new self(
            productId: (int) $attributes['product_id'],
            quantity: (int) $attributes['quantity'],
            unitCost: (int) round((float) $attributes['unitcost'] * 100),
            lineTotal: array_key_exists('total', $attributes)
                ? (int) round((float) $attributes['total'] * 100)
                : null,
        );
    }

    public function normalizedUnitCost(): int
    {
        return (int) $this->unitCost;
    }

    public function calculatedTotal(): int
    {
        return $this->quantity * $this->normalizedUnitCost();
    }
}
