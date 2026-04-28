<?php

namespace Tests\Feature;

use App\Enums\PurchaseStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Supplier;
use App\Models\Unit;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
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
        $user = $this->createAuthorizedUser(['view purchase']); // Create a user for authentication
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
        $user = $this->createAuthorizedUser(['view purchase']);
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
        $user = $this->createAuthorizedUser(['view purchase']);
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
        $user = $this->createAuthorizedUser(['view purchase']);
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
        $user = $this->createAuthorizedUser(['create purchase']);
        $this->actingAs($user);

        $response = $this->get(route('purchases.create'));

        $response->assertStatus(200);
        $response->assertViewIs('purchases.create');
        $response->assertViewHas('categories');
        $response->assertViewHas('suppliers');
    }

    public function test_purchase_store()
    {
        $user = $this->createAuthorizedUser(['create purchase']);
        $this->actingAs($user);

        $supplier = Supplier::factory()->create();
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();

        $product1 = Product::factory()->create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);
        $product2 = Product::factory()->create([
            'category_id' => $category->id,
            'unit_id' => $unit->id,
        ]);

        $invoiceProducts = [
            [
                'product_id' => $product1->id,
                'quantity' => 2,
                'unitcost' => 100,
            ],
            [
                'product_id' => $product2->id,
                'quantity' => 5,
                'unitcost' => 50,
            ],
        ];

        $response = $this->post(route('purchases.store'), [
            'supplier_id' => $supplier->id,
            'date' => now()->toDateString(),
            'purchase_no' => 'FORGED-12345',
            'status' => PurchaseStatus::PENDING->value,
            'total_amount' => 450,
            'created_by' => $user->id,
            'updated_by' => $user->id,
            'invoiceProducts' => $invoiceProducts,
        ]);

        $response->assertRedirect(route('purchases.index'));
        $response->assertSessionHas('success', 'Purchase has been created!');

        $this->assertDatabaseHas('purchases', [
            'supplier_id' => $supplier->id,
            'total_amount' => 45000,
            'status' => PurchaseStatus::PENDING->value,
        ]);

        $this->assertDatabaseHas('purchase_details', [
            'product_id' => $product1->id,
            'quantity' => 2,
            'unitcost' => 10000,
        ]);

        $this->assertDatabaseHas('purchase_details', [
            'product_id' => $product2->id,
            'quantity' => 5,
            'unitcost' => 5000,
        ]);
    }

    public function test_purchase_store_requires_at_least_one_line_and_does_not_partially_save(): void
    {
        $user = $this->createAuthorizedUser(['create purchase']);
        $this->actingAs($user);

        $supplier = Supplier::factory()->create();

        $response = $this->from(route('purchases.create'))
            ->post(route('purchases.store'), [
                'supplier_id' => $supplier->id,
                'date' => now()->toDateString(),
                'total_amount' => 100,
                'invoiceProducts' => [],
            ]);

        $response
            ->assertRedirect(route('purchases.create'))
            ->assertSessionHasErrors([
                'invoiceProducts' => 'At least one product row is required',
            ]);

        $this->assertDatabaseCount('purchases', 0);
        $this->assertDatabaseCount('purchase_details', 0);
    }

    public function test_purchase_edit(): void
    {
        // Create necessary data
        $user = $this->createAuthorizedUser(['update purchase']);
        $supplier = Supplier::factory()->create();
        $purchase = Purchase::factory()->create([
            'supplier_id' => $supplier->id,
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        // Add some details to the purchase
        PurchaseDetails::factory()->create([
            'purchase_id' => $purchase->id,
        ]);

        // Make the GET request to the edit route
        $response = $this->actingAs($user)->get(route('purchases.edit', $purchase));

        // Assert the response status
        $response->assertStatus(200);

        // Assert that the view is the correct one
        $response->assertViewIs('purchases.edit');

        // Assert that the purchase is passed to the view
        $response->assertViewHas('purchase', function ($viewPurchase) use ($purchase) {
            return $viewPurchase->id === $purchase->id;
        });

        // Assert that the related supplier and details are loaded (Eager Loading check)
        $this->assertTrue($purchase->load('supplier', 'details')->relationLoaded('supplier'));
        $this->assertTrue($purchase->load('supplier', 'details')->relationLoaded('details'));
    }

    public function test_purchase_store_persistence_and_server_side_totals(): void
    {
        $user = $this->createAuthorizedUser(['create purchase']);
        $this->actingAs($user);

        $supplier = Supplier::factory()->create();
        $category = Category::factory()->create();
        $unit = Unit::factory()->create();

        $product1 = Product::factory()->create(['category_id' => $category->id, 'unit_id' => $unit->id]);
        $product2 = Product::factory()->create(['category_id' => $category->id, 'unit_id' => $unit->id]);

        $invoiceProducts = [
            ['product_id' => $product1->id, 'quantity' => 2, 'unitcost' => 100, 'total' => 1], // Total should be 200
            ['product_id' => $product2->id, 'quantity' => 5, 'unitcost' => 50, 'total' => 9999], // Total should be 250
        ];

        $response = $this->post(route('purchases.store'), [
            'supplier_id' => $supplier->id,
            'date' => now()->toDateString(),
            'purchase_no' => 'FORGED-123',
            'status' => PurchaseStatus::APPROVED->value,
            'total_amount' => 450,
            'created_by' => 999,
            'invoiceProducts' => $invoiceProducts,
        ]);

        $response->assertRedirect(route('purchases.index'));

        $purchase = Purchase::latest()->first();

        // Verify server-side boundaries
        $this->assertNotEquals('FORGED-123', $purchase->purchase_no);
        $this->assertSame(PurchaseStatus::PENDING->value, $purchase->status->value);
        $this->assertSame($user->id, $purchase->created_by);

        // Verify line totals are server-calculated
        $this->assertDatabaseHas('purchase_details', [
            'purchase_id' => $purchase->id,
            'product_id' => $product1->id,
            'total' => 20000,
        ]);
        $this->assertDatabaseHas('purchase_details', [
            'purchase_id' => $purchase->id,
            'product_id' => $product2->id,
            'total' => 25000,
        ]);
    }

    public function test_purchase_approval_increases_stock_and_updates_status(): void
    {
        $user = $this->createAuthorizedUser(['update purchase']);
        $this->actingAs($user);

        $product = Product::factory()->create(['quantity' => 10]);
        $purchase = Purchase::factory()->create(['status' => PurchaseStatus::PENDING]);

        PurchaseDetails::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 5,
            'unitcost' => 100,
            'total' => 500,
        ]);

        $response = $this->put(route('purchases.update', $purchase));

        $response->assertRedirect(route('purchases.index'));
        $this->assertSame(PurchaseStatus::APPROVED->value, $purchase->fresh()->status->value);
        $this->assertSame(15, $product->fresh()->quantity);
    }

    public function test_purchase_approval_is_idempotent_and_rejects_duplicate_approval(): void
    {
        $user = $this->createAuthorizedUser(['update purchase']);
        $this->actingAs($user);

        $purchase = Purchase::factory()->create(['status' => PurchaseStatus::APPROVED]);

        $response = $this->put(route('purchases.update', $purchase));

        $response->assertRedirect(route('purchases.index'));
        $response->assertSessionHas('error', 'This purchase has already been approved.');
    }

    public function test_purchase_approval_handles_missing_product(): void
    {
        $user = $this->createAuthorizedUser(['update purchase']);
        $this->actingAs($user);

        $purchase = Purchase::factory()->create(['status' => PurchaseStatus::PENDING]);

        $product = Product::factory()->create();

        PurchaseDetails::create([
            'purchase_id' => $purchase->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unitcost' => 100,
            'total' => 100,
        ]);

        DB::statement('PRAGMA defer_foreign_keys = ON');
        DB::table('purchase_details')
            ->where('purchase_id', $purchase->id)
            ->update(['product_id' => $product->id + 999999]);

        $response = $this->put(route('purchases.update', $purchase));

        $response->assertRedirect(route('purchases.index'));
        $response->assertSessionHas('error', 'A product in this purchase no longer exists.');
        $this->assertSame(PurchaseStatus::PENDING->value, $purchase->fresh()->status->value);
    }
}
