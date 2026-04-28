<?php

namespace App\Data\Orders;

final class CreateOrderData
{
    /**
     * @param  array<int, OrderLineData>  $lines
     */
    public function __construct(
        public readonly int $customerId,
        public readonly string $orderDate,
        public readonly array $lines,
        public readonly ?float $manualTotal,
        public readonly OrderPaymentData $payment,
        public readonly ?string $note = null,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        $lines = array_map(
            static fn (array $line): OrderLineData => OrderLineData::fromArray($line),
            $attributes['invoiceProducts'] ?? [],
        );

        return new self(
            customerId: (int) $attributes['customer_id'],
            orderDate: (string) $attributes['date'],
            lines: $lines,
            manualTotal: isset($attributes['total_amount']) && $attributes['total_amount'] !== ''
                ? (int) round((float) $attributes['total_amount'] * 100)
                : null,
            payment: OrderPaymentData::fromArray($attributes),
            note: $attributes['note'] ?? null,
        );
    }

    public function lineCount(): int
    {
        return count($this->lines);
    }

    public function totalProducts(): int
    {
        return array_sum(array_map(
            static fn (OrderLineData $line): int => $line->quantity,
            $this->lines,
        ));
    }
}
