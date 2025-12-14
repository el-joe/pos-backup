<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
<meta charset="UTF-8">
<title>Refund Invoice</title>
<style>
body { font-family: "Segoe UI", Arial, sans-serif; font-size: 12px; margin:0; padding:0; }
.receipt-80mm { width: 80mm; padding: 5px; background: #fff; color: #000; }
.company-name { text-align: center; font-weight: bold; font-size: 14px; margin-bottom: 5px; }
.receipt-title { text-align: center; font-weight: bold; font-size: 13px; margin-bottom: 5px; }
hr { border: 0; border-top: 1px dashed #000; margin: 5px 0; }
.meta p, .customer p { margin: 2px 0; font-size: 11px; }
table { width: 100%; border-collapse: collapse; font-size: 11px; margin-bottom: 5px; }
table th, table td { border: 1px solid #000; text-align: center; padding: 2px; }
</style>
</head>
<body>
<div class="receipt-80mm">
    <div class="company-name">Codefanz Software</div>
    <div class="receipt-title">Return / Refund</div>

    <!-- Document Meta -->
    <div class="meta">
        <p>Doc #: CR-2025-005</p>
        <p>Date: 2025-12-14</p>
        <p>Original Invoice: INV-2025-025</p>
        <p>Payment: Cash</p>
    </div>
    <hr>

    <!-- Customer Info -->
    <div class="customer">
        <p>Customer: Ahmed Hassan</p>
        <p>Phone: +20 112 345 6789</p>
    </div>
    <hr>

    <!-- Returned Items -->
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Item</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>White Rice</td>
                <td>1</td>
                <td>25.00</td>
                <td>25.00</td>
            </tr>
        </tbody>
    </table>
    <hr>

    <!-- Total -->
    <p>Total Refunded: 28.50</p>
    <p style="text-align:center;">Thank You!</p>
</div>
</body>
</html>
