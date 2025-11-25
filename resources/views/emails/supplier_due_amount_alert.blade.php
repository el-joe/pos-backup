<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta-name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Due Amount Alert</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f6f8;
        }

        .email-container {
            max-width: 700px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.12);
        }

        .header {
            background: #d35400;
            color: #ffffff;
            padding: 25px;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .content {
            padding: 30px;
            color: #333;
            line-height: 1.7;
            font-size: 15px;
        }

        .summary-box {
            background: #fff7ec;
            border-left: 4px solid #d35400;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 25px;
            font-size: 15px;
        }

        .table-wrapper {
            margin-top: 20px;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th {
            background: #f2f2f2;
            padding: 12px;
            text-align: left;
            font-size: 14px;
            border-bottom: 2px solid #ddd;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .amount {
            font-weight: bold;
            color: #c0392b;
            font-size: 16px;
        }

        .footer {
            text-align: center;
            padding: 18px;
            font-size: 13px;
            color: #777;
            background-color: #f1f1f1;
        }
    </style>
</head>

<body>

<div class="email-container">
    <div class="header">
        Supplier Due Amount, Weekly Alert
    </div>

    <div class="content">

        <p>Dear Admin,</p>

        <p>
            Please be informed that the following suppliers currently have outstanding due amounts that require your attention.
        </p>

        <div class="summary-box">
            <strong>Total Suppliers with Outstanding Balance:</strong> {{ count($data) }}
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Supplier Name</th>
                        <th>Due Amount</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($data as $item)
                    <tr>
                        <td>{{ $item['supplierName'] }}</td>
                        <td class="amount">{{ $item['dueAmount'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <p style="margin-top: 25px;">
            Kindly review these accounts and take the necessary action to resolve the outstanding balances.
        </p>

        <p>Thank you.</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</div>

</body>
</html>
