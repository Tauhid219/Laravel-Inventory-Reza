<?php

namespace App\Data\Orders;

use App\Enums\PaymentType;

final class OrderPaymentData
{
    public function __construct(
        public readonly PaymentType|string $paymentType,
        public readonly int|float $paidAmount,
    ) {
    }

    public static function fromArray(array $attributes): self
    {
        $paymentType = $attributes['payment_type'] instanceof PaymentType
            ? $attributes['payment_type']
            : PaymentType::from(strtolower((string) $attributes['payment_type']));

        return new self(
            paymentType: $paymentType,
            paidAmount: (int) round((float) $attributes['pay'] * 100),
        );
    }
}
