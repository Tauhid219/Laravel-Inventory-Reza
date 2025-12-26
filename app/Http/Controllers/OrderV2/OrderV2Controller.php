<?php

namespace App\Http\Controllers\OrderV2;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderV2\StoreOrderV2Request;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderV2Controller extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:view order')->only(['index', 'show', 'completedOrders', 'pendingOrders']);
        $this->middleware('permission:create order')->only(['create', 'store']);
        $this->middleware('permission:update order')->only(['update']);
        $this->middleware('permission:delete order')->only(['destroy']);
    }

    public function index()
    {
        return view('ordersV2.index');
    }

    public function show(Order $order)
    {
        // $order->loadMissing(['customer', 'details'])->get();
        $order->loadMissing(['customer', 'details.product.category', 'details.product.subCategory']);

        return view('ordersV2.show', [
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

        return view('ordersV2.completed-orders', [
            'orders' => $orders
        ]);
    }

    public function pendingOrders()
    {
        $orders = Order::with('customer', 'details.product.category', 'details.product.subCategory')
            ->where('order_status', OrderStatus::PENDING)
            ->latest()
            ->get();

        return view('ordersV2.pending-orders', [
            'orders' => $orders
        ]);
    }

    public function create()
    {
        return view('ordersV2.create', [
            'categories' => Category::select(['id', 'name'])->get(),
            'customers' => Customer::select(['id', 'name'])->get(),
        ]);
    }

    public function store(StoreOrderV2Request $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $order = Order::create([
                    'customer_id' => $request->customer_id,
                    'order_date' => $request->date,
                    'order_status' => $request->status,
                    'total_products' => count($request->invoiceProducts),
                    'sub_total' => $request->total_amount,
                    'vat' => 0,
                    'total' => $request->total_amount,
                    'invoice_no' => $request->invoice_no,
                    'payment_type' => 'Cash',
                    'pay' => $request->total_amount,
                    'due' => 0,
                    'note' => $request->note,
                ]);

                foreach ($request->invoiceProducts as $product) {
                    $order->details()->create([
                        'product_id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'unitcost' => $product['unitcost'],
                        'total' => $product['total'],
                    ]);
                }
            });

            return redirect()
                ->route('ordersV2.index')
                ->with('success', 'Order has been created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => 'Something went wrong: ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function update(Request $request, Order $order)
    {
        // TODO refactoring

        // Reduce the stock
        DB::transaction(function () use ($order) {
            $products = OrderDetails::where('order_id', $order->id)->get();

            foreach ($products as $product) {
                $productModel = Product::find($product->product_id);

                if ($productModel->quantity < $product->quantity) {
                    throw new \Exception('Insufficient stock for ' . $productModel->name);
                }

                $productModel->decrement('quantity', $product->quantity);
            }

            $order->update([
                'order_status' => OrderStatus::COMPLETE,
            ]);
        });

        return redirect()
            ->route('ordersV2.completedOrders')
            ->with('success', 'Order has been completed!');
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()
            ->route('ordersV2.index')
            ->with('success', 'Order has been deleted!');
    }
}
