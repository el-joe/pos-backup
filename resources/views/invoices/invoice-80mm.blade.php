<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>80mm Invoice</title>
    <link rel="stylesheet" href="{{ asset('invoices/css/invoice-80mm.css') }}">
</head>
<body>

<div class="receipt">

    <!-- Company Info -->
    <div class="center">
        <h2 class="company-name">{{ tenant('name') }} POS</h2>
        <!-- <p>Software & IT Solutions</p> -->
        @if(tenant('tax_number'))
        <p>Tax No: {{ tenant('tax_number') }}</p>
        @endif
        <p>Tel: {{ tenant('phone') }}</p>
    </div>

    <hr>

    <!-- Invoice Info -->
    <div class="row">
        <span>Invoice #</span>
        <span>{{ $order->invoice_number }}</span>
    </div>
    <div class="row">
        <span>Date</span>
        <span>{{ carbon($order->created_at)->format('Y-m-d H:i') }}</span>
    </div>

    <hr>

    <!-- Customer / Supplier -->
    <div class="section-title">Customer Details</div>
    <p>Name: {{ $order->customer?->name }}</p>
    <p>Phone: {{ $order->customer?->phone }}</p>

    <!-- Uncomment if supplier -->
    <!--
    <div class="section-title">Supplier Details</div>
    <p>Name: ABC Trading</p>
    <p>Tax No: 987654321</p>
    -->

    <hr>
    <!-- Items Table -->
    <table class="items">
        <thead>
            <tr>
                <th>Item</th>
                <th class="right">Qty</th>
                <th class="right">Price</th>
                <th class="right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->saleItems as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td class="right">{{ $item->qty }}</td>
                    <td class="right">{{ currencyFormat($item->sell_price, true) }}</td>
                    <td class="right">{{ currencyFormat($item->total, true) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <hr>

    <!-- Totals -->
    <div class="row">
        <span>Subtotal</span>
        <span>{{ currencyFormat($order->sub_total, true) }}</span>
    </div>
    <div class="row">
        <span>Discount</span>
        <span>-{{ currencyFormat($order->discount_amount ?? 0, true) }}</span>
    </div>
    <div class="row">
        <span>VAT </span>
        <span>{{ currencyFormat($order->tax_amount ?? 0, true) }}</span>
    </div>

    <div class="row total">
        <span>TOTAL</span>
        <span>{{ currencyFormat($order->grand_total_amount, true) }}</span>
    </div>

    <hr>

    <!-- Payment Info -->
    <div class="section-title">Payment</div>
    <div class="row">
        <span>Method</span>
        <span>Cash</span>
    </div>
    <div class="row">
        <span>Paid</span>
        <span>{{ currencyFormat($order->paid_amount, true) }}</span>
    </div>
    <!-- <div class="row">
        <span>Change</span>
        <span>1.60</span>
    </div> -->

    <hr>

    <!-- Footer -->
    <div class="center footer">
        <p>Thank you for your business</p>
        <p>Powered by {{ tenant('name') }} POS</p>
    </div>

</div>

</body>
</html>
