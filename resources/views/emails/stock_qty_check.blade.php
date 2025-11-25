<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Stock Alert Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f7f7f7;
            padding: 20px;
            color: #333;
        }
        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        h2 {
            color: #d9534f;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
            font-size: 14px;
        }
        th {
            background: #f4f4f4;
        }
        .low {
            background: #fff3cd;
        }
        .out {
            background: #f8d7da;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>⚠️ Stock Alert Report</h2>
    <p>Dear Admin,</p>
    <p>The following products have low stock or are out of stock:</p>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th>Current Qty</th>
                <th>Alert Qty</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr class="{{ $product['status'] == 'out_of_stock' ? 'out' : 'low' }}">
                    <td>{{ $product['name'] }}</td>
                    <td>{{ $product['current_qty'] }}</td>
                    <td>{{ $product['alert_qty'] }}</td>
                    <td>{{ $product['status'] == 'out_of_stock' ? 'Out of Stock' : 'Low Stock' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 20px;">Please restock these products as soon as possible.</p>

    <p>Best regards,<br>Your POS System</p>
</div>

</body>
</html>
