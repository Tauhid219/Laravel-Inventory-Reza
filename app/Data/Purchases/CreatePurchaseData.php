<?php

namespace App\Data\Purchases;

final class CreatePurchaseData
{
    /**
     * @param  array<int, PurchaseLineData>  $lines
     */
    public function __construct(
        public readonly int $supplierId,
        public readonly string $purchaseDate,
        public readonly int $totalAmount,
        public readonly int $createdBy,
        public readonly array $lines,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        $lines = array_map(
            static fn (array $line): PurchaseLineData => PurchaseLineData::fromArray($line),
            $attributes['invoiceProducts'] ?? [],
        );

        return new self(
            supplierId: (int) $attributes['supplier_id'],
            purchaseDate: (string) $attributes['date'],
            totalAmount: (int) round((float) $attributes['total_amount'] * 100),
            createdBy: (int) $attributes['created_by'],
            lines: $lines,
        );
    }
}
