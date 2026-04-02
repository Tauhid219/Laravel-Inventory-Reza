<!DOCTYPE html>
<html lang="en">

<head>
    <title>
        {{ config('app.name') }} - Invoice {{ $order->invoice_no }}
    </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <link type="text/css" rel="stylesheet"
        href="{{ asset('assets/invoice/fonts/font-awesome/css/font-awesome.min.css') }}">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
    <style>
        .invoice-meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .invoice-meta-card {
            border: 1px solid #e9ecef;
            border-radius: 10px;
            padding: 14px 16px;
            background: #fff;
        }

        .invoice-meta-card .label {
            display: block;
            color: #6c757d;
            font-size: 13px;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .invoice-meta-card .value {
            color: #212529;
            font-size: 15px;
            font-weight: 600;
        }

        .invoice-note {
            margin-top: 24px;
            padding: 16px 18px;
            border-left: 4px solid #0d6efd;
            background: #f8f9fa;
            border-radius: 8px;
        }

        .invoice-note h5 {
            margin: 0 0 8px;
            font-size: 15px;
        }

        .invoice-note p {
            margin: 0;
            white-space: pre-wrap;
        }

        @media print {
            .invoice-meta-card {
                break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="logo">
                                        <h1>CSL Tech</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="invoice">
                                        <h1>
                                            Invoice # <span>{{ $order->invoice_no }}</span>
                                        </h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-info">
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <h4 class="inv-title-1">Customer</h4>
                                    <p class="inv-from-1">{{ $order->customer->name }}</p>
                                    <p class="inv-from-1">{{ $order->customer->phone }}</p>
                                    <p class="inv-from-1">{{ $order->customer->email }}</p>
                                    <p class="inv-from-2">{{ $order->customer->address }}</p>
                                </div>
                                <div class="col-sm-6 text-end mb-50">
                                    <h4 class="inv-title-1">Store</h4>
                                    <p class="inv-from-1">CSL Technologies Ltd</p>
                                    <p class="inv-from-1">(+88) 02 9890719</p>
                                    <p class="inv-from-1">info@csltechltd.com</p>
                                    <p class="inv-from-2">12B Ataturk Tower,
                                        22 Kemal Ataturk Avenue, Dhaka-1213</p>
                                </div>
                            </div>

                            <div class="invoice-meta-grid">
                                <div class="invoice-meta-card">
                                    <span class="label">Invoice Date</span>
                                    <span class="value">{{ optional($order->order_date)->format('d-m-Y') }}</span>
                                </div>
                                <div class="invoice-meta-card">
                                    <span class="label">Payment Type</span>
                                    <span class="value">{{ $order->payment_type }}</span>
                                </div>
                                <div class="invoice-meta-card">
                                    <span class="label">Paid Amount</span>
                                    <span class="value">{{ Number::currency($order->pay, 'BDT') }}</span>
                                </div>
                                <div class="invoice-meta-card">
                                    <span class="label">Due Amount</span>
                                    <span class="value">{{ Number::currency($order->due, 'BDT') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="order-summary">
                            <div class="table-outer">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            <th class="align-middle">Item</th>
                                            <th class="align-middle text-center">Price</th>
                                            <th class="align-middle text-center">Quantity</th>
                                            <th class="align-middle text-center">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        {{--                                            @foreach ($orderDetails as $item) --}}
                                        @foreach ($order->details as $item)
                                            <tr>
                                                <td class="align-middle">
                                                    {{ $item->product->name }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ Number::currency($item->unitcost, 'BDT') }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="align-middle text-center">
                                                    {{ Number::currency($item->total, 'BDT') }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>
                                                    Subtotal
                                                </strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->sub_total, 'BDT') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Tax</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->vat, 'BDT') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Paid</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->pay, 'BDT') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Due</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->due, 'BDT') }}
                                                </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="3" class="text-end">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="align-middle text-center">
                                                <strong>
                                                    {{ Number::currency($order->total, 'BDT') }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @if ($order->note)
                            <div class="invoice-note">
                                <h5>Order Note</h5>
                                <p>{{ $order->note }}</p>
                            </div>
                        @endif
                    </div>
                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="javascript:window.print()" class="btn btn-lg btn-print">
                            <i class="fa fa-print"></i>
                            Print Invoice
                        </a>
                        <a id="invoice_download_btn" class="btn btn-lg btn-download">
                            <i class="fa fa-download"></i>
                            Download Invoice
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/invoice/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/jspdf.min.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/html2canvas.js') }}"></script>
    <script src="{{ asset('assets/invoice/js/app.js') }}"></script>
</body>

</html>
