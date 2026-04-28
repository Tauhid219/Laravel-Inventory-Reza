<?php

namespace Tests\Feature;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class AdminLTEInteractiveRegressionTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_theme_switch_persists_and_renders_navigation_hooks(): void
    {
        $user = $this->createUser();

        $dashboardResponse = $this->actingAs($user)->get(route('dashboard'));

        $dashboardResponse
            ->assertOk()
            ->assertSee('data-widget="pushmenu"', false)
            ->assertSee('data-widget="treeview"', false)
            ->assertSee('dropdown-menu-right', false);

        $switchResponse = $this->from(route('dashboard'))
            ->actingAs($user)
            ->get(route('theme.switch', 'compact'));

        $switchResponse
            ->assertRedirect(route('dashboard'))
            ->assertSessionHas('adminlte_theme', 'compact');

        $this->actingAs($user)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Compact Dashboard');
    }

    public function test_order_create_page_renders_customer_tom_select_markup(): void
    {
        $user = $this->createAuthorizedUser(['create order']);

        Customer::factory()->create();
        Product::factory()->create();

        $response = $this->actingAs($user)->get(route('orders.create'));

        $response
            ->assertOk()
            ->assertViewIs('orders.create')
            ->assertSee('name="customer_id"', false)
            ->assertSee('data-tom-select', false)
            ->assertSee('name="payment_type"', false)
            ->assertSee('name="pay"', false);
    }

    public function test_purchase_create_page_renders_supplier_tom_select_markup(): void
    {
        $user = $this->createAuthorizedUser(['create purchase']);

        Supplier::factory()->create();

        $response = $this->actingAs($user)->get(route('purchases.create'));

        $response
            ->assertOk()
            ->assertViewIs('purchases.create')
            ->assertSee('name="supplier_id"', false)
            ->assertSee('data-tom-select', false);
    }

    public function test_due_show_page_renders_due_payment_modal_trigger(): void
    {
        $user = $this->createAuthorizedUser(['view order']);
        $order = $this->createDueOrder();

        $response = $this->actingAs($user)->get(route('due.show', $order));

        $response
            ->assertOk()
            ->assertViewIs('due.show')
            ->assertSee('data-bs-target="#modal-due"', false)
            ->assertSee('id="modal-due"', false);
    }

    public function test_due_edit_page_renders_customer_select_and_due_modal_trigger(): void
    {
        $user = $this->createAuthorizedUser(['update order']);
        $order = $this->createDueOrder();

        $response = $this->actingAs($user)->get(route('due.edit', $order));

        $response
            ->assertOk()
            ->assertViewIs('due.edit')
            ->assertSee('name="customer"', false)
            ->assertSee('data-tom-select', false)
            ->assertSee('data-bs-target="#modal-due"', false);
    }

    public function test_legacy_orders_v2_route_name_is_retired(): void
    {
        $this->assertFalse(Route::has('ordersV2.index'));
    }

    protected function createDueOrder(): Order
    {
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        $order = Order::factory()->create([
            'customer_id' => $customer->id,
            'order_status' => OrderStatus::PENDING->value,
            'payment_type' => PaymentType::DUE->value,
            'pay' => 50,
            'due' => 25,
            'vat' => 5,
            'total' => 80,
        ]);

        OrderDetails::create([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'quantity' => 1,
            'unitcost' => 50,
            'total' => 50,
        ]);

        return $order;
    }
}
