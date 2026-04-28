<?php

namespace App\Data\Orders;

final class OrderPricingData
{
    public function __construct(
        public readonly int $subTotal,
        public readonly int $finalTotal,
        public readonly int $paidAmount,
        public readonly int $dueAmount,
        public readonly int $lineCount,
        public readonly int $totalProducts,
        public readonly int $adjustmentAmount,
        public readonly bool $isOverridden,
    ) {
    }

    public function hasDueBalance(): bool
    {
        return $this->dueAmount > 0;
    }
}
