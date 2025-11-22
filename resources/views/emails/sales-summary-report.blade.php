<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Summary Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background-color: #0d6efd;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
        }
        .body {
            padding: 20px;
        }
        .body h2 {
            margin-top: 0;
            color: #333;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        .summary-table th, .summary-table td {
            border: 1px solid #dee2e6;
            padding: 10px;
            text-align: right;
        }
        .summary-table th {
            background-color: #e9ecef;
            text-align: left;
        }
        .footer {
            text-align: center;
            padding: 15px;
            font-size: 12px;
            color: #6c757d;
            background-color: #f1f3f5;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Sales Summary Report</h1>
            <p>{{ now()->format('Y-m-d') }}</p>
        </div>
        <div class="body">
            <h2>Summary Details</h2>
            <table class="summary-table">
                <tr>
                    <th>Gross Sales</th>
                    <td>{{ $report['gross_sales'] }}</td>
                </tr>
                <tr>
                    <th>Discount</th>
                    <td>{{ $report['discount'] }}</td>
                </tr>
                <tr>
                    <th>Sales Return</th>
                    <td>{{ $report['sales_return'] }}</td>
                </tr>
                <tr>
                    <th>Net Sales</th>
                    <td>{{ $report['net_sales'] }}</td>
                </tr>
                <tr>
                    <th>VAT Payable</th>
                    <td>{{ $report['vat_payable'] }}</td>
                </tr>
                <tr>
                    <th>Total Collected</th>
                    <td>{{ $report['total_collected'] }}</td>
                </tr>
                <tr>
                    <th>COGS</th>
                    <td>{{ $report['cogs'] }}</td>
                </tr>
                <tr>
                    <th>Gross Profit</th>
                    <td>{{ $report['gross_profit'] }}</td>
                </tr>
            </table>
        </div>
        <div class="footer">
            &copy; 2025 Your Company. All rights reserved.
        </div>
    </div>
</body>
</html>
