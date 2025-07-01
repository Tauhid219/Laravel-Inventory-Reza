<?php

namespace Tests\Feature;

use App\Enums\PurchaseStatus;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PurchaseControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_purchase_index(): void
    {
        // Arrange: Create necessary data for the test
        $user = User::factory()->create([]); // Create a user for authentication
        $this->actingAs($user); // Authenticate the user

        $supplier = Supplier::factory()->create();

        // Create multiple purchases
        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'status' => PurchaseStatus::PENDING->value,
        ]);
        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'status' => PurchaseStatus::APPROVED->value,
        ]);

        // Act: Send a GET request to the purchases.index route
        $response = $this->get(route('purchases.index'));

        // Assert: Check if the response status is OK
        $response->assertStatus(200);

        // Assert: Check if the view is correct
        $response->assertViewIs('purchases.index');

        // Assert: Check if the purchases are passed to the view
        $response->assertViewHas('purchases');

        // Assert: Check if specific purchase data is present in the response
        $response->assertSee('PENDING'); // Assuming you're displaying the status
        $response->assertSee('APPROVED'); // Assuming you're displaying the status
    }

    public function test_purchase_approved_purchases(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $supplier = Supplier::factory()->create();

        // Create approved purchases
        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'status' => PurchaseStatus::APPROVED->value,
        ]);
        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'status' => PurchaseStatus::APPROVED->value,
        ]);

        $response = $this->get(route('purchases.approvedPurchases'));

        $response->assertStatus(200);
        $response->assertViewIs('purchases.approved-purchases');
        $response->assertViewHas('purchases');
    }

    public function test_purchase_pending_purchases(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $supplier = Supplier::factory()->create();

        // Create pending purchases
        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'status' => PurchaseStatus::PENDING->value,
        ]);
        Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'status' => PurchaseStatus::PENDING->value,
        ]);

        $response = $this->get(route('purchases.pendingPurchases'));

        $response->assertStatus(200);
        $response->assertViewIs('purchases.pending-purchases');
        $response->assertViewHas('purchases');
    }

    public function test_purchase_show(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $supplier = Supplier::factory()->create();

        // Create a purchase
        $purchase = Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'status' => PurchaseStatus::PENDING->value,
        ]);

        $response = $this->get(route('purchases.show', ['purchase' => $purchase]));

        $response->assertStatus(200);
        $response->assertViewIs('purchases.details-purchase');
        $response->assertViewHas('purchase');
        $response->assertViewHas('products');
    }

    public function test_purchase_create(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get(route('purchases.create'));

        $response->assertStatus(200);
        $response->assertViewIs('purchases.create');
        $response->assertViewHas('categories');
        $response->assertViewHas('suppliers');
    }

    public function test_purchase_store()
    {
        // Arrange: Create necessary data for the test
        $user = User::factory()->create();
        $this->actingAs($user); // Authenticate the user

        // Create a supplier
        $supplier = Supplier::factory()->create();

        // Create Category and Unit data for products
        $category = \App\Models\Category::factory()->create();
        $unit = \App\Models\Unit::factory()->create();

        // Create products (ensure categories and units are set)
        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);

        // Prepare invoice products data
        $invoiceProducts = [
            [
                'product_id' => $product1->id,
                'quantity' => 2,
                'unitcost' => 100,
                'total' => 200,
            ],
            [
                'product_id' => $product2->id,
                'quantity' => 5,
                'unitcost' => 50,
                'total' => 250,
            ],
        ];

        // Act: Send a POST request to store the purchase
        $response = $this->post(route('purchases.store'), [
            'supplier_id' => $supplier->id,
            'date' => now()->toDateString(), // Send date as string
            'purchase_no' => 'INV-12345',
            'status' => PurchaseStatus::PENDING->value,
            'total_amount' => 450,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'invoiceProducts' => $invoiceProducts, // Send the invoice products
        ]);

        // Assert: Check if the response redirects to the purchases.index route
        $response->assertRedirect(route('purchases.index'));

        // Assert: Check if the session contains a success message
        $response->assertSessionHas('success', 'Purchase has been created!');

        // Assert: Check if the purchase is created in the database
        $this->assertDatabaseHas('purchases', [
            'purchase_no' => 'PRS-000001',
            'status' => PurchaseStatus::PENDING->value,
            'total_amount' => 450,
        ]);

        // Assert: Check if the purchase details are created in the database
        foreach ($invoiceProducts as $product) {
            $this->assertDatabaseHas('purchase_details', [
                'product_id' => $product['product_id'], // Ensure product_id exists
                'quantity' => $product['quantity'],
                'unitcost' => $product['unitcost'],
                'total' => $product['total'],
            ]);
        }
    }
}
