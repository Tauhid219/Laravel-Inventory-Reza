<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiProductControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_product_api_returns_products(): void
    {
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();

        Product::factory()->create([
            'name' => 'Test Product',
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);

        $response = $this->getJson(route('api.product.index'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'slug',
                        'code',
                        'quantity',
                        'quantity_alert',
                        'buying_price',
                        'selling_price',
                        'tax',
                        'tax_type',
                        'notes',
                        'product_image',
                        'category' => ['id', 'name', 'slug'],
                        'sub_category',
                        'unit' => ['id', 'name', 'slug'],
                        'created_at',
                        'updated_at',
                    ],
                ],
                'links',
                'meta',
            ])
            ->assertJsonPath('data.0.name', 'Test Product')
            ->assertJsonPath('data.0.category.id', $category->id)
            ->assertJsonPath('data.0.unit.id', $unit->id);
    }

    public function test_product_api_filters_by_category(): void
    {
        $unit = Unit::factory()->create();
        $matchingCategory = Category::factory()->create();
        $otherCategory = Category::factory()->create();

        Product::factory()->create([
            'name' => 'Matching Product',
            'category_id' => $matchingCategory->id,
            'unit_id' => $unit->id,
        ]);

        Product::factory()->create([
            'name' => 'Other Product',
            'category_id' => $otherCategory->id,
            'unit_id' => $unit->id,
        ]);

        $response = $this->getJson(route('api.product.index', ['category_id' => $matchingCategory->id]));

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.name', 'Matching Product')
            ->assertJsonPath('data.0.category.id', $matchingCategory->id)
            ->assertJsonMissing(['name' => 'Other Product']);
    }

    public function test_product_api_paginates_products(): void
    {
        Product::factory()->count(3)->create();

        $response = $this->getJson(route('api.product.index', ['per_page' => 2]));

        $response
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.per_page', 2)
            ->assertJsonPath('meta.total', 3);
    }

    public function test_product_api_validates_category_filter(): void
    {
        $response = $this->getJson(route('api.product.index', ['category_id' => 999999]));

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors('category_id');
    }
}
