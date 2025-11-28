<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sales Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .invoice-container {
            max-width: 800px;
            margin: auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .section-title {
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            color: #444;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 10px;
        }
        .details-grid div {
            background: #fafafa;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #eee;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        table th {
            background: #f0f0f0;
        }
        .totals {
            margin-top: 20px;
            float: right;
            width: 50%;
        }
        .totals div {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <h2>Sales Invoice</h2>

        <div class="section-title">Company Details</div>
        <div class="details-grid">
            <div><strong>Company Name:</strong> {{ tenant('name') }}</div>
            <div><strong>Phone:</strong> {{ $order->branch?->phone }}</div>
            <div><strong>Address:</strong> {{ $order->branch?->address }}</div>
            <div><strong>Invoice Number:</strong> {{ $order->invoice_number }}</div>
            <div><strong>Invoice Date:</strong> {{ carbon($order->order_date)->format('Y-m-d') }}</div>
        </div>

        <div class="section-title">Customer Details</div>
        <div class="details-grid">
            <div><strong>Name:</strong> {{ $order->customer?->name }}</div>
            <div><strong>Phone:</strong> {{ $order->customer?->phone }}</div>
            <div><strong>Address:</strong> {{ $order->customer?->address }}</div>
            <div><strong>Customer Code:</strong> CUST-{{ $order->customer_id }}</div>
        </div>

        <div class="section-title">Order Summary</div>
        <div class="details-grid">
            <div><strong>Order ID:</strong> ORD-{{ $order->id }}</div>
            <div><strong>Order Date:</strong> {{ carbon($order->order_date)->format('Y-m-d') }}</div>
            <div><strong>Branch:</strong> {{ $order->branch?->name }}</div>
            <div><strong>Salesperson:</strong> {{ $order->createdBy?->name }}</div>
        </div>

        <div class="section-title">Items</div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->saleItems as $item)
                    <tr>
                        <td>{{ $item->product?->name }}</td>
                        <td>{{ $item->actual_qty }}</td>
                        <td>{{ $item->sell_price }}</td>
                        <td>{{ $item->actual_qty * $item->sell_price }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <div><span>Subtotal:</span> <span>{{ $order->sub_total }}</span></div>
            <div><span>Discount:</span> <span>{{ $order->discount_amount }}</span></div>
            @if($order->tax)
            <div><span>VAT ({{ $order->tax?->rate }}%):</span> <span>{{ $order->tax_amount }}</span></div>
            @endif
            <div><strong>Grand Total:</strong> <strong>{{ $order->grand_total_amount }}</strong></div>
        </div>

        <div style="clear: both"></div>

        <div class="footer">Thank you for shopping with us</div>
    </div>
</body>
</html>
