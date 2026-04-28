<?php

namespace App\Actions\Orders;

use App\Data\Orders\CreateOrderData;
use App\Data\Orders\OrderPricingData;
use App\Data\Orders\OrderLineData;
use App\Exceptions\Orders\InvalidOrderPricing;

final class CalculateOrderTotals
{
    public function handle(CreateOrderData $data): OrderPricingData
    {
        if ($data->lineCount() === 0) {
            throw new InvalidOrderPricing('At least one order line is required.');
        }

        $subTotal = 0;

        foreach ($data->lines as $line) {
            $this->validateLine($line);
            $subTotal += $line->calculatedTotal();
        }

        $finalTotal = $data->manualTotal ?? $subTotal;
        $paidAmount = $data->payment->paidAmount;

        if ($finalTotal <= 0) {
            throw new InvalidOrderPricing('Final total must be greater than zero.');
        }

        if ($paidAmount < 0) {
            throw new InvalidOrderPricing('Paid amount must be zero or greater.');
        }

        if ($paidAmount > $finalTotal) {
            throw new InvalidOrderPricing('Paid amount cannot exceed the final total.');
        }

        $dueAmount = $finalTotal - $paidAmount;
        $adjustmentAmount = $finalTotal - $subTotal;

        return new OrderPricingData(
            subTotal: $subTotal,
            finalTotal: $finalTotal,
            paidAmount: $paidAmount,
            dueAmount: $dueAmount,
            lineCount: $data->lineCount(),
            totalProducts: $data->totalProducts(),
            adjustmentAmount: $adjustmentAmount,
            isOverridden: $adjustmentAmount !== 0,
        );
    }

    private function validateLine(OrderLineData $line): void
    {
        if ($line->productId <= 0) {
            throw new InvalidOrderPricing('Each order line must reference a valid product.');
        }

        if ($line->quantity <= 0) {
            throw new InvalidOrderPricing('Each order line must have a quantity greater than zero.');
        }

        if ($line->normalizedUnitCost() <= 0) {
            throw new InvalidOrderPricing('Each order line must have a unit cost greater than zero.');
        }
    }
}
