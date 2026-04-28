<?php

declare(strict_types=1);

namespace App\Actions\Orders;

use App\Models\Product;
use RuntimeException;

final class ValidateRequestedOrderProducts
{
    public function handle(array $invoiceProducts): void
    {
        foreach ($invoiceProducts as $item) {
            $product = Product::query()->find($item['product_id']);

            if (!$product) {
                throw new RuntimeException('A selected product could not be found.');
            }

            if ($product->quantity < $item['quantity']) {
                throw new RuntimeException(
                    "Sorry, '{$product->name}' is out of stock. (Available: {$product->quantity})"
                );
            }
        }
    }
}
