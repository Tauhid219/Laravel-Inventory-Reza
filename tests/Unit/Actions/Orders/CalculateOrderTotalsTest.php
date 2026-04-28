<?php

namespace Tests\Unit\Actions\Orders;

use App\Actions\Orders\CalculateOrderTotals;
use App\Data\Orders\CreateOrderData;
use App\Enums\PaymentType;
use App\Exceptions\Orders\InvalidOrderPricing;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class CalculateOrderTotalsTest extends TestCase
{
    #[Test]
    public function it_calculates_subtotal_due_and_override_metadata(): void
    {
        $data = CreateOrderData::fromArray([
            'customer_id' => 1,
            'date' => '2026-04-16',
            'payment_type' => PaymentType::DUE->value,
            'pay' => 50,
            'total_amount' => 120,
            'invoiceProducts' => [
                [
                    'product_id' => 10,
                    'quantity' => 2,
                    'unitcost' => 40,
                ],
            ],
        ]);

        $pricing = app(CalculateOrderTotals::class)->handle($data);

        $this->assertSame(8000, $pricing->subTotal);
        $this->assertSame(12000, $pricing->finalTotal);
        $this->assertSame(5000, $pricing->paidAmount);
        $this->assertSame(7000, $pricing->dueAmount);
        $this->assertSame(1, $pricing->lineCount);
        $this->assertSame(2, $pricing->totalProducts);
        $this->assertSame(4000, $pricing->adjustmentAmount);
        $this->assertTrue($pricing->isOverridden);
        $this->assertTrue($pricing->hasDueBalance());
    }

    #[Test]
    public function it_rejects_paid_amount_greater_than_final_total(): void
    {
        $this->expectException(InvalidOrderPricing::class);
        $this->expectExceptionMessage('Paid amount cannot exceed the final total.');

        $data = CreateOrderData::fromArray([
            'customer_id' => 1,
            'date' => '2026-04-16',
            'payment_type' => PaymentType::CASH->value,
            'pay' => 200,
            'total_amount' => 100,
            'invoiceProducts' => [
                [
                    'product_id' => 10,
                    'quantity' => 1,
                    'unitcost' => 100,
                ],
            ],
        ]);

        app(CalculateOrderTotals::class)->handle($data);
    }
}
