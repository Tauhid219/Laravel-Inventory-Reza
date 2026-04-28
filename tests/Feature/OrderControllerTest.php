<?php

namespace Tests\Feature;

// use App\Models\Customer;
// use App\Models\Product;
// use Gloudemans\Shoppingcart\Cart;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderControllerTest extends TestCase
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

    public function test_order_create(): void
    {
        // Arrange
        $user = $this->createAuthorizedUser(['create order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        // Act
        $response = $this->get(route('orders.create'));

        // Assert
        $response->assertStatus(200);
        $response->assertViewIs('orders.create');
        $response->assertViewHas('customers', function ($customers) use ($customer) {
            return $customers->contains($customer);
        });
        $response->assertViewHas('categories');
    }

    public function test_order_store(): void
    {
        $user = $this->createAuthorizedUser(['create order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 10,
        ]);

        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'payment_type' => PaymentType::CASH->value,
            'pay' => 80,
            'total_amount' => 80,
            'invoiceProducts' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unitcost' => 40,
                    'total' => 80,
                ],
            ],
        ]);

        $response
            ->assertRedirect(route('orders.index'))
            ->assertSessionHas('success', 'Order has been created successfully and is now pending approval.');

        $order = Order::latest()->first();

        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'invoice_no' => $order->invoice_no,
            'total' => 8000,
            'payment_type' => PaymentType::CASH->value,
        ]);

        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 4000,
            'total' => 8000,
        ]);
    }

    public function test_order_store_uses_canonical_store_flow(): void
    {
        $user = $this->createAuthorizedUser(['create order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 10,
        ]);

        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'payment_type' => PaymentType::DUE->value,
            'pay' => 50,
            'total_amount' => 120,
            'note' => 'Canonical store flow note',
            'invoiceProducts' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unitcost' => 40,
                    'total' => 80,
                ],
            ],
        ]);

        $response
            ->assertRedirect(route('orders.index'))
            ->assertSessionHas('success', 'Order has been created successfully and is now pending approval.');

        $order = Order::query()->latest()->first();

        $this->assertNotNull($order);
        $this->assertSame(OrderStatus::PENDING, $order->order_status);
        $this->assertSame(PaymentType::DUE->value, $order->payment_type);
        $this->assertSame(2, $order->total_products);
        $this->assertSame(8000, (int) $order->sub_total);
        $this->assertSame(12000, (int) $order->total);
        $this->assertSame(5000, (int) $order->pay);
        $this->assertSame(7000, (int) $order->due);
        $this->assertSame('Canonical store flow note', $order->note);
        $this->assertStringStartsWith('INV-', $order->invoice_no);

        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 4000,
            'total' => 8000,
        ]);
    }

    public function test_order_update_completes_order_and_decrements_stock(): void
    {
        $user = $this->createAuthorizedUser(['update order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 10,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_status' => OrderStatus::PENDING->value,
            'payment_type' => PaymentType::CASH->value,
            'pay' => 80,
            'due' => 0,
            'total' => 80,
            'sub_total' => 80,
            'total_products' => 2,
        ]);

        OrderDetails::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 40,
            'total' => 80,
        ]);

        $response = $this->put(route('orders.update', $order));

        $response
            ->assertRedirect(route('orders.complete'))
            ->assertSessionHas('success', 'Order has been approved and stock updated!');

        $this->assertSame(OrderStatus::COMPLETE, $order->fresh()->order_status);
        $this->assertSame(8, $product->fresh()->quantity);
    }

    public function test_order_update_rejects_already_completed_order(): void
    {
        $user = $this->createAuthorizedUser(['update order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 10,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_status' => OrderStatus::COMPLETE->value,
            'payment_type' => PaymentType::CASH->value,
            'pay' => 80,
            'due' => 0,
            'total' => 80,
            'sub_total' => 80,
            'total_products' => 2,
        ]);

        OrderDetails::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 40,
            'total' => 80,
        ]);

        $response = $this->from(route('orders.show', $order))
            ->put(route('orders.update', $order));

        $response
            ->assertRedirect(route('orders.show', $order))
            ->assertSessionHasErrors([
                'error' => 'This order is already complete.',
            ]);

        $this->assertSame(10, $product->fresh()->quantity);
    }

    public function test_order_update_rejects_when_stock_is_insufficient(): void
    {
        $user = $this->createAuthorizedUser(['update order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create([
            'quantity' => 1,
        ]);

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_status' => OrderStatus::PENDING->value,
            'payment_type' => PaymentType::DUE->value,
            'pay' => 20,
            'due' => 60,
            'total' => 80,
            'sub_total' => 80,
            'total_products' => 2,
        ]);

        OrderDetails::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 40,
            'total' => 80,
        ]);

        $response = $this->from(route('orders.show', $order))
            ->put(route('orders.update', $order));

        $response
            ->assertRedirect(route('orders.show', $order))
            ->assertSessionHasErrors([
                'error' => "Insufficient stock for {$product->name}. Current stock: 1",
            ]);

        $this->assertSame(OrderStatus::PENDING, $order->fresh()->order_status);
        $this->assertSame(1, $product->fresh()->quantity);
    }

    public function test_order_invoice_download_uses_canonical_order_controller(): void
    {
        $user = $this->createAuthorizedUser(['view order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_status' => OrderStatus::COMPLETE->value,
            'payment_type' => PaymentType::CASH->value,
            'pay' => 80,
            'due' => 0,
            'total' => 80,
            'sub_total' => 80,
            'total_products' => 2,
        ]);

        OrderDetails::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 2,
            'unitcost' => 40,
            'total' => 80,
        ]);

        $response = $this->get(route('orders.downloadInvoice', $order));

        $response->assertOk();
        $response->assertViewIs('orders.print-invoice');
        $response->assertViewHas('order', fn ($viewOrder) => $viewOrder->is($order));
        $response->assertSee($order->invoice_no);
    }

    public function test_order_store_with_manual_total_override(): void
    {
        $user = $this->createAuthorizedUser(['create order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create(['quantity' => 10]);

        // Subtotal will be 2 * 40 = 80, but we override to 100
        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'payment_type' => PaymentType::CASH->value,
            'pay' => 60,
            'total_amount' => 100, // Manual override
            'invoiceProducts' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2,
                    'unitcost' => 40,
                    'total' => 80,
                ],
            ],
        ]);

        $response->assertRedirect(route('orders.index'));

        $order = Order::latest()->first();
        $this->assertSame(8000, (int) $order->sub_total);
        $this->assertSame(10000, (int) $order->total);
        $this->assertSame(6000, (int) $order->pay);
        $this->assertSame(4000, (int) $order->due);
    }

    public function test_order_store_rejects_invalid_pricing(): void
    {
        $user = $this->createAuthorizedUser(['create order']);
        $this->actingAs($user);

        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        // Case 1: Final total is zero or negative
        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'payment_type' => PaymentType::CASH->value,
            'pay' => 0,
            'total_amount' => 0,
            'invoiceProducts' => [
                ['product_id' => $product->id, 'quantity' => 1, 'unitcost' => 10, 'total' => 10],
            ],
        ]);

        $response->assertSessionHasErrors(['total_amount' => 'The total amount field must be greater than 0.']);

        // Case 2: Paid amount exceeds final total
        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'date' => now()->toDateString(),
            'payment_type' => PaymentType::CASH->value,
            'pay' => 200,
            'total_amount' => 100,
            'invoiceProducts' => [
                ['product_id' => $product->id, 'quantity' => 1, 'unitcost' => 10, 'total' => 10],
            ],
        ]);

        $response->assertSessionHasErrors(['error' => 'Unable to create order: Paid amount cannot exceed the final total.']);
    }

    public function test_order_completion_is_idempotent(): void
    {
        $user = $this->createAuthorizedUser(['update order']);
        $this->actingAs($user);

        $order = Order::factory()->create(['order_status' => OrderStatus::COMPLETE]);

        $response = $this->put(route('orders.update', $order));

        $response->assertSessionHasErrors(['error' => 'This order is already complete.']);
    }
}
