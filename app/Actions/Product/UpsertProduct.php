<?php

declare(strict_types=1);

namespace App\Actions\Product;

use App\Models\Product;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

final class UpsertProduct
{
    private const PRODUCT_FIELDS = [
        'code',
        'name',
        'category_id',
        'sub_category_id',
        'unit_id',
        'buying_price',
        'selling_price',
        'quantity_alert',
        'tax',
        'tax_type',
        'notes',
    ];

    public function __construct(
        private readonly HandleProductImage $handleProductImage,
    ) {
    }

    public function create(array $validated, ?UploadedFile $image = null): Product
    {
        $payload = $this->buildPayload($validated);
        $payload['code'] = $this->ensureUniqueCode($payload['code'] ?? null);

        $product = Product::create($payload);

        return $this->syncImage($product, $image);
    }

    public function update(Product $product, array $validated, ?UploadedFile $image = null): Product
    {
        $payload = $this->buildPayload($validated);
        $payload['code'] = $this->ensureUniqueCode($payload['code'] ?? $product->code, $product);

        $product->update($payload);

        return $this->syncImage($product, $image);
    }

    private function buildPayload(array $validated): array
    {
        $payload = array_intersect_key($validated, array_flip(self::PRODUCT_FIELDS));
        $payload['slug'] = Str::slug($validated['name']);

        return $payload;
    }

    private function ensureUniqueCode(?string $code, ?Product $ignore = null): string
    {
        if (blank($code)) {
            $code = $this->generateUniqueCode($ignore);
        }

        $query = Product::query()->where('code', $code);

        if ($ignore !== null) {
            $query->whereKeyNot($ignore->getKey());
        }

        if (!$query->exists()) {
            return $code;
        }

        return $this->generateUniqueCode($ignore);
    }

    private function generateUniqueCode(?Product $ignore = null): string
    {
        do {
            $generatedCode = 'PC' . strtoupper(Str::random(6));
        } while (
            Product::query()
                ->where('code', $generatedCode)
                ->when($ignore !== null, fn ($builder) => $builder->whereKeyNot($ignore->getKey()))
                ->exists()
        );

        return $generatedCode;
    }

    private function syncImage(Product $product, ?UploadedFile $image = null): Product
    {
        if ($image === null) {
            return $product->fresh();
        }

        $filename = $product->product_image
            ? $this->handleProductImage->update($product->product_image, $image)
            : $this->handleProductImage->store($image);

        $product->update([
            'product_image' => $filename,
        ]);

        return $product->fresh();
    }
}
