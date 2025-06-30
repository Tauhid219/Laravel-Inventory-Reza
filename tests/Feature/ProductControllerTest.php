<?php

namespace Tests\Feature;

use App\Enums\TaxType;
use App\Models\Category;
use App\Models\Product;
use App\Models\Unit;
use App\Models\User;
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
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('products.index'));

        $response->assertStatus(200);
    }

    public function test_product_create(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get(route('products.create'));

        $response->assertStatus(200);
    }

    public function test_product_store(): void
    {
        // 🔒 Acting as authenticated user
        $user = User::factory()->create();
        $this->actingAs($user);

        // 🧪 Fake storage for image test
        Storage::fake('public');

        // 🧱 Create necessary foreign keys
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();

        // 🧾 Prepare request payload
        $data = [
            'code' => 'PC123',
            'name' => 'Test Product',
            'category_id' => $category->id,
            'unit_id' => $unit->id,
            'buying_price' => 500,
            'selling_price' => 700,
            'quantity_alert' => 5,
            'tax' => 10,
            'tax_type' => TaxType::INCLUSIVE->value,
            'notes' => 'Test note',
            'product_image' => UploadedFile::fake()->image('product.jpg'),
        ];

        // 🚀 POST request to store product
        $response = $this->post('/products', $data);

        // ✅ Assert redirect back with success message
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // ✅ Assert product exists in database
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            // 'code' => 'PC123', // or auto-generated
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);

        // ✅ Assert image stored
        Storage::disk('public')->assertExists('products/' . Product::first()->product_image);
    }

    public function test_product_show(): void
    {
        // Arrange: লগইন এবং প্রোডাক্ট বানানো
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()
            ->for(Category::factory())
            ->for(Unit::factory())
            ->create();

        // Act: show রাউট হিট করো
        $response = $this->get(route('products.show', $product->slug));

        // Assert: সব ঠিকমতো কাজ করছে কি না
        $response->assertStatus(200);
        $response->assertViewIs('products.show');
        $response->assertViewHas('barcode');
        $response->assertViewHas('product', function ($viewProduct) use ($product) {
            return $viewProduct->id === $product->id;
        });
    }

    public function test_product_edit(): void
    {
        // Arrange: লগইন এবং প্রোডাক্ট বানানো
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()
            ->for(Category::factory())
            ->for(Unit::factory())
            ->create();

        // Act: edit রাউট হিট করো
        $response = $this->get(route('products.edit', $product->slug));

        // Assert: সব ঠিকমতো কাজ করছে কি না
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
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()
            ->for(Category::factory())
            ->for(Unit::factory())
            ->create();

        // 🧪 Fake storage for image test
        Storage::fake('public');

        // 🧾 Prepare request payload
        $data = [
            'code' => 'PC123',
            'name' => 'Updated Product',
            'category_id' => $product->category_id,
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

        // রিফ্রেশ করা প্রোডাক্ট
        $updatedProduct = Product::find($product->id);

        // Assert: Old image deleted and new image stored
        // Storage::disk('public')->assertMissing('products/' . $product->product_image);
        // Storage::disk('public')->assertExists('products/' . Product::first()->product_image);
        Storage::disk('public')->assertExists('products/' . $updatedProduct->product_image);
    }

    public function test_product_destroy(): void
    {
        // Arrange: লগইন এবং প্রোডাক্ট বানানো
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::factory()
            ->for(Category::factory())
            ->for(Unit::factory())
            ->create();

        // Act: DELETE রাউট হিট করো
        $response = $this->delete(route('products.destroy', $product->slug));

        // Assert: সব ঠিকমতো কাজ করছে কি না
        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Assert: প্রোডাক্ট ডিলিট হয়েছে কি না
        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}
