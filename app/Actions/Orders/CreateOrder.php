<?php

namespace App\Actions\Orders;

use App\Data\Orders\CreateOrderData;
use App\Enums\OrderStatus;
use App\Models\Order;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\DB;

final class CreateOrder
{
    public function __construct(
        private readonly CalculateOrderTotals $calculateOrderTotals,
    ) {
    }

    public function handle(CreateOrderData $data): Order
    {
        $pricing = $this->calculateOrderTotals->handle($data);

        return DB::transaction(function () use ($data, $pricing): Order {
            $order = Order::create([
                'customer_id' => $data->customerId,
                'order_date' => $data->orderDate,
                'order_status' => OrderStatus::PENDING,
                'total_products' => $pricing->totalProducts,
                'sub_total' => $pricing->subTotal,
                'vat' => 0,
                'total' => $pricing->finalTotal,
                'invoice_no' => $this->generateInvoiceNumber(),
                'payment_type' => $data->payment->paymentType->value,
                'pay' => $pricing->paidAmount,
                'due' => $pricing->dueAmount,
                'note' => $data->note,
            ]);

            $order->details()->createMany(array_map(
                static fn ($line): array => [
                    'product_id' => $line->productId,
                    'quantity' => $line->quantity,
                    'unitcost' => $line->normalizedUnitCost(),
                    'total' => $line->calculatedTotal(),
                ],
                $data->lines,
            ));

            return $order->fresh(['details']);
        });
    }

    private function generateInvoiceNumber(): string
    {
        if (DB::connection()->getDriverName() !== 'mysql') {
            $lastInvoiceNo = DB::table('orders')->orderByDesc('id')->value('invoice_no');
            $nextNumber = ((int) preg_replace('/\D/', '', (string) $lastInvoiceNo)) + 1;

            return 'INV-' . str_pad((string) $nextNumber, 6, '0', STR_PAD_LEFT);
        }

        return IdGenerator::generate([
            'table' => 'orders',
            'field' => 'invoice_no',
            'length' => 10,
            'prefix' => 'INV-',
        ]);
    }
}
