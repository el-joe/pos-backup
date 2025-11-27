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
            <div><strong>VAT Number:</strong> 123-456-789</div>
            <div><strong>Invoice Number:</strong> INV-2025-001</div>
            <div><strong>Invoice Date:</strong> 2025-11-27</div>
        </div>

        <div class="section-title">Customer Details</div>
        <div class="details-grid">
            <div><strong>Name:</strong> Ahmed Mohamed</div>
            <div><strong>Phone:</strong> 01123456789</div>
            <div><strong>Address:</strong> Nasr City</div>
            <div><strong>Customer Code:</strong> CUST-145</div>
        </div>

        <div class="section-title">Order Summary</div>
        <div class="details-grid">
            <div><strong>Order ID:</strong> ORD-5521</div>
            <div><strong>Order Date:</strong> 2025-11-27</div>
            <div><strong>Branch:</strong> Main Branch</div>
            <div><strong>Salesperson:</strong> Mohamed Ali</div>
        </div>

        <div class="section-title">Items</div>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Discount</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>iPhone 14 Pro</td>
                    <td>1</td>
                    <td>45,000 EGP</td>
                    <td>0</td>
                    <td>45,000 EGP</td>
                </tr>
                <tr>
                    <td>Screen Protector</td>
                    <td>2</td>
                    <td>150 EGP</td>
                    <td>10 EGP</td>
                    <td>290 EGP</td>
                </tr>
                <tr>
                    <td>Mobile Cover</td>
                    <td>1</td>
                    <td>200 EGP</td>
                    <td>0</td>
                    <td>200 EGP</td>
                </tr>
            </tbody>
        </table>

        <div class="totals">
            <div><span>Subtotal:</span> <span>45,490 EGP</span></div>
            <div><span>Discount:</span> <span>10 EGP</span></div>
            <div><span>VAT (14%):</span> <span>6,368.6 EGP</span></div>
            <div><strong>Grand Total:</strong> <strong>51,848.6 EGP</strong></div>
        </div>

        <div style="clear: both"></div>

        <div class="footer">Thank you for shopping with us</div>
    </div>
</body>
</html>
