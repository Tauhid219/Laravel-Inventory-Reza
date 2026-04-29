<?php

namespace Database\Seeders;

use App\Enums\OrderStatus;
use App\Enums\PaymentType;
use App\Enums\PurchaseStatus;
use App\Enums\QuotationStatus;
use App\Enums\SupplierType;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\PurchaseDetails;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $demoOperatorId = User::query()
            ->where('email', 'admin@admin.com')
            ->value('id')
            ?? User::query()->where('email', 'ahmad@gmail.com')->value('id')
            ?? User::query()->where('email', 'demo-admin@reza-inventory.test')->value('id');

        $customers = [
            [
                'email' => 'demo.customer.one@example.com',
                'name' => 'Demo Customer One',
                'phone' => '8801700000001',
                'address' => 'Dhaka Demo Zone 1',
                'account_holder' => 'Demo Customer One',
                'account_number' => '10000001',
                'bank_name' => 'Dutch-Bangla Bank',
            ],
            [
                'email' => 'demo.customer.two@example.com',
                'name' => 'Demo Customer Two',
                'phone' => '8801700000002',
                'address' => 'Dhaka Demo Zone 2',
                'account_holder' => 'Demo Customer Two',
                'account_number' => '10000002',
                'bank_name' => 'BRAC Bank',
            ],
            [
                'email' => 'demo.customer.three@example.com',
                'name' => 'Demo Customer Three',
                'phone' => '8801700000003',
                'address' => 'Chattogram Demo Zone',
                'account_holder' => 'Demo Customer Three',
                'account_number' => '10000003',
                'bank_name' => 'City Bank',
            ],
        ];

        foreach ($customers as $customerData) {
            Customer::updateOrCreate(
                ['email' => $customerData['email']],
                $customerData
            );
        }

        $suppliers = [
            [
                'email' => 'supplier.alpha@example.com',
                'name' => 'Supplier Alpha',
                'phone' => '8801800000001',
                'address' => 'Gazipur Warehouse Road',
                'shopname' => 'Alpha Supply House',
                'type' => SupplierType::DISTRIBUTOR->value,
                'account_holder' => 'Supplier Alpha',
                'account_number' => '20000001',
                'bank_name' => 'Islami Bank',
            ],
            [
                'email' => 'supplier.beta@example.com',
                'name' => 'Supplier Beta',
                'phone' => '8801800000002',
                'address' => 'Narayanganj Trade Point',
                'shopname' => 'Beta Tech Traders',
                'type' => SupplierType::WHOLESALER->value,
                'account_holder' => 'Supplier Beta',
                'account_number' => '20000002',
                'bank_name' => 'Eastern Bank',
            ],
            [
                'email' => 'supplier.gamma@example.com',
                'name' => 'Supplier Gamma',
                'phone' => '8801800000003',
                'address' => 'Khulna Industry Block',
                'shopname' => 'Gamma Infrastructure',
                'type' => SupplierType::PRODUCER->value,
                'account_holder' => 'Supplier Gamma',
                'account_number' => '20000003',
                'bank_name' => 'Sonali Bank',
            ],
        ];

        foreach ($suppliers as $supplierData) {
            Supplier::updateOrCreate(
                ['email' => $supplierData['email']],
                $supplierData
            );
        }

        $customerIds = Customer::query()
            ->whereIn('email', collect($customers)->pluck('email'))
            ->pluck('id', 'email');

        $supplierIds = Supplier::query()
            ->whereIn('email', collect($suppliers)->pluck('email'))
            ->pluck('id', 'email');

        $products = Product::query()->orderBy('id')->take(6)->get()->keyBy('code');

        $orders = [
            [
                'invoice_no' => 'INV-DEMO-1001',
                'customer_email' => 'demo.customer.one@example.com',
                'order_date' => '2026-04-01',
                'order_status' => OrderStatus::PENDING->value,
                'payment_type' => PaymentType::DUE->value,
                'pay' => 0,
                'due' => 6100,
                'items' => [
                    ['product_code' => '001', 'quantity' => 2, 'unitcost' => 1400],
                    ['product_code' => '002', 'quantity' => 1, 'unitcost' => 3300],
                ],
                'note' => 'Demo pending order for dashboard review.',
            ],
            [
                'invoice_no' => 'INV-DEMO-1002',
                'customer_email' => 'demo.customer.two@example.com',
                'order_date' => '2026-04-03',
                'order_status' => OrderStatus::COMPLETE->value,
                'payment_type' => PaymentType::CASH->value,
                'pay' => 5100,
                'due' => 0,
                'items' => [
                    ['product_code' => '003', 'quantity' => 1, 'unitcost' => 1800],
                    ['product_code' => '004', 'quantity' => 1, 'unitcost' => 3300],
                ],
                'note' => 'Demo completed order for recruiter walkthrough.',
            ],
            [
                'invoice_no' => 'INV-DEMO-1003',
                'customer_email' => 'demo.customer.three@example.com',
                'order_date' => '2026-04-05',
                'order_status' => OrderStatus::COMPLETE->value,
                'payment_type' => PaymentType::CHEQUE->value,
                'pay' => 4700,
                'due' => 0,
                'items' => [
                    ['product_code' => '005', 'quantity' => 1, 'unitcost' => 2900],
                    ['product_code' => '006', 'quantity' => 2, 'unitcost' => 900],
                ],
                'note' => 'Demo completed order with multiple line items.',
            ],
        ];

        foreach ($orders as $orderData) {
            $items = $orderData['items'];
            unset($orderData['items']);

            $subTotal = collect($items)->sum(fn (array $item) => $item['quantity'] * $item['unitcost']);
            $vat = (int) round($subTotal * 0.05);
            $total = $subTotal + $vat;

            $order = Order::updateOrCreate(
                ['invoice_no' => $orderData['invoice_no']],
                [
                    'customer_id' => $customerIds[$orderData['customer_email']],
                    'order_date' => $orderData['order_date'],
                    'order_status' => $orderData['order_status'],
                    'total_products' => collect($items)->sum('quantity'),
                    'sub_total' => $subTotal,
                    'vat' => $vat,
                    'total' => $total,
                    'payment_type' => $orderData['payment_type'],
                    'pay' => $orderData['pay'],
                    'due' => $orderData['due'],
                    'note' => $orderData['note'],
                ]
            );

            OrderDetails::query()->where('order_id', $order->id)->delete();

            foreach ($items as $item) {
                $product = $products[$item['product_code']] ?? null;

                if (!$product) {
                    continue;
                }

                OrderDetails::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unitcost' => $item['unitcost'],
                    'total' => $item['quantity'] * $item['unitcost'],
                ]);
            }
        }

        $purchases = [
            [
                'purchase_no' => 'PO-DEMO-2001',
                'supplier_email' => 'supplier.alpha@example.com',
                'date' => '2026-04-02',
                'status' => PurchaseStatus::PENDING->value,
                'items' => [
                    ['product_code' => '001', 'quantity' => 10, 'unitcost' => 900],
                    ['product_code' => '002', 'quantity' => 4, 'unitcost' => 2100],
                ],
            ],
            [
                'purchase_no' => 'PO-DEMO-2002',
                'supplier_email' => 'supplier.beta@example.com',
                'date' => '2026-04-04',
                'status' => PurchaseStatus::APPROVED->value,
                'items' => [
                    ['product_code' => '003', 'quantity' => 6, 'unitcost' => 1200],
                    ['product_code' => '004', 'quantity' => 3, 'unitcost' => 2500],
                ],
            ],
            [
                'purchase_no' => 'PO-DEMO-2003',
                'supplier_email' => 'supplier.gamma@example.com',
                'date' => '2026-04-06',
                'status' => PurchaseStatus::APPROVED->value,
                'items' => [
                    ['product_code' => '005', 'quantity' => 5, 'unitcost' => 1700],
                    ['product_code' => '006', 'quantity' => 8, 'unitcost' => 650],
                ],
            ],
        ];

        foreach ($purchases as $purchaseData) {
            $items = $purchaseData['items'];
            unset($purchaseData['items']);

            $totalAmount = collect($items)->sum(fn (array $item) => $item['quantity'] * $item['unitcost']);

            $purchase = Purchase::updateOrCreate(
                ['purchase_no' => $purchaseData['purchase_no']],
                [
                    'supplier_id' => $supplierIds[$purchaseData['supplier_email']],
                    'date' => $purchaseData['date'],
                    'status' => $purchaseData['status'],
                    'total_amount' => $totalAmount,
                    'created_by' => $demoOperatorId,
                    'updated_by' => $demoOperatorId,
                ]
            );

            PurchaseDetails::query()->where('purchase_id', $purchase->id)->delete();

            foreach ($items as $item) {
                $product = $products[$item['product_code']] ?? null;

                if (!$product) {
                    continue;
                }

                PurchaseDetails::create([
                    'purchase_id' => $purchase->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unitcost' => $item['unitcost'],
                    'total' => $item['quantity'] * $item['unitcost'],
                ]);
            }
        }

        $quotationPayloads = [
            [
                'id' => 1,
                'customer_email' => 'demo.customer.one@example.com',
                'date' => '2026-04-07',
                'status' => QuotationStatus::PENDING->value,
                'customer_name' => 'Demo Customer One',
                'tax_percentage' => 5,
                'discount_percentage' => 0,
                'shipping_amount' => 150,
                'note' => 'Entry-level inventory quotation.',
                'items' => [
                    ['product_code' => '001', 'quantity' => 3, 'unit_price' => 1400],
                    ['product_code' => '002', 'quantity' => 1, 'unit_price' => 3300],
                ],
            ],
            [
                'id' => 2,
                'customer_email' => 'demo.customer.two@example.com',
                'date' => '2026-04-08',
                'status' => QuotationStatus::SENT->value,
                'customer_name' => 'Demo Customer Two',
                'tax_percentage' => 5,
                'discount_percentage' => 3,
                'shipping_amount' => 200,
                'note' => 'Sent quotation for networking equipment.',
                'items' => [
                    ['product_code' => '003', 'quantity' => 1, 'unit_price' => 2100],
                    ['product_code' => '004', 'quantity' => 1, 'unit_price' => 3600],
                ],
            ],
        ];

        foreach ($quotationPayloads as $quotationData) {
            $items = $quotationData['items'];
            unset($quotationData['items']);

            $subTotal = collect($items)->sum(fn (array $item) => $item['quantity'] * $item['unit_price']);
            $discountAmount = (int) round($subTotal * ($quotationData['discount_percentage'] / 100));
            $taxBase = $subTotal - $discountAmount;
            $taxAmount = (int) round($taxBase * ($quotationData['tax_percentage'] / 100));
            $totalAmount = $taxBase + $taxAmount + $quotationData['shipping_amount'];
            $reference = sprintf('QT%06d', $quotationData['id']);

            $quotation = Quotation::updateOrCreate(
                ['id' => $quotationData['id']],
                [
                    'date' => $quotationData['date'],
                    'reference' => $reference,
                    'customer_id' => $customerIds[$quotationData['customer_email']],
                    'customer_name' => $quotationData['customer_name'],
                    'tax_percentage' => $quotationData['tax_percentage'],
                    'tax_amount' => $taxAmount,
                    'discount_percentage' => $quotationData['discount_percentage'],
                    'discount_amount' => $discountAmount,
                    'shipping_amount' => $quotationData['shipping_amount'],
                    'total_amount' => $totalAmount,
                    'status' => $quotationData['status'],
                    'note' => $quotationData['note'],
                ]
            );

            QuotationDetails::query()->where('quotation_id', $quotation->id)->delete();

            foreach ($items as $item) {
                $product = $products[$item['product_code']] ?? null;

                if (!$product) {
                    continue;
                }

                $lineSubTotal = $item['quantity'] * $item['unit_price'];
                $lineTaxAmount = (int) round($lineSubTotal * ($quotationData['tax_percentage'] / 100));

                QuotationDetails::create([
                    'quotation_id' => $quotation->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'product_code' => $product->code,
                    'quantity' => $item['quantity'],
                    'price' => $lineSubTotal,
                    'unit_price' => $item['unit_price'],
                    'sub_total' => $lineSubTotal,
                    'product_discount_amount' => 0,
                    'product_discount_type' => 'fixed',
                    'product_tax_amount' => $lineTaxAmount,
                ]);
            }
        }
    }
}
