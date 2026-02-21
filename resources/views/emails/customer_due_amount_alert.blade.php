<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Due Amount Alert</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f5f6f8;
        }

        .email-container {
            max-width: 620px;
            margin: 30px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }

        .header {
            background: #4a6cf7;
            color: #ffffff;
            padding: 22px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
        }

        .content {
            padding: 25px;
            color: #333;
            line-height: 1.6;
            font-size: 15px;
        }

        .content h2 {
            margin-top: 0;
            color: #333;
            font-size: 20px;
        }

        .alert-box {
            background: #fef4e8;
            border-left: 4px solid #ff9900;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
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
        Customer Due Amount Alert
    </div>

    <div class="content">
        <p>Hello <strong>{{ $username }}</strong>,</p>

        <p>
            This is a reminder that you currently have an outstanding due amount in your account.
        </p>

        <div class="alert-box">
            <strong>Total Due Amount:</strong>
            <span style="font-size: 18px; font-weight: bold;">
                {{ $dueAmount }}
            </span>
        </div>

        <p>
            Please make sure to settle the payment as soon as possible to avoid any service interruptions.
        </p>

        <p>Thank you.</p>
    </div>

    <div class="footer">
        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    </div>
</div>

</body>
</html>
