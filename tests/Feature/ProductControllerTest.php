<?php

namespace Tests\Feature;

use App\Enums\TaxType;
use App\Models\Category;
use App\Models\Product;
use App\Models\SubCategory;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    use RefreshDatabase;
    use WithFaker;

    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_product_index(): void
    {
        $user = $this->createAuthorizedUser(['view product']);
        $this->actingAs($user);
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
    }

    public function test_product_create(): void
    {
        $user = $this->createAuthorizedUser(['create product']);
        $this->actingAs($user);
        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
    }

    public function test_product_store(): void
    {
        // Acting as authenticated user
        $user = $this->createAuthorizedUser(['create product']);
        $this->actingAs($user);

        // Fake storage for image test
        Storage::fake('public');

        // Create necessary foreign keys
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();
        $subCategory = SubCategory::create([
            'name' => 'Test Sub Category',
            'slug' => 'test-sub-category',
            'category_id' => $category->id,
        ]);

        // Prepare request payload
        $data = [
            'code' => 'PC123',
            'name' => 'Test Product',
            'category_id' => $category->id,
            'sub_category_id' => $subCategory->id,
            'unit_id' => $unit->id,
            'buying_price' => 500,
            'selling_price' => 700,
            'quantity_alert' => 5,
            'tax' => 10,
            'tax_type' => TaxType::INCLUSIVE->value,
            'notes' => 'Test note',
            'product_image' => UploadedFile::fake()->image('product.jpg'),
        ];

        // POST request to store product
        $response = $this->post('/products', $data);

        // Assert redirect back with success message
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert product exists in database
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            // 'code' => 'PC123', // or auto-generated
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);

        // Assert image stored
        Storage::disk('public')->assertExists('products/' . Product::first()->product_image);
    }

    public function test_product_show(): void
    {
        // Arrange:
        $user = $this->createAuthorizedUser(['view product']);
        $this->actingAs($user);
        $category = Category::factory()->create();
        $subCategory = SubCategory::create([
            'name' => 'Product Show Sub Category',
            'slug' => 'product-show-sub-category',
            'category_id' => $category->id,
        ]);

        $product = Product::factory()
            ->for($category)
            ->for(Unit::factory())
            ->create([
                'sub_category_id' => $subCategory->id,
            ]);

        // Act: show
        $response = $this->get(route('products.show', $product->slug));

        // Assert:
        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertViewHas('barcode');
        $response->assertViewHas('product', function ($viewProduct) use ($product) {
            return $viewProduct->id === $product->id;
        });
    }

    public function test_product_edit(): void
    {
        // Arrange:
        $user = $this->createAuthorizedUser(['update product']);
        $this->actingAs($user);
        $category = Category::factory()->create();
        $subCategory = SubCategory::create([
            'name' => 'Product Edit Sub Category',
            'slug' => 'product-edit-sub-category',
            'category_id' => $category->id,
        ]);

        $product = Product::factory()
            ->for($category)
            ->for(Unit::factory())
            ->create([
                'sub_category_id' => $subCategory->id,
            ]);

        // Act: edit
        $response = $this->get(route('products.edit', $product->slug));

        // Assert:
        $response->assertStatus(200);
        $response->assertViewIs('products.edit');
        $response->assertViewHas('product', function ($viewProduct) use ($product) {
            return $viewProduct->id === $product->id;
        });
        $response->assertViewHas('categories');
        $response->assertViewHas('units');
    }

    public function test_product_update(): void
    {
        // Arrange: লগইন এবং প্রোডাক্ট বানানো
        $user = $this->createAuthorizedUser(['update product']);
        $this->actingAs($user);

        $category = Category::factory()->create();
        $subCategory = SubCategory::create([
            'name' => 'Updated Product Sub Category',
            'slug' => 'updated-product-sub-category',
            'category_id' => $category->id,
        ]);
        $product = Product::factory()
            ->for($category)
            ->for(Unit::factory())
            ->create([
                'sub_category_id' => $subCategory->id,
            ]);

        // Fake storage for image test
        Storage::fake('public');

        // Prepare request payload
        $data = [
            'code' => 'PC123',
            'name' => 'Updated Product',
            'category_id' => $product->category_id,
            'sub_category_id' => $product->sub_category_id,
            'unit_id' => $product->unit_id,
            'buying_price' => 600,
            'selling_price' => 800,
            'quantity_alert' => 10,
            'tax' => 15,
            'tax_type' => TaxType::EXCLUSIVE->value,
            'notes' => 'Updated note',
            'product_image' => UploadedFile::fake()->image('updated_product.jpg'),
        ];

        // Act: PUT/PATCH request to update product
        $response = $this->put(route('products.update', $product->slug), $data);

        // Assert: Redirect back with success message
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert: Product updated in database
        $this->assertDatabaseHas('products', [
            'name' => 'Updated Product',
            'buying_price' => 60000,   // 600 * 100
            'selling_price' => 80000,  // 800 * 100
        ]);

        $updatedProduct = Product::find($product->id);

        // Assert: Old image deleted and new image stored
        // Storage::disk('public')->assertMissing('products/' . $product->product_image);
        // Storage::disk('public')->assertExists('products/' . Product::first()->product_image);
        Storage::disk('public')->assertExists('products/' . $updatedProduct->product_image);
    }

    public function test_product_destroy(): void
    {
        // Arrange:
        $user = $this->createAuthorizedUser(['delete product']);
        $this->actingAs($user);

        $product = Product::factory()
            ->for(Category::factory())
            ->for(Unit::factory())
            ->create();

        // Act: DELETE
        $response = $this->delete(route('products.destroy', $product->slug));

        // Assert:
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert:
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
