<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Invoice</title>
    <link rel="stylesheet" href="{{ asset('invoices/css/invoice-a4.css') }}">
</head>
<body>

<div class="invoice-container">

    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <h1>{{ tenant('name') }}</h1>
            <!-- <p>IT Solutions & POS Systems</p> -->
            <p>Address: {{ tenant('address') ?? 'Cairo, Egypt' }}</p>
            <p>Phone: {{ tenant('phone') }}</p>
            @if(tenant('tax_number'))
            <p>Tax Number: {{ tenant('tax_number') }}</p>
            @endif
        </div>

        <div class="invoice-meta">
            <h2>Sales Invoice</h2>
            <table>
                <tr>
                    <td>Invoice No</td>
                    <td>{{ $order->invoice_number }}</td>
                </tr>
                <tr>
                    <td>Invoice Date</td>
                    <td>{{ carbon($order->created_at)->format('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td>Cash</td>
                </tr>
                <tr>
                    <td>Payment Status</td>
                    <td>{{ $order->payment_status }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="customer-section">
        <h4>Customer Details</h4>
        <div class="customer-grid">
            <p><strong>Name:</strong> {{ $order->customer?->name }}</p>
            <p><strong>Phone:</strong> {{ $order->customer?->phone }}</p>
            <p><strong>Address:</strong> {{ $order->customer?->address ?? '—' }}</p>
            <!--<p><strong>Tax Number:</strong> {{ $order->customer?->tax_number ?? '—' }}</p>-->
        </div>
    </div>

    <!-- Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->saleItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product?->name }}</td>
                    <td>{{ $item->unit?->name ?? 'PIECE' }}</td>
                    <td>{{ $item->qty }}</td>
                    <td>{{ $item->sell_price }}{{ currency()?->symbol }}</td>
                    <td>{{ $item->total }}{{ currency()->symbol }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <table>
            <tr>
                <td>Subtotal</td>
                <td>{{ $order->sub_total }}{{ currency()->symbol }}</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td>{{ ($order->discount_amount ?? 0 ) * -1}}{{ currency()->symbol }}</td>
            </tr>
            <tr>
                <td>VAT</td>
                <td>{{ $order->tax_amount ?? 0 }}{{ currency()->symbol }}</td>
            </tr>
            <tr class="grand-total">
                <td>Grand Total</td>
                <td>{{ $order->grand_total_amount }}{{ currency()->symbol }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="invoice-footer">
        <div class="sign">
            <p>Customer Signature</p>
        </div>
        <div class="sign">
            <p>Seller Signature</p>
        </div>
    </div>

    <div class="note">
        <p>This is a valid sales invoice for accounting and tax purposes</p>
    </div>

</div>

</body>
</html>
