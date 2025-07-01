<?php

namespace Tests\Feature;

// use App\Models\Customer;
// use App\Models\Product;
// use Gloudemans\Shoppingcart\Cart;
use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Session;
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
        $user = \App\Models\User::factory()->create();
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
        $response->assertViewHas('products', function ($products) use ($product) {
            return $products->contains($product);
        });
        $response->assertViewHas('carts'); // Optional, usually empty in fresh test
    }

    public function test_order_store(): void
    {
        // Arrange: Prepare data for the order
        $customer = Customer::factory()->create();
        $product = Product::factory()->create(); // Create a product
        $quantity = 2;
        $price = 100;

        // Add product to the cart (mocked version)
        Cart::shouldReceive('instance')
            ->once()
            ->with('order')
            ->andReturnSelf();

        Cart::shouldReceive('content')
            ->once()
            ->andReturn(collect([
                (object) [
                    'id' => $product->id,
                    'name' => $product->name,
                    'qty' => $quantity,
                    'price' => $price,
                    'subtotal' => $quantity * $price
                ]
            ]));

        // Act: Send request to store the order
        $response = $this->post(route('orders.store'), [
            'customer_id' => $customer->id,
            'order_date' => now(),
            'order_status' => OrderStatus::PENDING->value,
            'total_products' => $quantity,
            'sub_total' => $quantity * $price,
            'vat' => 15,
            'total' => ($quantity * $price) + 15,
            'invoice_no' => 'INV-1234567890',
            'payment_type' => PaymentType::CASH->value,
            'pay' => ($quantity * $price) + 15,
            'due' => 0,
        ]);

        // Assert: Check if order is created in the database
        $this->assertDatabaseHas('orders', [
            'customer_id' => $customer->id,
            'invoice_no' => 'INV-1234567890',
            'total' => ($quantity * $price) + 15,
        ]);

        // Assert: Check if order details are created in the order_details table
        $order = Order::latest()->first();
        $this->assertDatabaseHas('order_details', [
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unitcost' => $price,
            'total' => $quantity * $price,
        ]);

        // Assert: Check if cart is cleared after order is created
        $this->assertEmpty(Cart::instance('order')->content());

        // Assert: Check if the response redirects to the orders index with success message
        $response->assertRedirect(route('orders.index'));
        $response->assertSessionHas('success', 'Order has been created!');
    }
}
