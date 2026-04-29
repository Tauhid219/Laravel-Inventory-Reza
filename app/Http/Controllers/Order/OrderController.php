<?php

namespace App\Http\Controllers\Order;

use App\Actions\Orders\CompleteOrder;
use App\Actions\Orders\CreateOrder;
use App\Actions\Orders\ValidateRequestedOrderProducts;
use App\Data\Orders\CreateOrderData;
use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view order')->only(['index', 'show', 'completedOrders', 'pendingOrders', 'downloadInvoice']);
        $this->middleware('permission:create order')->only(['create', 'store']);
        $this->middleware('permission:update order')->only(['update']);
        $this->middleware('permission:delete order')->only(['destroy']);
        $this->middleware('deny.demo')->only(['create', 'store', 'update', 'destroy']);
    }

    public function index()
    {
        return view('orders.index');
    }

    public function show(Order $order)
    {
        $order->loadMissing(['customer', 'details.product.category', 'details.product.subCategory']);

        return view('orders.show', [
            'order' => $order,
            'note' => $order->note,
        ]);
    }

    public function completedOrders()
    {
        $orders = Order::with('customer', 'details.product.category', 'details.product.subCategory')
            ->where('order_status', OrderStatus::COMPLETE)
            ->latest()
            ->get();

        return view('orders.complete-orders', [
            'orders' => $orders
        ]);
    }

    public function pendingOrders()
    {
        $orders = Order::with('customer', 'details.product.category', 'details.product.subCategory')
            ->where('order_status', OrderStatus::PENDING)
            ->latest()
            ->get();

        return view('orders.pending-orders', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        return view('orders.create', [
            'categories' => Category::select(['id', 'name'])->get(),
            'customers' => Customer::select(['id', 'name'])->get(),
        ]);
    }

    public function store(
        StoreOrderRequest $request,
        CreateOrder $createOrder,
        ValidateRequestedOrderProducts $validateRequestedOrderProducts,
    )
    {
        try {
            $validateRequestedOrderProducts->handle($request->validated('invoiceProducts'));

            $createOrder->handle(CreateOrderData::fromArray($request->validated()));

            return redirect()
                ->route('orders.index')
                ->with('success', __('Order has been created successfully and is now pending approval.'));

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => __('Unable to create order: ') . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, Order $order, CompleteOrder $completeOrder)
    {
        try {
            $completeOrder->handle($order);

            return redirect()
                ->route('orders.complete')
                ->with('success', 'Order has been approved and stock updated!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order has been deleted!');
    }

    public function downloadInvoice(Order $order)
    {
        $order->loadMissing(['customer', 'details.product']);

        return view('orders.print-invoice', [
            'order' => $order,
        ]);
    }
}
