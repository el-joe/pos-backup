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
        <h2 class="company-name">CODEFANZ POS</h2>
        <p>Software & IT Solutions</p>
        <p>Tax No: 123456789</p>
        <p>Tel: 01000000000</p>
    </div>

    <hr>

    <!-- Invoice Info -->
    <div class="row">
        <span>Invoice #</span>
        <span>INV-00025</span>
    </div>
    <div class="row">
        <span>Date</span>
        <span>2025-12-14 14:35</span>
    </div>

    <hr>

    <!-- Customer / Supplier -->
    <div class="section-title">Customer Details</div>
    <p>Name: Ahmed Hassan</p>
    <p>Phone: 01123456789</p>
    <p>Account: Customer Account</p>

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
            <tr>
                <td>Rice 1KG</td>
                <td class="right">2</td>
                <td class="right">25.00</td>
                <td class="right">50.00</td>
            </tr>
            <tr>
                <td>Sugar 1KG</td>
                <td class="right">1</td>
                <td class="right">18.00</td>
                <td class="right">18.00</td>
            </tr>
        </tbody>
    </table>

    <hr>

    <!-- Totals -->
    <div class="row">
        <span>Subtotal</span>
        <span>68.00</span>
    </div>
    <div class="row">
        <span>Discount</span>
        <span>-8.00</span>
    </div>
    <div class="row">
        <span>VAT (14%)</span>
        <span>8.40</span>
    </div>

    <div class="row total">
        <span>TOTAL</span>
        <span>68.40</span>
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
        <span>70.00</span>
    </div>
    <div class="row">
        <span>Change</span>
        <span>1.60</span>
    </div>

    <hr>

    <!-- Footer -->
    <div class="center footer">
        <p>Thank you for your business</p>
        <p>Powered by Codefanz POS</p>
    </div>

</div>

</body>
</html>
