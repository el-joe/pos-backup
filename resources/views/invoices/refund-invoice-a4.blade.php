<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Return / Refund Invoice</title>
    <link rel="stylesheet" href="{{ asset('invoices/css/refund-invoice-a4.css') }}">
</head>
<body>
<div class="invoice-container">

    <!-- Header -->
    <div class="invoice-header">
        <div class="company-info">
            <h1>Codefanz Software Solutions</h1>
            <p>IT Solutions & POS Systems</p>
            <p>Address: Cairo, Egypt</p>
            <p>Phone: +20 100 000 0000</p>
            <p>Tax Number: 123456789</p>
        </div>

        <div class="invoice-meta">
            <h2>Return / Refund Invoice</h2>
            <table>
                <tr>
                    <td>Document No</td>
                    <td>CR-2025-005</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>2025-12-14</td>
                </tr>
                <tr>
                    <td>Original Invoice No</td>
                    <td>INV-2025-025</td>
                </tr>
                <tr>
                    <td>Payment Method</td>
                    <td>Cash</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Customer Info -->
    <div class="customer-section">
        <h4>Customer Details</h4>
        <div class="customer-grid">
            <p><strong>Name:</strong> Ahmed Hassan</p>
            <p><strong>Phone:</strong> +20 112 345 6789</p>
            <p><strong>Address:</strong> Giza, Egypt</p>
        </div>
    </div>

    <!-- Returned Items -->
    <table class="items-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Unit</th>
                <th>Returned Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>White Rice</td>
                <td>KG</td>
                <td>1</td>
                <td>25.00</td>
                <td>25.00</td>
            </tr>
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals-section">
        <table>
            <tr>
                <td>Subtotal</td>
                <td>25.00</td>
            </tr>
            <tr>
                <td>Discount</td>
                <td>0.00</td>
            </tr>
            <tr>
                <td>VAT (14%)</td>
                <td>3.50</td>
            </tr>
            <tr class="grand-total">
                <td>Total Refunded</td>
                <td>28.50</td>
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
        <p>This document represents an official credit note for accounting and tax purposes</p>
    </div>

</div>
</body>
</html>
