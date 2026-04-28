<?php

namespace App\Actions\Orders;

use App\Enums\OrderStatus;
use App\Exceptions\Orders\InvalidOrderCompletion;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

final class CompleteOrder
{
    public function handle(Order $order): Order
    {
        return DB::transaction(function () use ($order): Order {
            $lockedOrder = Order::query()
                ->with('details')
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->order_status === OrderStatus::COMPLETE) {
                throw new InvalidOrderCompletion('This order is already complete.');
            }

            foreach ($lockedOrder->details as $item) {
                $product = Product::query()
                    ->lockForUpdate()
                    ->find($item->product_id);

                if (!$product) {
                    throw new InvalidOrderCompletion('A product in this order no longer exists.');
                }

                if ($product->quantity < $item->quantity) {
                    throw new InvalidOrderCompletion(
                        "Insufficient stock for {$product->name}. Current stock: {$product->quantity}"
                    );
                }

                $product->decrement('quantity', $item->quantity);
            }

            $lockedOrder->update([
                'order_status' => OrderStatus::COMPLETE,
            ]);

            return $lockedOrder->fresh(['details']);
        });
    }
}
